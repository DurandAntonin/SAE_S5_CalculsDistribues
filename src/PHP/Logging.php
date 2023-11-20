<?php

namespace PHP;

use DateTime;

include_once "Utility.php";

class Logging{
    private string $logId;

    private Enum_niveau_logger $logLevel;

    private string $userId;

    private DateTime $date;

    private string $ip;

    private string $description;

    public function __construct(string $parLogId, Enum_niveau_logger $parLogLevel, string $parUserId, DateTime $parDate, string $parIp, string $parDescription){
        $this->logId = $parLogId;
        $this->logLevel = $parLogLevel;
        $this->userId = $parUserId;
        $this->date = $parDate;
        $this->ip = $parIp;
        $this->description = $parDescription;
    }

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

    public function getLogId(): string
    {
        return $this->logId;
    }

    public function setLogId(string $logId): void
    {
        $this->logId = $logId;
    }

    public function getLogLevel(): Enum_niveau_logger
    {
        return $this->logLevel;
    }

    public function setLogLevel(Enum_niveau_logger $logLevel): void
    {
        $this->logLevel = $logLevel;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

}


?>