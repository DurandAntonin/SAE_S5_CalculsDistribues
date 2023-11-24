<?php

namespace PHP;

use mysqli;
use mysqli_result;

include_once "User.php";
include_once "Logger.php";
include_once "Enum_role_user.php";

class MySQLDataManagement{
    private mysqli $connector;
    private string $hostname;
    private string $username;
    private string $password;
    private string $database;
    private int $connectionErreur;

    private string $connectionErreurMessage;
    private array $lastConnexionParams;

    function __construct(string $parHostname, string $parUsername, string $parPassword, string $parDatabase){
        $this->hostname = $parHostname;
        $this->username = $parUsername;
        $this->password = $parPassword;
        $this->database = $parDatabase;

        //on se connecte à la base de données
        $this->connect_to_db();
    }

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

    public function getConnectionErreur(): int
    {
        return $this->connectionErreur;
    }

    public function getConnectionErreurMessage(): ?string
    {
        return $this->connectionErreurMessage;
    }

    public function get_users(string $table): int|array
    {
        try{
            $request = "select * from $table";

            //on exécute la requete pour obtenir tous les users
            if ($stmt = $this->connector->prepare($request)){
                $stmt -> execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt -> get_result();


                //on retourne une liste de users mappée
                return $this->mappMySqliResultToUser($results);
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function insert_log(string $table, Logging $log)
    {
        try{
            $request = "insert into $table(logId, logLevel, userId, date, ip, description) values(?,?,?,?,?,?)";

            //on exécute la requete pour insérer un log dans la table des enregistrements des actions
            if ($stmt = $this->connector->prepare($request)){
                $logId = $log->getLogId();
                $enum_niveau_logger = $log->getLogLevel()->name;
                $userId = $log->getUserId();
                $dateTime = $log->getDate()->format("Y-m-d H:i:s");
                $ip = $log->getIp();
                $description = $log->getDescription();
                $stmt-> bind_param("ssssss", $logId, $enum_niveau_logger, $userId, $dateTime, $ip, $description);

                $stmt -> execute();
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function check_mail_login_taken(string $table, string $mail, string $login): array
    {
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];
        try{
            $request = "select userId from $table where userMail = ? or login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("ss", $mail, $login);

            $stmt -> execute();
            $results = $stmt -> get_result();

            //on regarde si un ou plusieurs users ont été renvoyés
            $listeResultParamsFunction["result"] = $results->num_rows != 0;

        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    public function get_user_by_mail(string $table, string $mail): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where userMail = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s", $mail);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            //on retourne une liste de users mappée
            $listeResultParamsFunction["result"] = $this->mappMySqliResultToUser($results);
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }

        return $listeResultParamsFunction;
    }

    public function get_user_by_login(string $table, string $login): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            $stmt = $this->connector->prepare($request);
            $stmt-> bind_param("s", $login);

            $stmt -> execute();

            //on récupere les résultats sous forme d'une liste
            $results = $stmt -> get_result();

            //on retourne une liste de users mappée
            $listeResultParamsFunction["result"] = $this->mappMySqliResultToUser($results);
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre dans la liste des param de result, le message d'erreur
            $listeResultParamsFunction["error"] = 1;
            $listeResultParamsFunction["errorMessage"] = $e;
        }
        return $listeResultParamsFunction;
    }

    public function get_users_by_mail_appro(string $table, string $mailAppro, Pagination $pagination): string|int
    {
        try{
            $request = "select userId, userMail, login, lastName, firstName, role from $table where userMail like ? order by userMail asc limit ? offset ?";

            //on exécute la requete pour obtenir des users dont leur mail contient celui passé en paramètre
            if ($stmt = $this->connector->prepare($request)){
                $mailConcat = "%" . $mailAppro . "%";

                //on prend n éléments
                $limit = $pagination->getLimit();
                $offset = $pagination->getOffset();

                $stmt-> bind_param("sss",$mailConcat, $limit, $offset);

                $stmt -> execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt -> get_result();

                //on retourne une liste des users
                $listeUsersMappe = $this->mappMySqliResultToUser($results);

                //print_r($listeUsersMappe);
                //on convertit chaque objet user en un string json, qu'on stocke dans une chaine json
                $usersSerialized = "[";
                for ($i=0; $i<count($listeUsersMappe)-1; $i++){
                    $usersSerialized .= $listeUsersMappe[$i]->serialize() . ",";
                    //echo $usersSerialized . "\n\n";
                }

                if (count($listeUsersMappe) > 0)
                    $usersSerialized .= $listeUsersMappe[count($listeUsersMappe)-1]->serialize();

                return $usersSerialized . "]";
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function get_number_users_by_mail(string $table, string $mailAppro): string|int
    {
        try{
            $request = "select count(userId) from $table where userMail like ?";

            //on exécute la requete pour obtenir le nombre de users dont leur mail contient celui passé en paramètre
            if ($stmt = $this->connector->prepare($request)){
                $mailConcat = "%" . $mailAppro . "%";

                $stmt-> bind_param("s",$mailConcat);

                $stmt -> execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt -> get_result();

                $ligne = $results->fetch_row();
                return $ligne[0];
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function verif_password(string $table, string $login, string $password_to_verify): mysqli_result|array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];
        try{
            $request = "select password from $table where login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
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
            } else {
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

    public function insert_user(string $table, \PHP\User $userToInsert, string $userPassword): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "insert into $table(userId, userMail, login, lastName, firstName, password) values(?, ?, ?, ?, ?, ?)";

            //on exécute la requete pour insérer un nouvel user
            $stmt = $this->connector->prepare($request);

            $userId = $userToInsert->getId();
            $login = $userToInsert->getLogin();
            $userMail = $userToInsert->getUserMail();
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

    public function delete_user(string $tableUser, string $userId): array
    {
        //on va stocker les différents paramètres de renvoi dans une liste
        $listeResultParamsFunction = ["error"=>0, "errorMessage"=>"", "result"=>null];

        try{
            $request = "delete from $tableUser where userId = ?";

            //on supprime le user de la table des users
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

    public function change_user_password(string $table, string $userId, string $newPassword): int
    {
        try{
            $request = "update $table set password = ? where userId = ?";

            //on exécute la requete pour changer le mot de passe d'un user
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("ss", $newPassword, $userId);

                $stmt -> execute();
                return 1;
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function change_user_login(string $table, string $userId, string $newLogin): int
    {
        try{
            //on vérifie que le login n'a pas déja été utilisé
            $requestCheckLoginExists = "select userId from $table where login = ?";

            //on exécute la requete pour changer le login d'un user
            if ($stmt = $this->connector->prepare($requestCheckLoginExists)){
                $stmt-> bind_param("s", $newLogin);
                $stmt -> execute();

                //on regarde si un tuple a été renvoyé
                $result = $stmt->get_result();
                if ($result->num_rows != 0)
                    return -2;

                $requestUpdateLogin = "update $table set login = ? where userId = ?";
                //on exécute la requete pour changer le mot de passe d'un user
                if ($stmt = $this->connector->prepare($requestUpdateLogin)){
                    $stmt-> bind_param("ss", $newLogin, $userId);

                    $stmt -> execute();
                    return 1;
                }
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function change_user_mail(string $table, string $userId, string $newMail): int
    {
        try{
            //on vérifie que le login n'a pas déja été utilisé
            $requestCheckLoginExists = "select userId from $table where userMail = ?";

            //on exécute la requete pour changer le login d'un user
            if ($stmt = $this->connector->prepare($requestCheckLoginExists)){
                $stmt-> bind_param("s", $newMail);
                $stmt -> execute();

                //on regarde si un tuple a été renvoyé
                $result = $stmt->get_result();
                if ($result->num_rows != 0)
                    return -2;

                $requestUpdateLogin = "update $table set userMail = ? where userId = ?";
                //on exécute la requete pour changer le mot de passe d'un user
                if ($stmt = $this->connector->prepare($requestUpdateLogin)){
                    $stmt-> bind_param("ss", $newMail, $userId);

                    $stmt -> execute();
                    return 1;
                }
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function change_user_lastname(string $table, string $userId, string $newLastName): int
    {
        try{
            $request = "update $table set lastName = ? where userId = ?";

            //on exécute la requete pour changer le nom d'un user
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("ss", $newLastName, $userId);

                $stmt -> execute();

                //on vérifie si la requete s'est exécutée sans erreur
                if ($stmt -> errno == 0){
                    return 1;
                }
                else{
                    return -1;
                }
            }
            else{
                return -1;
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function change_user_firstname(string $table, string $userId, string $newFirstName): int
    {
        try{
            $request = "update $table set firstName = ? where userId = ?";

            //on exécute la requete pour changer le prénom d'un user
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("ss", $newFirstName, $userId);

                $stmt -> execute();

                //on vérifie si la requete s'est exécutée sans erreur
                if ($stmt -> errno == 0){
                    return 1;
                }
                else{
                    return -1;
                }
            }
            else{
                return -1;
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function close_connexion_to_db(): void
    {
        $this->connector -> close();
    }

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

    public function __sleep(){
        return array('connector', 'hostname', 'username', 'password', 'database', 'connectionErreur', 'connectionErreurMessage', 'lastConnexionParams');
    }

    public function __wakeup(){
    }
}

?>