<?php

namespace PHP;

use PHP\Enum_niveau_logger;
use PHP\MySQLDataManagement;

include_once "Enum_niveau_logger.php";
include_once "MySQLDataManagement.php";
include_once "Logging.php";

/**
 * Instance logger qui permet d'enregistrer des logs. <br>
 * Il permet d'enregistrer et d'avoir un historique des événements de l'application. (Ex: l'instance est appelé pour enregistrer l'action de modification de mot de passe d'un utilisateur)
 * <br>
 * Possède 2 modes d'enregistrement d'un log :
 * <ul>
 *     <li>Mode 'file' où le log est enregistré dans un répertoire local du host hébergeant l'application</li>
 *     <li>Mode 'bd' où le log est enregistré dans une base de données MySQL. Ce mode utilise la classe MySQLDataManagement pour communiquer avec un serveur MySQL.</li>
 * </ul>
 *
 * @version 1.0
 */
class LoggerInstance
{
    /**
     * @var Enum_niveau_logger $logLevel Niveau de priorité du log utilisé pour déterminer si un log peut être enregistré
     */
    private Enum_niveau_logger $logLevel;

    /**
     * @var string $logInstanceName Nom de l'instance, utilisé pour la récupérer
     */
    private string $logInstanceName;

    /**
     * @var string $logRepo Répertoire pour enregistrer des logs si l'instance logger est en mode 'file'
     */
    private string $logRepo;

    /**
     * @var MySQLDataManagement|null $mySqlConnector Objet pour enregistrer le log dans une base de données MySQL si l'instance est en mode 'bd'
     * @see MySQLDataManagement
     */
    private ?MySQLDataManagement $mySqlConnector;

    /**
     * @var string|null $tableName Table SQL dans laquelle les logs sont enregistrés si l'instance logger est en mode 'bd'
     */
    private ?string $tableName;

    /**
     * Constructeur de la classe
     *
     * @param string $parLogInstanceName Niveau de log utilisé pour vérifier si un log peut être enregistré
     * @param \PHP\Enum_niveau_logger $parLogLevel Nom de l'instance
     * @param string $parLogRepo Répertoire pour enregistrer des logs en mode 'file'
     * @param \PHP\MySQLDataManagement|null $parMySqlConnector Objet pour enregistrer le log dans une base de données MySQL si l'instance est en mode 'bd'
     * @param string|null $parTableName Table SQL dans laquelle les logs sont enregistrés si l'instance logger est en mode 'bd'
     *
     * @version 1.0
     */
    function __construct(string $parLogInstanceName, Enum_niveau_logger $parLogLevel, string $parLogRepo, ?MySQLDataManagement $parMySqlConnector, ?string $parTableName){
        $this->logInstanceName = $parLogInstanceName;
        $this->logRepo = $parLogRepo;
        $this->logLevel = $parLogLevel;
        $this->mySqlConnector = $parMySqlConnector;
        $this->tableName = $parTableName;
    }

