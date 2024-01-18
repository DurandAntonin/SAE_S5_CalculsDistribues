<?php

namespace PHP;

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
 * Importe la configuration de l'application stockée dans un fichier Json, puis la convertie en une liste associative.
 *
 * @return mixed
 *
 * @version 1.0
 */
function import_config(): mixed
{
    //on recupere le contenu du fichier de config
    $file_content = file_get_contents("../Config/config.json");
    //echo $file_content;

    //on transforme la chaine de caractere en un objet json, qu'on returne
    return json_decode($file_content, true);
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
 * Supprime un fichier du host depuis un conteneur docker
 *
 * @param $pathToFile string Chemin vers le fichier
 * @param $fileName string Fichier à supprimer
 * @return array Liste contenant l'indice d'erreur (1) ou non, avec le message d'erreur associé s'il y en a
 *
 * @version 1.0
 */
function deleteFileOnHostFromContainer(string $pathToFile, string $fileName): array
{
    $file = $pathToFile . $fileName;
    $commandDeleteFile = "rm {$file}";

    $output = null;
    $resultCommand = null;

    try{
        exec($commandDeleteFile, $output, $resultCommand);
    }
    catch (\ValueError|\Exception $e){
        return [0, $e];
    }

    return [1, ""];
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