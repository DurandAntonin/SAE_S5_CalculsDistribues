<?php

namespace PHP;

use mysqli;
use mysqli_result;

include_once "User.php";
include_once "Logger.php";
include_once "Enum_role_user.php";
include_once "Utility.php";

/**
 * Utilise l'API mysqli pour se connecter et exécuter des requêtes dans une base de données MySQL.
 * Permet d'exécuter des requêtes d'insertion, de sélection et de suppression qui sont prédéfinies.
 *
 * @version 1.0
 */
class MySQLDataManagement{
    /**
     * @var mysqli $connector Interface se connectant à une base de données MySQL
     */
    private mysqli $connector;

    /**
     * @var string $hostname Adresse IP du serveur MySQL
     */
    private string $hostname;

    /**
     * @var string $username Utilisateur serveur MySQL
     */
    private string $username;

    /**
     * @var string $password Mot de passe de l'utilisateur
     */
    private string $password;

    /**
     * @var string $database Base de données utilisée comme contexte d'exécution des requêtes SQL
     */
    private string $database;

    /**
     * @var int $connectionErreur Indique si une erreur est survenue lors de la connexion au serveur (Ex: 1 si une erreur est survenue)
     */
    private int $connectionErreur;

    /**
     * @var string|null $connectionErreurMessage Message d'erreur de la connexion au serveur MySQL
     */
    private ?string $connectionErreurMessage;

    /**
     * @var array Liste des paramètres utilisés pour la dernière connexion au serveur. Les paramètres sont : hostname, username, password, database
     */
    private array $lastConnexionParams;

    /**
     * Constructeur de la classe, Instancie une connexion à une base de données d'un serveur MySQL
     *
     * @param string $parHostname Adresse IP du serveur MySQL
     * @param string $parUsername Utilisateur pour se connecteur au serveur
     * @param string $parPassword Mot de passe de l'utilisateur
     * @param string $parDatabase Base de données utilisée comme contexte d'exécution des requêtes SQL
     *
     * @version 1.0
     */

    function __construct(string $parHostname, string $parUsername, string $parPassword, string $parDatabase){
        $this->hostname = $parHostname;
        $this->username = $parUsername;
        $this->password = $parPassword;
        $this->database = $parDatabase;

        //on se connecte à la base de données
        $this->connect_to_db();
    }

