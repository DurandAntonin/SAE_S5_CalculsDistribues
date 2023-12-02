<?php

namespace PHP;

require_once "Enum_fic_logs.php";

/**
 * Vérifie si un champ d'un formulaire en HTML est valide, i.e si le nombre de caractères du champ est compris dans une borne.
 *
 * @param string $champ Chaîne de caractères d'un champ
 * @param array $taille_champ Liste contenant les bornes minimum et maximum utilisées pour valider un champ
 *
 * @return bool true si le champ est valide, false sinon
 *
 * @version 1.0
 */
function verif_validite_champ(string $champ, array $taille_champ): bool
{
    return count($taille_champ) == 2 && $taille_champ[0] <= strlen($champ) && $taille_champ[1] >= strlen($champ);
}


/**
 * Hash une chaîne de caractères selon l'algorithme CRYPT_BLOWFISH.
 * Cette fonction est utilisée pour hasher le mot de passe d'un utilisateur.
 *
 * @param string $string_to_hash Chaîne de caractères à hasher
 * @return string La chaîne de caractères hashée
 *
 * @version 1.0
 */
function hash_password(string $string_to_hash): string
{
    return password_hash($string_to_hash, PASSWORD_BCRYPT);
}

/**
 * Vérifie si une chaîne de caractère non hashée est identique ou non à une chaîne de caractères hashée.
 *
 *
 * @param string $string_to_verify La chaîne de caractères à comparer.
 * @param string $password_hash La chaîne de caractères hashée
 * @return bool
 *
 * @version 1.0
 */
function compare_passwords(string $string_to_verify, string $password_hash): bool
{
    return password_verify($string_to_verify, $password_hash);
}

/**
 * Enregistre un log dans un répertoire en fonction de son type, spécifiée par l'énumération _$repo_logs_
 *
 * @deprecated 1.0 N'est plus utilisée dans le code, sera enlevée dans la version suivante
 *
 * @param array $info_a_stocker
 * @param Enum_fic_logs $repo_logs
 * @param array $VARS
 * @return void
 *
 * @see Enum_fic_logs
 *
 * @version 1.0
 */
function enregistrement_actions_dans_logs(array $info_a_stocker, Enum_fic_logs $repo_logs, array $VARS): void
{
    $repo_a_utiliser = "";
    $entete_fic_logs = "";

    //on regarde quel repértoire utiliser à stocker pour le log
    switch ($repo_logs){
        case Enum_fic_logs::REPO_LOGS_ERREURS:
            $repo_a_utiliser = "../LOGS/logs_programme/";
            $entete_fic_logs = $VARS["entete_fic_logs_erreurs"];
            break;

        case Enum_fic_logs::REPO_LOGS_USERS_ACTIONS :
            $repo_a_utiliser = "../LOGS/logs_users_actions/";
            $entete_fic_logs = $VARS["entete_fic_logs_users_actions"];
            break;

        case  Enum_fic_logs::REPO_LOGS_TENTATIVES_CONNEXIONS_USERS :
            $repo_a_utiliser = "../LOGS/logs_tentatives_connexions_users/";
            $entete_fic_logs = $VARS["entete_fic_logs_tentatives_connexions_users"];
            break;
    }

    $date_log = date("Y") . date("m");
    $nom_fichier_log = $repo_a_utiliser . $VARS["prefixe_fic_log"] . $date_log . ".csv";

    /**print_r($info_a_stocker);
    echo "<br>";
    print_r($repo_logs);
    echo "<br>";
    echo $date_log;
    echo "<br>";
    echo $nom_fichier_log;
    echo "<br>";*/

    //on a un fichier de log pour chaque mois, on regarde s'il existe, sinon on le créé avec une entete
    if (!file_exists($nom_fichier_log)){
        $curseur = fopen($nom_fichier_log,"w");
        fputcsv($curseur, $entete_fic_logs, ";");
        fputcsv($curseur, $info_a_stocker, ";");
        fclose($curseur);
    }
    else{
        //on insère les info à stocker à la fin du fichier
        $curseur = fopen($nom_fichier_log, "a");
        fputcsv($curseur, $info_a_stocker, ";");
        fclose($curseur);
    }
}


/**
 * Importe la configuration de l'application stockée dans un fichier Json, puis la convertie en une liste associative.
 *
 * @return mixed
 *
 * @version 1.0
 */
function import_config(){
    //on recupere le contenu du fichier de config
    $file_content = file_get_contents("../Config/config.json");
    //echo $file_content;

    //on transforme la chaine de caractere en un objet json, qu'on returne
    return json_decode($file_content, true);
}

/**
 * Retourne la date d'aujourd'hui selon l'horaire UTC+1 (Europe/Paris), avec des microsecondes
 *
 * @deprecated 1.0 N'est plus utilisée dans le code, sera enlevée dans la version suivante
 *
 * @return \DateTime
 *
 * @version 1.0
 */
function getTodayDateWithMilliSeconds(): \DateTime
{
    date_default_timezone_set("Europe/Paris");
    $now = \DateTime::createFromFormat('U.u', microtime(true));
    return $now;
}


/**
 * Retourne la date d'aujourd'hui selon l'horaire UTC+1 (Europe/Paris)
 *
 * @return \DateTime Date
 *
 * @version 1.0
 */
function getTodayDate(): \DateTime
{
    $currentDate = "";
    try {
        $currentDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
    } catch (\Exception $e) {
    }

    return $currentDate;
}

/**
 * Génère un UUID compatible avec la RFC 4122.
 *
 * @param $data
 *
 * @return string L'UUID généré sous forme d'une chaîne de caractères
 *
 * @throws \Exception
 *
 * @version 1.0
 */
function guidv4($data = null): string
{
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Enlève une liste de caractères spéciaux dans une chaîne de caractères. <br>
 * Ex : te't ==> tet, si le ' est indiqué comme caractère spécial à enlever
 *
 * @param string $strToAnalyse Chaîne de caractères à analyser
 * @param array $listSpecialCharacters Liste des caractères spéciaux à enlever dans la chaîne de caractères
 *
 * @return string La chaîne de caractères sans les caractères spéciaux
 *
 * @version 1
 */
function deleteSpecialCharacters(string $strToAnalyse, array $listSpecialCharacters): string
{
    //on enleve les caracteres spéciaux du string entrée en paramètre
    return str_replace($listSpecialCharacters, "", $strToAnalyse);
}