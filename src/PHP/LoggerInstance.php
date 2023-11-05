<?php

namespace PHP;

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

            $string .= "\n\t$nameField : $value";
        }
        return $string;
    }

    public function debug(string $message, array $parameters, string $dateFileName, string $dateComplete): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::DEBUG->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::DEBUG);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::DEBUG);
        }
    }

    public function info(string $message,array $parameters, string $dateFileName, string $dateComplete): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::INFO->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::INFO);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::INFO);
        }
    }

    public function warning(string $message,array $parameters, string $dateFileName, string $dateComplete): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value >= Enum_niveau_logger::WARNING->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::WARNING);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::WARNING);
        }
    }

    public function error(string $message,array $parameters, string $dateFileName, string $dateComplete): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= Enum_niveau_logger::ERROR->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::ERROR);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::ERROR);
        }
    }

    public function critical(string $message,array $parameters, string $dateFileName, string $dateComplete): void
    {
        //on vérifie que le niveau permet d'écrire un log de niveau debug
        if ($this->logLevel->value <= \PHP\Enum_niveau_logger::CRITICAL->value){
            //on regarde à quel endroit on écrit le log, soit dans un fichier, soit dans une table sql, soit dans les deux
            if ($this->logRepo != "")
                $this->writeLogInFile($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::CRITICAL);
            elseif(!is_null($this->mySqlConnector))
                $this->writeLogInDbTable($message, $parameters, $dateFileName, $dateComplete, Enum_niveau_logger::CRITICAL);
        }
    }

    private function writeLogInFile(string $message,array $parameters, string $dateFileName, string $dateComplete, Enum_niveau_logger $niveau): void
    {
        //on a un fichier de log par mois
        $fileName = $this->logRepo . "logsprogramme_" . $dateFileName;

        //on tente d'ouvrir le fichier de log, dans lequel on insère le niveau, la date et le message du log
        $curseur = fopen($fileName, "a+");
        fputs($curseur,$niveau->name . "-" . $dateComplete . " : " . $message . PHP_EOL);
        fputs($curseur, "Parameters : " . implode(";", $parameters) . PHP_EOL);
        fputs($curseur, "");
        fclose($curseur);
    }

    private function writeLogInDbTable(string $message,array $parameters, string $dateFileName, string $dateComplete, Enum_niveau_logger $niveau){
        //on insère dans une table dans la base de données le log
        //$this->mySqlConnector->insertLog($this->tableName, $niveau->name, );
    }

    public function __sleep(){
        return array('loggerInstanceName', 'repoLog', 'niveauLog');
    }

    public function __wakeup(){

    }
}