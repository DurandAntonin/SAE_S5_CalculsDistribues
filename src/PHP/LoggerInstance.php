<?php

namespace PHP;

use PHP\Enum_niveau_logger;
use PHP\MySQLDataManagement;

include_once "Enum_niveau_logger.php";
include_once "MySQLDataManagement.php";
include_once "Logging.php";

class LoggerInstance
{
    private Enum_niveau_logger $logLevel;

    private string $logInstanceName;

    private string $logRepo;

    private ?MySQLDataManagement $mySqlConnector;

    private ?string $tableName;

    function __construct(string $parLogInstanceName, Enum_niveau_logger $parLogLevel, string $parLogRepo, ?MySQLDataManagement $parMySqlConnector, ?string $parTableName){
        $this->logInstanceName = $parLogInstanceName;
        $this->logRepo = $parLogRepo;
        $this->logLevel = $parLogLevel;
        $this->mySqlConnector = $parMySqlConnector;
        $this->tableName = $parTableName;
    }

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

    public function debug(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::DEBUG->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::DEBUG, $userId, $date, $ip, $message);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::DEBUG, $userId, $date, $ip, $message);
        }
    }

    public function info(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::INFO->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::INFO, $userId, $date, $ip, $message);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::INFO, $userId, $date, $ip, $message);
        }
    }

    public function warning(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::WARNING->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::WARNING, $userId, $date, $ip, $message);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::WARNING, $userId, $date, $ip, $message);
        }
    }

    public function error(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::ERROR->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::ERROR, $userId, $date, $ip, $message);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::ERROR, $userId, $date, $ip, $message);
        }
    }

    public function critical(string $userId, \DateTime $date, string $ip, string $message): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::CRITICAL->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile(Enum_niveau_logger::CRITICAL, $userId, $date, $ip, $message);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable(Enum_niveau_logger::CRITICAL, $userId, $date, $ip, $message);
        }
    }

    private function writeLogInFile(Enum_niveau_logger $logLevel, string $userId, \DateTime $date, string $ip, string $message): void
    {
        echo $this->logRepo;
        //on vérifie que le répertoire contenant les différents répertoire de log par niveau existe
        if (is_dir($this->logRepo) && file_exists($this->logRepo)){
            //on va stocker le fichier de log dans un répertoire associé au niveau du log
            $logLeveRepo = $this->logRepo . $logLevel->name;

            //si ce répertoire n'existe pas, on le crée
            if (!file_exists($logLeveRepo)){
                mkdir($logLeveRepo);
            }

            //on a un fichier de log par mois
            $fileName = $logLeveRepo . "\logsprogramme_" . $date->format("Ym");

            //on tente d'ouvrir le fichier de log, dans lequel on insère le niveau, la date et le message du log
            $curseur = fopen($fileName, "a+");
            fputs($curseur, "{$logLevel->name}|userId:{$userId}|userIP:{$ip}|{$date->format("Y-m-d H:i:s")}|{$message}".PHP_EOL);
            fclose($curseur);
        }
    }

    private function writeLogInDbTable(Enum_niveau_logger $logLevel, $userId, $date, $ip, $message): void
    {
        //on crée un objet logging, contenant les différentes info du log
        $log = new Logging(guidv4(), $logLevel, $userId, $date, $ip, $message);

        //on insère dans une table sql les champs de cet objet
        $this->mySqlConnector->insert_log($this->tableName, $log);
    }

    public function disconnectLoggerInstanceBd(): void
    {
        $this->mySqlConnector->close_connexion_to_db();
    }

    public function __sleep(){
        return array('logLevel', 'logInstanceName', 'logRepo', 'mySqlConnector', 'tableName');
    }

    public function __wakeup(){

    }

    /**
     * @return \PHP\MySQLDataManagement|null
     */
    public function getMySqlConnector(): ?\PHP\MySQLDataManagement
    {
        return $this->mySqlConnector;
    }
}