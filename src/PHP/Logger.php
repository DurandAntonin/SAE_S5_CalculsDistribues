<?php

namespace PHP;

include_once "Enum_niveau_logger.php";

class Logger
{
    private array $listLoggerInstances;

    function __construct(Array $parLoggerConf){
        $this->listLoggerInstances = array();

        //on instancie un objet LoggerInstance pour chaque clé de la liste config passé en paramètre
        $ListeNomsLoggerInstances = array_keys($parLoggerConf);
        //print_r($ListeNomsLoggerInstances);
        foreach ($ListeNomsLoggerInstances as $nomLoggerInstance){
            $confLoggerInstance = $parLoggerConf[$nomLoggerInstance];
            //echo "<pre>";
            //print_r($confLoggerInstance);
            //echo "</pre>";
            $logRepo = $confLoggerInstance["logRepo"];
            $logToDb = $confLoggerInstance["logToDb"];
            $mySqliConnector = null;
            $tableName = null;

            $logLevel = $confLoggerInstance["logLevel"];
            //on instancie un type enumere en fonction du level du logger obtenu
            $logLevelEnum = Enum_niveau_logger::fromName($logLevel);
            //echo var_dump($logToDb);

            //on regarde si le logger doit aussi écrire dans la base de données
            if ($logToDb == 1){
                $configBd = $confLoggerInstance["configDbConnexion"];

                //on instancie un objet pour se connecter à la bd
                $bdHostName = $configBd["bdHostname"];
                $bdUserName = $configBd["bdUsername"];
                $bdPassword = $configBd["bdPassword"];
                $bdDataBase = $configBd["bdDatabase"];
                $tableName = $configBd["bdTableLogging"];

                $mySqliConnector = new MySQLDataManagement($bdHostName, $bdUserName, $bdPassword, $bdDataBase);
            }

            $loggerInstance = new LoggerInstance($nomLoggerInstance, $logLevelEnum, $logRepo, $mySqliConnector, $tableName);

            //on ajoute une instance de logger dans la liste des loggerInstances
            $this->listLoggerInstances[$nomLoggerInstance] = $loggerInstance;
        }

    }

    function __toString(): string
    {
        $string = "";
        //on parcourt la liste des loggerInstance dans le champ de type liste
        $ListeNomsLoggerInstances = array_keys($this->listLoggerInstances);
        foreach ($ListeNomsLoggerInstances as $nomLoggerInstance){
            $string .= "$nomLoggerInstance : {$this->listLoggerInstances["$nomLoggerInstance"]}<br>";
        }

        return $string;
    }

    function getLoggerInstance(string $loggerInstanceName){
        return $this->listLoggerInstances[$loggerInstanceName];
    }

    public function __sleep(){
        return array('listLoggerInstances');
    }

    public function __wakeup(){
    }
}