<?php

namespace PHP;

include_once "Enum_role_user.php";

class User
{
    private string $userId;
    private string $userMail;
    private string $login;
    private string $lastName;
    private string $firstName;
    private Enum_role_user $role;

    public static function defaultUser(): static
    {
        return new static(guidv4(), "null","Visiteur","null", "null",Enum_role_user::VISITEUR);
    }

    function __construct(string $parUserId, string $parUserMail, string $parLogin, string $parLastName, string $parFirstName, Enum_role_user $parRole){
        $this->userId = $parUserId;
        $this -> userMail = $parUserMail;
        $this->login = $parLogin;
        $this -> lastName = $parLastName;
        $this -> firstName = $parFirstName;
        $this -> role = $parRole;
    }

    function __toString(): string
    {
        $string = "";
        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField) {

            //on n'affiche pas le champ de type mysqli comme il n'a pas de méthode toString
            if ($valueField instanceof \mysqli)
                continue;

            $value = "";
            //si le champ est un type énuméré, on affiche le nom de la valeur
            if ($valueField instanceof Enum_role_user)
                $value .= $valueField->name;
            else
                $value .= $valueField;

            $string .= "<br>$nameField : $value";
        }
        return $string . "<br>";
    }

    public function getId(): string
    {
       return $this->userId;
    }

    public function getUserMail(): string
    {
        return $this->userMail;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getRole(): \PHP\Enum_role_user
    {
        return $this->role;
    }

    public function setUserMail(string $newUserMail): void
    {
        $this->userMail = $newUserMail;
    }

    public function setLogin(string $newLogin): void
    {
        $this->login = $newLogin;
    }

    public function setLastName(string $newLastName): void
    {
        $this->lastName = $newLastName;
    }

    public function setFirstName(string $newFirstName): void
    {
        $this->firstName = $newFirstName;
    }

    public function str(): string
    {
        return "<br><br>Id : ". $this->userId . "Mail : " . $this->userMail . "<br>Login : " . $this->login . "<br>Last name : " . $this->lastName . "<br>First name : " . $this->firstName . "<br>Role : " . $this->role->str() . "<br>";
    }

    public function serialize(): string
    {
        $userSerialized = "{";

        //on parcourt les champs de l'objet
        foreach ($this as $nameField => $valueField){
            $value = $valueField;

            //on convertit le role du user en un string
            if (gettype($valueField) != "string")
                $value = $valueField->str();

            $userSerialized .= "\"$nameField\" : \"$value\",";
        }
        //on enlève la virgule en trop
        $userSerialized = rtrim($userSerialized, ",");

        return $userSerialized . "}";
    }

    public function __sleep(){
        return array('userId', 'userMail', 'login', 'lastName', 'firstName', 'role');
    }

    public function __wakeup(){

    }
}