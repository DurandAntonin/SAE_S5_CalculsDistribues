<?php

use PHP\Enum_role_user;

require_once "User.php";
require_once "Enum_role_user.php";

//on démarre la session
session_start();

//on vérifie si un objet user est dans la variable session
if (!empty($_SESSION) && isset($_SESSION["user"])){
    //on redirige l'utilisateur en fonction de son role
    $user = unserialize($_SESSION["user"]);
    //print_r($user);

    switch ($user->getRole()){
        case Enum_role_user::USER:
            //on ne fait rien
            //echo "Vous etes bien autoriser à etre sur cette page";
            break;
        case Enum_role_user::ADMIN:
            //on le redirige vers sa page d'acceuil
            //echo "Vous etes un admin, vous allez etre redirigé vers votre page d'accueil";
            header("Location:page_accueil_admin.php");
            break;
        case Enum_role_user::VISITEUR:
            //le visiteur n'a pas accès à cette page car elle est réservée au user inscrit
            //echo "Vous etes un visiteur, vous allez etre redirigé vers votre page d'accueil";
            header("Location:page_accueil_user.php");
            break;
        default :
            //role non enregistrée
            header("Location:../index.html");
    }
}
else{
    //la personne n'est pas connectée, on la renvoie à la page d'acceuil
    //echo "Personne non connectée";
    header("Location:../index.html");
}
?>