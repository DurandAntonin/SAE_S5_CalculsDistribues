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
    private int $connection_erreur;

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
            if (gettype($valueField) == "mysqli")
                continue;

            $string .= "\n\t$nameField : $valueField";
        }
        return $string;
    }

    private function connect_to_db(): void
    {
        try{
            //on se connecteur à la base de données
            $this->connector = new mysqli($this->hostname, $this->username, $this->password, $this->database);

            $this->connection_erreur = 0;
        }
        catch (\mysqli_sql_exception $e){
            $this->connection_erreur = 1;

            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
        }

    }

    public function getConnectionErreur(): int
    {
        return $this->connection_erreur;
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

    public function insert_log(string $table, Logging $log){
        try{
            $request = "insert into $table(logId, logLevel, userId, date, ip, description) values(?, ?,?,?,?,?)";

            //on exécute la requete pour insérer un log dans la table des enregistrements des actions
            if ($stmt = $this->connector->prepare($request)){
                $logId = $log->getLogId();
                $enum_niveau_logger = $log->getLogLevel();
                $userId = $log->getUserId();
                $dateTime = $log->getDate();
                $ip = $log->getIp();
                $description = $log->getDescription();
                $stmt-> bind_param("ssssss", $logId, $enum_niveau_logger, $userId, $dateTime, $ip, $description);

                $stmt -> execute();
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function check_mail_login_taken(string $table, string $mail, string $login): bool|int
    {
        try{
            $request = "select userId from $table where userMail = ? or login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("ss", $mail, $login);

                $stmt -> execute();
                $results = $stmt -> get_result();

                //on regarde si un ou plusieurs users ont été renvoyés
                if ($results->num_rows != 0)
                    return true;
                return false;
            }
            return -1;
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
            return -1;
        }
    }

    public function get_user_by_mail(string $table, string $mail): int|array
    {
        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where userMail = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("s", $mail);

                $stmt -> execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt -> get_result();
                //print_r($stmt -> errno);
                //echo $stmt->error;

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

    public function get_user_by_login(string $table, string $login): int|array
    {
        try{
            $request = "select userId, userMail, login, lastName, firstName, password, role from $table where login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("s", $login);

                $stmt -> execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt -> get_result();
                //print_r($stmt -> errno);
                //echo $stmt->error;

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

    public function verif_password(string $table, string $login, string $password_to_verify): bool|int
    {
        try{
            $request = "select password from $table where login = ?";

            //on exécute la requete pour obtenir un user d'après un mail
            if ($stmt = $this->connector->prepare($request)) {
                $stmt->bind_param("s", $login);

                $stmt->execute();

                //on récupere les résultats sous forme d'une liste
                $results = $stmt->get_result();

                //on vérifie si la requete s'est exécutée sans erreur
                if ($stmt->affected_rows == 1) {
                    //on compare les 2 mots de passes
                    $listResults = $results->fetch_array(MYSQLI_ASSOC);
                    return compare_passwords($password_to_verify, $listResults["password"]);

                } else {
                    return false;
                }
            }
            else{
                return -1;
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym")->format("Ym"), getTodayDate()->format("Y-m-d H:i:s")->format());
            return -1;
        }
    }

    public function verif_solidite_password(string $table, string $password): bool|int
    {
        try{
            $request = "select password from $table where password = ?";

            //on exécute la requete savoir si le mot de passe en parametre est présent dans la base de données des mdp faibles
            if ($stmt = $this->connector->prepare($request)) {
                $stmt->bind_param("s", $password);
                $stmt->execute();


                //echo $stmt->get_result()->num_rows;
                $result = $stmt->get_result();
                //print_r($result);
                //print_r($result->fetch_array());
                if ($result->num_rows == 0)
                    return true;
                else
                    return false;
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

    public function insert_user(string $table, \PHP\User $userToInsert, string $userPassword): int
    {
        try{
            $request = "insert into $table(userId, userMail, login, lastName, firstName, password) values(?, ?, ?, ?, ?, ?)";

            //on exécute la requete pour insérer un nouvel user
            if ($stmt = $this->connector->prepare($request)) {
                $userId = $userToInsert->getId();
                $login = $userToInsert->getLogin();
                $userMail = $userToInsert->getUserMail();
                $lastName = $userToInsert->getLastName();
                $firstName = $userToInsert->getFirstName();
                $stmt->bind_param("ssssss",$userId, $userMail, $login, $lastName, $firstName, $userPassword);

                $stmt->execute();

                return 1;
            }
            else {
                return -1;
            }
        }
        catch (\mysqli_sql_exception $e) {
            //on enregistre à l'aide d'un logger l'erreur, ainsi que les paramètres d'exécution
            //$this->logger->error($e, array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));

            return -1;
        }
    }

    public function delete_user(string $tableUser, string $userId): int
    {
        try{
            $request = "delete from $tableUser where userId = ?";

            //on supprime le user de la table des users
            if ($stmt = $this->connector->prepare($request)){
                $stmt-> bind_param("s", $userId);

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
}

?>