<?php

namespace PHP;

include_once "Enum_role_user.php";

/**
 * Stocke les informations d'un utilisateur de l'application, sauf son mot de passe
 *
 * @version 1.0
 */
class User
{
    /**
     * @var string $userId Identifiant de l'utilisateur
     */
    private string $userId;

    /**
     * @var string $userMail Adresse mail de l'utilisateur
     */
    private string $userMail;

    /**
     * @var string $login Login de l'utilisateur
     */
    private string $login;

    /**
     * @var string $lastName Nom de l'utilisateur
     */
    private string $lastName;

    /**
     * @var string $firstName Prénom de l'utilisateur
     */
    private string $firstName;

    /**
     * @var Enum_role_user $role Rôle de l'utilisateur dans l'application. Définit ses droits et les pages qui lui sont accessibles.<br>
     * Ex : USER, ce rôle est accordé aux utilisateurs qui ont un compte.
     */
    private Enum_role_user $role;

    /**
     * @var string Date d'inscription de l'utilisateur
     */
    private string $registrationDate;

    /**
     * Permet de construire une instance de cette classe avec des champs par défaut
     *
     * @return static L'instance de cette classe
     * @throws \Exception
     *
     * @version 1.0
     */
    public static function defaultUser(): static
    {
        return new static(guidv4(), "null","Visiteur","null", "null",Enum_role_user::VISITEUR, "null");
    }

    /**
     * Constructeur de la classe.
     *
     * @param string $parUserId Identifiant de l'utilisateur
     * @param string $parUserMail Adresse mail de l'utilisateur
     * @param string $parLogin Login de l'utilisateur
     * @param string $parLastName Nom de l'utilisateur
     * @param string $parFirstName Prénom de l'utilisateur
     * @param Enum_role_user $parRole Rôle de l'utilisateur
     *
     * @version 1.0
     */
    function __construct(string $parUserId, string $parUserMail, string $parLogin, string $parLastName, string $parFirstName, Enum_role_user $parRole, string $parRegistrationDate){
        $this->userId = $parUserId;
        $this -> userMail = $parUserMail;
        $this->login = $parLogin;
        $this -> lastName = $parLastName;
        $this -> firstName = $parFirstName;
        $this -> role = $parRole;
        $this->registrationDate = $parRegistrationDate;
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

    /**
     * Getter du champ _userId_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getId(): string
    {
       return $this->userId;
    }

    /**
     * Getter du champ _userMail_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getMail(): string
    {
        return $this->userMail;
    }

    /**
     * Getter du champ _lastName_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Getter du champ _login_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Getter du champ _firstName_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Getter du champ _role_
     *
     * @return Enum_role_user
     *
     * @version 1.0
     */
    public function getRole(): \PHP\Enum_role_user
    {
        return $this->role;
    }

    /**
     * Getter du champ _registrationDate_
     *
     * @return string
     *
     * @version 1.0
     */
    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }

    /**
     * Setter du champ _userMail_
     *
     * @param string $newUserMail
     *
     * @return void
     *
     * @version 1.0
     */
    public function setMail(string $newUserMail): void
    {
        $this->userMail = $newUserMail;
    }

    /**
     * Setter du champ _login_
     *
     * @param string $newLogin
     *
     * @return void
     *
     * @version 1.0
     */
    public function setLogin(string $newLogin): void
    {
        $this->login = $newLogin;
    }

    /**
     * Setter du champ _lastName_
     *
     * @param string $newLastName
     *
     * @return void
     *
     * @version 1.0
     */
    public function setLastName(string $newLastName): void
    {
        $this->lastName = $newLastName;
    }

    /**
     * Setter du champ _firstName_
     *
     * @param string $newFirstName
     *
     * @return void
     *
     * @version 1.0
     */
    public function setFirstName(string $newFirstName): void
    {
        $this->firstName = $newFirstName;
    }

    /**
     * Sérialise l'objet utilisateur, en stockant ses champs et valeur dans une chaîne de caractères.
     *
     * @return string Utilisateur sérialisé
     *
     * @version 1.0
     */
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

    /**
     * Renvoi la liste des noms des champs de la classe
     *
     * @return array Liste des noms des champs
     */
    public function getListFieldNames() : array
    {
        $listFieldNames = array();
        foreach ($this as $field){
            $listFieldNames[] = $field;
        }

        return $listFieldNames;
    }

    /**
     * Méthode magique qui retourne l'objet sérializé pour permettre son stockage dans la variable **$_SESSION**.
     *
     * @return string[] Liste des champs de l'objet
     *
     * @version 1.0
     */
    public function __sleep(){
        return array('userId', 'userMail', 'login', 'lastName', 'firstName', 'role', 'registrationDate');
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