    /**
     * Méthode magique, retourne l'objet sous forme d'une chaîne de caractères lorsque ce dernier est affiché. <br>
     * Le champ $connector de l'objet est ignoré et ne sera pas stocké dans la chaîne de caractères.
     *
     * @return string L'objet retourné sous forme d'une chaîne de caractères
     *
     * @version 1.0
     */
    function __toString(): string
    {
        $string = "";
        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            //on n'affiche pas le champ de type mysqli car il n'y a pas de méthode mysql
            if (gettype($valueField) == "object" && get_class($valueField) == "mysqli")
                continue;

            //si le champ est une liste, on affiche le contenu de cette liste
            else if (gettype($valueField) == "array"){
                $subString = "";
                for ($i=0;$i<count($valueField);$i++)
                    $subString.= "<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$i}:{$valueField[$i]}";
                $string .= $subString;
            }

            else
                $string .= "<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$nameField : $valueField";
        }
        return $string;
    }

    /**
     * Ouvre une connexion au serveur MySQL.
     * Si une erreur survient au cours de cette connexion comme une base de données non existante, ou un mouvais mot de passe pour l'utilisateur MySQL, le champ $connectionErreur est mis à 1 et le message d'erreur est stocké dans $connectionErreurMessage.
     *
     * @return void
     *
     * @version 1.0
     */
    private function connect_to_db(): void
    {
        try{
            //on se connecteur à la base de données
            $this->connector = @new mysqli($this->hostname, $this->username, $this->password, $this->database);

            //on stocke les informations de cette connexion dans le champ approprié
            $this->lastConnexionParams = [$this->hostname, $this->username, $this->password, $this->database];

            $this->connectionErreur = 0;
        }
        catch (\Exception|\mysqli_sql_exception $e){
            $this->connectionErreur = 1;
            $this->connectionErreurMessage = $e;
        }
    }

    /**
     * Réouvre une connexion au serveur MySQL, avec les derniers paramètres de connexion utilisés
     *
     * @return void
     *
     * @version 1.0
     */
    public function reconnect_to_bd(): void
    {
        if (count($this->lastConnexionParams) == 4){
            $this->hostname = $this->lastConnexionParams[0];
            $this->username = $this->lastConnexionParams[1];
            $this->password = $this->lastConnexionParams[2];
            $this->database = $this->lastConnexionParams[3];
        }

        $this->connect_to_db();
    }

    /**
     * Getter du champ _connectionErreur_
     *
     * @return int
     *
     * @see MySQLDataManagement::$connectionErreur
     *
     * @version 1.0
     */
    public function getConnectionErreur(): int
    {
        return $this->connectionErreur;
    }

    /**
     * Getter du champ _connectionErreurMessage_
     * @return string|null
     *
     *  @see MySQLDataManagement::$connectionErreurMessage
     *
     * @version 1.0
     */
    public function getConnectionErreurMessage(): ?string
    {
        return $this->connectionErreurMessage;
    }

    /**
     * Exécute une requête SQL pour récupérer l'ensemble des utilisateurs de la base de données
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *      '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *      '**errorMessage**' string : message d'erreur <br>
     *      '**result**' null<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @return array
     *
     * @see Logging
     *
     * @version 1.0
     */
    public function get_users(string $table): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select * from $table";

            //on exécute la requete pour obtenir tous les users
            $stmt = $this->connector->prepare($request);
            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();


            //on retourne une liste de users mappée
            return $this->mappMySqliResultToUser($results);
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour insérer un enregistrement log.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *      '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *      '**errorMessage**' string : message d'erreur <br>
     *      '**result**' null<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les différents logs
     * @param Logging $log Objet contenant les différentes informations d'un log
     * @return array
     *
     * @see Logging
     *
     * @version 1.0
     */
    public function insert_log(string $table, Logging $log): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "insert into $table(logId, logLevel, userId, date, ip, description) values(?,?,?,?,?,?)";

            //on exécute la requete pour insérer un log dans la table des enregistrements des actions
            $stmt = $this->connector->prepare($request);

            $logId = $log->getLogId();
            $enum_niveau_logger = $log->getLogLevel()->name;
            $userId = $log->getUserId();
            $dateTime = $log->getDate()->format("Y-m-d H:i:s");
            $ip = $log->getIp();
            $description = $log->getDescription();
            $stmt-> bind_param("ssssss", $logId, $enum_niveau_logger, $userId, $dateTime, $ip, $description);

            $stmt -> execute();
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour vérifier si un login ou une adresse mail est déjà utilisé par un compte utilisateur.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *   [ <br>
     *     '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *     '**errorMessage**' string : message d'erreur <br>
     *     '**result**' null|boolean : boolean qui indique si des utilisateurs ont été renvoyés ou non <br>
     *   ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $mail Adresse mail à rechercher
     * @param string $login Login à rechercher
     *
     * @return array Liste contenant les paramètres de retour, sous forme clé-valeur.
     *
     * @version 1.0
     *
     */
    public function check_mail_login_taken(string $table, string $mail, string $login): array
    {
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];
        try{
            //on regarde si le login est déjà pris
            $resultRequeteGetUserByLogin = $this->get_user_by_login($table, $login);

            //on regarde si la requete a renvoyée une erreur
             if ($resultRequeteGetUserByLogin["error"] == 1){
                 $listeResultParamsFunction["error"] = 1;
                 $listeResultParamsFunction["errorMessage"] = $resultRequeteGetUserByLogin["errorMessage"];
             }

             //on regarde si la requete retourne au moins 1 user
             else if (count($resultRequeteGetUserByLogin["result"]) > 0){
                 $listeResultParamsFunction["result"] = -1;
             }

             else{
                 //le login n'est pas pris, on regarde maintenant si l'adresse mail est prise
                 $resultRequeteGetUserByMail = $this->get_user_by_mail($table, $mail);

                 //on regarde si la requete a renvoyée une erreur
                 if ($resultRequeteGetUserByMail["error"] == 1){
                     $listeResultParamsFunction["error"] = 1;
                     $listeResultParamsFunction["errorMessage"] = $resultRequeteGetUserByMail["errorMessage"];
                 }

                 //on regarde si la requete retourne au moins 1 user
                 else if (count($resultRequeteGetUserByMail["result"]) > 0){
                     $listeResultParamsFunction["result"] = -2;
                 }

                 else{
                     //le login et le mail ne sont pas pris, on indique ca dans le parametre result
                     $listeResultParamsFunction["result"] = 1;
                 }
             }

        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour retourner l'utilisateur identifié par une adresse mail.
     *
     * Les informations de l'utilisateur retourné sont mappées.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *   [ <br>
     *     '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *     '**errorMessage**' string : message d'erreur <br>
     *     '**result**' null|boolean : boolean qui indique si des utilisateurs ont été renvoyés ou non <br>
     *   ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $mail Adresse mail à utiliser pour la recherche d'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @see MySQLDataManagement::mappMySqliResultToUser()
     *
     * @version 1.0
     */
    public function get_user_by_mail(string $table, string $mail): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        $result = array();

        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where userMail = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s", $mail);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            //on retourne une liste de users mappée
            $result = $this->mappMySqliResultToUser($results);
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        $listeResultParamsFunction["result"] = $result;
        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour retourner l'utilisateur identifié par un login.
     *
     * Les informations de l'utilisateur retourné sont mappées.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null|boolean : boolean qui indique si des utilisateurs ont été renvoyés ou non<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $login Login à utiliser pour la recherche d'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @see MySQLDataManagement::mappMySqliResultToUser()
     *
     * @version 1.0
     */
    public function get_user_by_login(string $table, string $login): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        $result = array();

        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s", $login);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            //on retourne une liste de users mappée
            $result = $this->mappMySqliResultToUser($results);
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        $listeResultParamsFunction["result"] = $result;
        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour retourner les utilisateurs dont leur adresse mail contient une chaîne de caractères donnée. <br>
     * Une pagination est utilisée pour ne retourner qu'une partie des utilisateurs trouvés selon une limite et un offset.
     *
     * Les informations de l'utilisateur retourné sont mappées, puis converties en format Json.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null|string : Liste des utilisateurs convertis en format Json<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $mailAppro Chaîne de caractères
     * @param Pagination $pagination Pagination pour la requête SQL
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @see MySQLDataManagement::mappMySqliResultToUser()
     *
     * @version 1.0
     */
    public function get_users_by_mail_appro(string $table, string $mailAppro, Pagination $pagination): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select userId, userMail, login, lastName, firstName, role from $table where userMail like ? order by userMail asc limit ? offset ?";
            $mailConcat = "%" . $mailAppro . "%";

            //on exécute la requete pour obtenir des users dont leur mail contient celui passé en paramètre
            $stmt = $this->connector->prepare($request);

            //on prend n éléments
            $limit = $pagination->getLimit();
            $offset = $pagination->getOffset();

            $stmt-> bind_param("sss",$mailConcat, $limit, $offset);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            //on retourne une liste des users
            $listeUsersMappe = $this->mappMySqliResultToUser($results);

            //on convertit chaque objet user en un string json, qu'on stocke dans une chaine json
            $usersSerialized = "[";
            for ($i=0; $i<count($listeUsersMappe)-1; $i++){
                $usersSerialized .= $listeUsersMappe[$i]->serialize() . ",";
            }

            if (count($listeUsersMappe) > 0)
                $usersSerialized .= $listeUsersMappe[count($listeUsersMappe)-1]->serialize();
            $usersSerialized .= $usersSerialized . "]";

            $listeResultParamsFunction["result"] = $usersSerialized;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour retourner le nombre d'utilisateurs dont leur adresse mail contient une chaîne de caractères donnée.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null|int : nombre d'utilisateurs retournés<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $mailAppro Chaîne de caractères
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @see MySQLDataManagement::mappMySqliResultToUser()
     *
     * @version 1.0
     */
    public function get_number_users_by_mail(string $table, string $mailAppro): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select count(userId) from $table where userMail like ?";
            $mailConcat = "%" . $mailAppro . "%";

            //on exécute la requete pour obtenir le nombre de users dont leur mail contient celui passé en paramètre
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s",$mailConcat);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            $ligne = $results->fetch_row();
            $listeResultParamsFunction["result"] = $ligne[0];
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour vérifier si le mot de passe d'un utilisateur est identique à un mot de passe donné.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null|boolean : indique si le mot de passe est identique ou non<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $login Login de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function verif_password(string $table, string $login, string $password_to_verify): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select password from $table where login = ?";

            //on exécute la requete pour obtenir le mot de passe d'un utilisateur selon son login
            $stmt = $this->connector->prepare($request);
            $stmt->bind_param("s", $login);

            $stmt->execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt->get_result();

            //on vérifie si la requete s'est exécutée sans erreur
            if ($stmt->affected_rows == 1) {
                //on compare les 2 mots de passes
                $listResults = $results->fetch_array(MYSQLI_ASSOC);
                $listeResultParamsFunction["result"] = compare_passwords($password_to_verify, $listResults["password"]);
            }
            else{
                $listeResultParamsFunction["result"] = false;
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on renvoie l'erreur dans la liste des résultats
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }
        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour tester si un mot de passe est fragile. <br>
     * Il est considéré comme fragile s'il figure dans la table SQL.
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null|boolean : indique si le mot de passe est fragile ou non<br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant des mots de passe considérés comme fragiles
     * @param string $password Mot de passe à tester
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function verif_solidite_password(string $table, string $password): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select password from $table where password = ?";

            //on exécute la requete savoir si le mot de passe en parametre est présent dans la base de données des mdp faibles
            $stmt = $this->connector->prepare($request);
            $stmt->bind_param("s", $password);
            $stmt->execute();

            $result = $stmt->get_result();

            $listeResultParamsFunction["result"] = $result->num_rows == 0;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour insérer un nouvel utilisateur dans la base de données. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant des mots de passe considérés comme fragiles
     * @param User $userToInsert Objet contenant les informations de l'utilisateur
     * @param string $userPassword Mot de passe de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @see User
     *
     * @version 1.0
     */
    public function insert_user(string $table, User $userToInsert, string $userPassword): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "insert into $table(userId, userMail, login, lastName, firstName, password) values(?, ?, ?, ?, ?, ?)";

            //on exécute la requete pour insérer un nouvel user
            $stmt = $this->connector->prepare($request);

            $userId = $userToInsert->getId();
            $login = $userToInsert->getLogin();
            $userMail = $userToInsert->getMail();
            $lastName = $userToInsert->getLastName();
            $firstName = $userToInsert->getFirstName();
            $stmt->bind_param("ssssss",$userId, $userMail, $login, $lastName, $firstName, $userPassword);

            $stmt->execute();
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour supprimer un utilisateur de la base de données. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $tableUser Table SQL contenant les informations des utilisateurs
     * @param string $userId Identifiant de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function supprimer_user(string $tableUser, string $userId): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "delete from $tableUser where userId = ?";

            //on supprime l'utilisateur de la table des users
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s", $userId);

            $stmt -> execute();
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour modifier le mot de passe d'un utilisateur. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $tableUsers Table SQL contenant les informations des utilisateurs
     * @param string $tableWeakPasswords Table SQL contenant des mots de passes fragiles
     * @param string $userId Identifiant de l'utilisateur
     * @param string $userLogin Login de l'utilisateur
     * @param string $newPassword Nouveau mot de passe de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function change_user_password(string $tableUsers, string $tableWeakPasswords, string $userId, string $userLogin, string $newPassword): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        //on vérifie déjà si le nouveau mdp est différent de l'ancien
        $resultVerifPassword = $this->verif_password($tableUsers, $userLogin, $newPassword);

        if ($resultVerifPassword["error"] == 0){

            if (!$resultVerifPassword["result"]){
                //on regarde si le nouveau mdp est fragile
                $resultSoliditePassword = $this->verif_solidite_password($tableWeakPasswords, $newPassword);

                if ($resultSoliditePassword["error"] == 0){

                    if ($resultSoliditePassword["result"]){
                        try{
                            //on met à jour le mdp
                            $request = "update $tableUsers set password = ? where userId = ?";

                            //on hash le mdp
                            $newPasswordHashed = hash_password($newPassword);

                            //on exécute la requete pour changer le mot de passe d'un user
                            if ($stmt = $this->connector->prepare($request)){
                                $stmt-> bind_param("ss", $newPasswordHashed, $userId);

                                $stmt -> execute();
                            }
                        }
                        catch (\mysqli_sql_exception $e) {
                            //on enregistre dans la liste des param de result, le message d'erreur
                            $listeResultParamsFunction["error"] = 1;
                            $listeResultParamsFunction["errorMessage"] = $resultSoliditePassword["errorMessage"];

                            return $listeResultParamsFunction;
                        }
                    }
                    else{
                        $listeResultParamsFunction["result"] = -2;

                        return $listeResultParamsFunction;
                    }

                }
            }
            else{
                $listeResultParamsFunction["result"] = -1;

                return $listeResultParamsFunction;
            }
        }
        else{
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $resultVerifPassword["errorMessage"];

            return $listeResultParamsFunction;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour modifier le login d'un utilisateur. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $userId Identifiant de l'utilisateur
     * @param string $newLogin Nouveau login de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function change_user_login(string $table, string $userId, string $newLogin): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            //on vérifie que le login n'a pas déja été utilisé
            $requestCheckLoginExists = "select userId from $table where login = ?";

            //on exécute la requete pour changer le login d'un user
            if ($stmt = $this->connector->prepare($requestCheckLoginExists)){
                $stmt-> bind_param("s", $newLogin);
                $stmt -> execute();

                //on quitte la fonction si au moins 1 tuple a été renvoyé
                $result = $stmt->get_result();
                if ($result->num_rows != 0)
                    $listeResultParamsFunction["result"] = false;

                else{
                    $requestUpdateLogin = "update $table set login = ? where userId = ?";
                    //on exécute la requete pour changer le login d'un user
                    if ($stmt = $this->connector->prepare($requestUpdateLogin)){
                        $stmt-> bind_param("ss", $newLogin, $userId);

                        $stmt -> execute();

                        $listeResultParamsFunction["result"] = true;
                    }
                }
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour modifier l'adresse mail d'un utilisateur. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $userId Identifiant de l'utilisateur
     * @param string $newMail Nouvelle addresse mail de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function change_user_mail(string $table, string $userId, string $newMail): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            //on vérifie que le mail n'a pas déja été utilisé
            $requestCheckMailExists = "select userId from $table where userMail = ?";

            //on exécute la requete pour changer le mail d'un user
            if ($stmt = $this->connector->prepare($requestCheckMailExists)){
                $stmt-> bind_param("s", $newMail);
                $stmt -> execute();

                //on quitte la fonction si au moins un tuple a été renvoyé
                $result = $stmt->get_result();
                if ($result->num_rows != 0)
                    $listeResultParamsFunction["result"] = false;

                else{
                    $requestUpdateLogin = "update $table set userMail = ? where userId = ?";
                    //on exécute la requete pour changer le mail d'un user
                    if ($stmt = $this->connector->prepare($requestUpdateLogin)){
                        $stmt-> bind_param("ss", $newMail, $userId);

                        $stmt -> execute();

                        $listeResultParamsFunction["result"] = true;
                    }
                }
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour modifier le nom d'un utilisateur. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $userId Identifiant de l'utilisateur
     * @param string $newLastName Nouveau nom de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function change_user_lastname(string $table, string $userId, string $newLastName): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "update $table set lastName = ? where userId = ?";

            //on exécute la requete pour changer le nom d'un user
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("ss", $newLastName, $userId);

            $stmt -> execute();
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Exécute une requête SQL pour modifier le prénom d'un utilisateur. <br>
     *
     * Les paramètres de retour sont stockés dans une liste sous la forme : <br>
     *  [ <br>
     *    '**error**' int : indique si une erreur est survenue durant l'exécution de la requête <br>
     *    '**errorMessage**' string : message d'erreur <br>
     *    '**result**' null <br>
     *  ] <br>
     *
     * @param string $table Table SQL contenant les informations des utilisateurs
     * @param string $userId Identifiant de l'utilisateur
     * @param string $newFirstName Nouveau prénom de l'utilisateur
     *
     * @return array Liste contenant les paramètres de retour
     *
     * @version 1.0
     */
    public function change_user_firstname(string $table, string $userId, string $newFirstName): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "update $table set firstName = ? where userId = ?";

            //on exécute la requete pour changer le prénom d'un user
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("ss", $newFirstName, $userId);

            $stmt -> execute();
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    /**
     * Ferme la connexion au serveur MySQL
     *
     * @return void
     *
     * @version 1.0
     */
    public function close_connexion_to_db(): void
    {
        $this->connector -> close();
    }

    /**
     *
     *
     * @param mysqli_result $result
     * @return array
     *
     * @see User
     *
     * @version 1.0
     */
    private function mappMySqliResultToUser(mysqli_result $result): array
    {
        $listUsers = array();

        //on mappe chaque résultat à un objet User, stocké dans une liste
        while ($listResultsUsers = $result->fetch_array(MYSQLI_ASSOC)){
            $user = "";

            if ($listResultsUsers["role"] == "USER")
                $user = new User($listResultsUsers["userId"], $listResultsUsers["userMail"], $listResultsUsers["login"], $listResultsUsers["lastName"], $listResultsUsers["firstName"], Enum_role_user::USER);
            elseif ($listResultsUsers["role"] == "ADMIN")
                $user = new \PHP\User($listResultsUsers["userId"], $listResultsUsers["userMail"], $listResultsUsers["login"], $listResultsUsers["lastName"], $listResultsUsers["firstName"], Enum_role_user::ADMIN);
            else
                continue;

            $listUsers[] = $user;
        }

        //on renvoie la liste des Users
        return $listUsers;
    }

    /**
     * Méthode magique qui retourne l'objet sérializé pour permettre son stockage dans la variable **$_SESSION**.
     *
     * @return string[] Liste des champs de l'objet
     *
     * @version 1.0
     */
    public function __sleep(){
        return array('connector', 'hostname', 'username', 'password', 'database', 'connectionErreur', 'connectionErreurMessage', 'lastConnexionParams');
    }

    /**
     * Méthode magique utilisée lors de la recréation de l'objet.
     *
     * @return void
     *
     * @version 1.0
     */
    public function __wakeup(){
    }
}

?>