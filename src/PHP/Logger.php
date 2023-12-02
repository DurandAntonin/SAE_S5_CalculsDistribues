<?php

namespace PHP;

include_once "Enum_niveau_logger.php";

/**
 * Gère des logger instances.
 * Ces dernières sont stockés dans une liste, et sont accessibles d'après leur nom.
 *
 * @version 1.0
 */
class Logger
{
    /**
     * @var array Liste de logger instances
     *
     * @version 1.0
     */
    private array $listLoggerInstances;

    /**
     * Constructeur de la classe.
     * Initialise des logger instances en fonction de la configuration donnée.
     * Cette dernière est au format Json, doit avoir la même structure et les mêmes champs que dans les exemples ci-dessous.
     *
     * Ci-dessous un exemple de configuration d'un logger instance en mode 'db' : <br>
     * {
     *     "nomLoggerInstance" : {
     *          "logLevel" : [NIVEAU PRIORITE],
     *          "logRepo" : "",
     *          "logToDb : 1,
     *          "configDbConnexion" : {
     *              "bdHostname" : [ADRESSE IP SERVEUR MYSQL],
     *              "bdUsername" : [USER MYSQL],
     *              "bdPassword" : [MOT DE PASSE USER MYSQL],
     *              "bdDatabase" : [BASE DE DONNEES MYSQL],
     *              "bdTableLogging" : [TABLE]
     *          }
     *      }
     * } <br>
     *
     * Ci-dessous un exemple de configuration d'un logger instance en mode 'file' : <br>
     *  {
     *      "nomLoggerInstance" : {
     *           "logLevel" : [NIVEAU PRIORITE],
     *           "logRepo" : "",
     *           "logToDb : 0,
     *       }
     *  }
     *
     * @param array $parLoggerConf
     */
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
        //on parcourt la liste des loggerInstance dans le champ de type liste
        $ListeNomsLoggerInstances = array_keys($this->listLoggerInstances);
        foreach ($ListeNomsLoggerInstances as $nomLoggerInstance){
            $string .= "$nomLoggerInstance : {$this->listLoggerInstances["$nomLoggerInstance"]}<br>";
        }

        return $string;
    }

    /**
     * Retourne une instance logger stocké dans le champ _listLoggerInstances_, en fonction de son nom
     *
     * @param string $loggerInstanceName
     * @return mixed
     *
     * @version 1.0
     */
    function getLoggerInstance(string $loggerInstanceName): LoggerInstance
    {
        return $this->listLoggerInstances[$loggerInstanceName];
    }

    /**
     * Méthode magique qui retourne l'objet sérializé pour permettre son stockage dans la variable **$_SESSION**.
     *
     * @return string[] Liste des champs de l'objet
     *
     * @version 1.0
     */
    public function __sleep(){
        return array('listLoggerInstances');
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