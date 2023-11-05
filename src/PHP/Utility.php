<?php

namespace PHP;

require_once "Enum_fic_logs.php";

//vérifie si le champ est de taille valide
function verif_validite_champ(string $champ, array $taille_champ): bool
{
    return count($taille_champ) == 2 && $taille_champ[0] <= strlen($champ) && $taille_champ[1] >= strlen($champ);
}

//hash un string passé en paramètre selon l'algo PASSWORD_BCRYPT
function hash_password(string $string_to_hash): string
{
    return password_hash($string_to_hash, PASSWORD_BCRYPT);
}

//vérifie si le string est identique au string haché
function compare_passwords(string $string_to_verify, string $password_hash): bool
{
    return password_verify($string_to_verify, $password_hash);
}

//fonction qui enregistre des LOGS dans un répertoire
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

//fonction qui importe la config
function import_config(){
    //on recupere le contenu du fichier de config
    $file_content = file_get_contents("../Config/config.json");
    //echo $file_content;

    //on transforme la chaine de caractere en un objet json, qu'on returne
    return json_decode($file_content, true);
}

function getTodayDateWithMilliSeconds(): \DateTime
{
    date_default_timezone_set("Europe/Paris");
    $now = \DateTime::createFromFormat('U.u', microtime(true));
    return $now;
}

/**
 * @throws \Exception
 */
function getTodayDate(): \DateTime
{
    $currentDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));

    return $currentDate;
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function deleteSpecialCharacters(string $str_to_analyse, array $listSpecialCharacters): array|string
{
    //on enleve les caracteres spéciaux du string entrée en paramètre
    return str_replace($listSpecialCharacters, "", $str_to_analyse);
}