    /**
     * Méthode magique, retourne l'objet sous forme d'une chaîne de caractères lorsque ce dernier est affiché.
     *
     * @return string L'objet retourné sous forme d'une chaîne de caractères
     *
     * @version 1.0
     */
    function __toString(): string
    {
        $string = "";
        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField) {
            $value = "";

            //si le champ est un type énuméré, on affiche le nom de la valeur
            if ($valueField instanceof Enum_niveau_logger)
                $value .= $valueField->name;
            else
                $value .= $valueField;

            $string .= "<br>&nbsp&nbsp&nbsp$nameField : $value";
        }
        return $string;
    }

    /**
     * Enregistrer un log de priorité **DEBUG**.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param string $userId  Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function debug(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::DEBUG->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::DEBUG, $userId, $date, $ip, $message);
            if (!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::DEBUG, $userId, $date, $ip, $message);
        }
    }

    /**
     * Enregistrer un log de priorité **INFO**.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param string $userId  Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function info(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::INFO->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::INFO, $userId, $date, $ip, $message);
            if (!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::INFO, $userId, $date, $ip, $message);
        }
    }

    /**
     * Enregistrer un log de priorité **WARNING**.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param string $userId  Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function warning(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::WARNING->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::WARNING, $userId, $date, $ip, $message);
            if (!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::WARNING, $userId, $date, $ip, $message);
        }
    }

    /**
     * Enregistrer un log de priorité **ERROR**.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param string $userId  Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function error(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::ERROR->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::ERROR, $userId, $date, $ip, $message);
            if (!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::ERROR, $userId, $date, $ip, $message);
        }
    }

    /**
     * Enregistrer un log de priorité **CRITICAL**.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param string $userId  Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function critical(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::CRITICAL->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::CRITICAL, $userId, $date, $ip, $message);
            if (!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::CRITICAL, $userId, $date, $ip, $message);
        }
    }

    /**
     * Permet d'enregistrer un log dans un répertoire spécifique en fonction de son niveau de priorité.
     *
     * Il appelle la méthode 'writeLogInFile' pour enregistrer le log dans un répertoire si le mode de l'instance logger est 'file'. <br>
     * Il appelle la méthode 'writeLogInDbTable' pour enregistrer le log dans une base de données MySQL si le mode de l'instance logger est 'bd'
     *
     * @param Enum_niveau_logger $logLevel
     * @param string $userId Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    private function writeLogInFile(Enum_niveau_logger $logLevel, string $userId, \DateTime $date, string $ip, string $message): void
    {
        //echo $this->logRepo;
        //on vérifie que le répertoire contenant les différents répertoire de log par niveau existe
        if (is_dir($this->logRepo) && file_exists($this->logRepo)){
            //on va stocker le fichier de log dans un répertoire associé au niveau du log
            $logLeveRepo = $this->logRepo . $logLevel->name;

            //si ce répertoire n'existe pas, on le crée
            if (!file_exists($logLeveRepo)){
                mkdir($logLeveRepo);
            }

            //on a un fichier de log par mois
            $fileName = $logLeveRepo . "/log_" . $date->format("Ym");

            //on tente d'ouvrir le fichier de log, dans lequel on insère le niveau, la date et le message du log
            $curseur = fopen($fileName, "a+");
            fputs($curseur, "{$logLevel->name}|userId:{$userId}|userIP:{$ip}|{$date->format("Y-m-d H:i:s")}|{$message}".PHP_EOL);
            fclose($curseur);
        }
    }

    /**
     * Permet d'enregistrer un log dans une base de données MySQL.
     *
     * Insère le log dans la base de données
     *
     * @param Enum_niveau_logger $logLevel
     * @param string $userId Identifiant de l'utilisateur
     * @param \DateTime $date Date du log
     * @param string $ip Adresse IP de l'utilisateur
     * @param string $message Descriptif de l'événement
     *
     * @return void Résultat d'exécution de la requête d'insertion du log dans la bd
     *
     * @version 1.0
     */
    private function writeLogInDbTable(Enum_niveau_logger $logLevel, string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on crée un objet logging, contenant les différentes info du log
        $log = new Logging(guidv4(), $logLevel, $userId, $date, $ip, $message);

        //on insère dans une table sql les champs de cet objet
        $this->mySqlConnector->insert_log($this->tableName, $log);
    }

    /**
     * Ferme la connexion au serveur MySQL.
     *
     * @return void
     *
     * @version 1.0
     */
    public function disconnectLoggerInstanceBd(): void
    {
        $this->mySqlConnector->close_connexion_to_db();
    }

    /**
     * Méthode magique qui retourne l'objet sérializé pour permettre son stockage dans la variable **$_SESSION**.
     *
     * @return string[] Liste des champs de l'objet
     *
     * @version 1.0
     */
    public function __sleep(){
        return array('logLevel', 'logInstanceName', 'logRepo', 'mySqlConnector', 'tableName');
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

    /**
     * Getter du champ _mySqlConnector_
     *
     * @return \PHP\MySQLDataManagement|null
     *
     * @see LoggerInstance::$mySqlConnector
     *
     * @version 1.0
     */
    public function getMySqlConnector(): ?\PHP\MySQLDataManagement
    {
        return $this->mySqlConnector;
    }
}