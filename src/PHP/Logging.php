<?php

namespace PHP;

use DateTime;

include_once "Utility.php";

/**
 * Contient les différentes informations d'un enregistrement log (journal), après une action d'un utilisateur par exemple.
 *
 * @version 1.0
 */
class Logging{
    /**
     * @var string $logId Identifiant du log
     */
    private string $logId;

    /**
     * @var Enum_niveau_logger $logLevel Niveau du log
     */
    private Enum_niveau_logger $logLevel;

    /**
     * @var string $userId Identifiant de l'utilisateur
     */
    private string $userId;

    /**
     * @var DateTime $date Date du log
     */
    private DateTime $date;

    /**
     * @var string $ip Adresse IP de l'utilisateur
     */
    private string $ip;

    /**
     * @var string $description Descriptif de l'événement
     */
    private string $description;

    /**
     * Constructeur de la classe
     *
     * @param string $parLogId Identifiant du log
     * @param Enum_niveau_logger $parLogLevel Niveau du log
     * @param string $parUserId Identifiant de l'utilisateur
     * @param DateTime $parDate Date du log
     * @param string $parIp Adresse IP de l'utilisateur
     * @param string $parDescription Descriptif de l'événement
     *
     * @return void
     *
     * @version 1.0
     */
    public function __construct(string $parLogId, Enum_niveau_logger $parLogLevel, string $parUserId, DateTime $parDate, string $parIp, string $parDescription){
        $this->logId = $parLogId;
        $this->logLevel = $parLogLevel;
        $this->userId = $parUserId;
        $this->date = $parDate;
        $this->ip = $parIp;
        $this->description = $parDescription;
    }

    /**
     * Permet de construire une instance de cette classe avec des champs par défaut
     *
     * @return static L'instance de cette classe
     * @throws \Exception
     *
     * @version 1.0
     */
    public static function defaultLogging(): static
    {
        return new static(guidv4(), Enum_niveau_logger::DEBUG,"null", getTodayDate(), "null","null");
    }

    /**
     * Méthode magique, retourne l'objet sous forme d'une chaîne de caractères lorsque ce dernier est affiché.
     *
     * @return string L'objet retourné sous forme d'une chaîne de caractères
     *
     * @version 1.0
     */
    public function __toString(): string
    {
        $stringObjet = "";
        foreach ($this as $fieldName=>$valueField){
            $value = "";

            //si le champ est un type énuméré, on affiche le nom de la valeur
            if ($valueField instanceof Enum_niveau_logger)
                $value .= $valueField->name;

            //si le champ est une date, on l'affiche sous format 'Y-m-d H:i:s'
            elseif ($valueField instanceof DateTime)
                $value .= $valueField->format("Y-m-d H:i:s");

            else
                $value .= $valueField;

            $stringObjet .= "<br>$fieldName : $value";
        }

        return $stringObjet;
    }

    /**
     * Getter du champ _logId_
     *
     * @return string
     *
     * @see Logging::$logId
     *
     * @version 1.0
     */
    public function getLogId(): string
    {
        return $this->logId;
    }

    /**
     * Setter du champ _logId_
     *
     * @param string $logId
     *
     * @return void
     *
     * @see Logging::$logId
     *
     * @version 1.0
     */
    public function setLogId(string $logId): void
    {
        $this->logId = $logId;
    }

    /**
     * Getter du champ _logLevel_
     *
     * @return Enum_niveau_logger
     *
     * @see Logging::$logLevel
     *
     * @version 1.0
     */
    public function getLogLevel(): Enum_niveau_logger
    {
        return $this->logLevel;
    }

    /**
     * Setter du champ _logLevel_
     *
     * @param Enum_niveau_logger $logLevel Nouveau niveau de log
     *
     * @return void
     *
     * @see Logging::$logLevel
     *
     * @version 1.0
     */
    public function setLogLevel(Enum_niveau_logger $logLevel): void
    {
        $this->logLevel = $logLevel;
    }

    /**
     * Getter du champ _userId_
     *
     * @return string
     *
     * @see Logging::$userId
     *
     * @version 1.0
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * Setter du champ _userId_
     *
     * @param string $userId Identifiant du nouvel utilisateur
     *
     * @return void
     *
     * @see Logging::$userId
     *
     * @version 1.0
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Getter du champ _date_
     *
     * @return DateTime
     *
     * @see Logging::$date
     *
     * @version 1.0
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Setter du champ _date_
     *
     * @param DateTime $date
     *
     * @return void
     *
     * @see Logging::$logId
     *
     * @version 1.0
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * Getter du champ _ip_
     *
     * @return string
     *
     * @see Logging::$logId
     *
     * @version 1.0
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Setter du champ _logId_
     *
     * @param string $ip
     *
     * @return void
     *
     * @see Logging::$logId
     *
     * @version 1.0
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * Getter du champ _description_
     *
     * @return string
     *
     * @see Logging::$description
     *
     * @version 1.0
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Setter du champ _description_
     *
     * @param string $description
     *
     * @return void
     *
     * @see Logging::$description
     *
     * @version 1.0
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Renvoi la liste des noms des champs de la classe
     *
     * @return array Liste des noms des champs
     */
    public function getListFieldNames() : array
    {
        $listFieldNames = array();
        foreach ($this as $field){
            //on ne prend pas le champ
            $listFieldNames[] = $field;
        }

        return $listFieldNames;
    }

    /**
     * Retourne le nom de classe sous forme de chaîne de caractères
     *
     * @return string Nom de la classe
     */
    public static function getClassName() : string
    {
        return get_class();
    }

}


?>