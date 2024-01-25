<?php

namespace PHP\TESTS;

use PHP\Enum_niveau_logger;
use PHP\Enum_role_user;
use PHP\Logging;
use PHP\MySQLDataManagement;
use PHP\User;
use PHPUnit\Framework\TestCase;
use function PHP\guidv4;
use function PHP\getTodayDate;
use function PHP\hash_password;

class MySQLDataManagementTest extends TestCase
{
    private MySQLDataManagement $mySqlConnector;

    private int $numberOfUsersInserted = 0;

    private int $numberOfLogsInserted = 0;

    private function insertUserBefore(): User
    {
        $num = $this->numberOfUsersInserted;
        $userMail = "test${$num}insert.user@mail.com";
        $userLogin = "test${$num}";
        $userToInsert = new User(guidv4(), $userMail, $userLogin, "nom${$num}", "prénom${$num}", Enum_role_user::USER, getTodayDate()->format("Y-m-d H:i:s"));
        $userPassword =  hash_password("azerty");

        //on insert un nouvel utilisateur dans la table
        $result = $this->mySqlConnector->insert_user("Users", $userToInsert, $userPassword);

        $this->assertEquals(0, $result["error"]);

        //on incrémente de 1 le nombre de users insérés dans la table
        $this->numberOfUsersInserted += 1;

        return $userToInsert;
    }

    private function insertLogBefore(string $description): Logging
    {
        $log = new Logging(guidv4(), Enum_niveau_logger::INFO, guidv4(), getTodayDate(), "127.0.0.1", $description);

        $resultInsertLog = $this->mySqlConnector->insert_log("Logging", $log);

        $this->assertEquals(0, $resultInsertLog["error"]);

        $this->numberOfLogsInserted ++;

        return $log;
    }

    public function setUpBeforeClass(): void
    {
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test";

        $this->mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);
    }

    public function tearDownAfterClass(): void
    {
        $this->mySqlConnector->close_connexion_to_db();
    }

    public function testVerif_solidite_password()
{
    //test password fragile
    $weakPassword = "azerty";
    $resultVerifSoliditePasswordFalse = $this->mySqlConnector->verif_solidite_password("Weak_passwords", $weakPassword);

    //test password non fragile
    $strongPassword = "aqzsedrftgyh";
    $resultVerifSoliditePasswordTrue = $this->mySqlConnector->verif_solidite_password("Weak_passwords", $strongPassword);

    $this->assertEquals(false, $resultVerifSoliditePasswordFalse["result"]);
    $this->assertEquals(true, $resultVerifSoliditePasswordTrue["result"]);
}

    public function testCheck_mail_login_taken()
{
    $userInserted =  $this->insertUserBefore();
    $loginTaken = $userInserted->getLogin();
    $mailTaken = $userInserted->getMail();

    $num = $this->numberOfUsersInserted + 1;
    $loginNotTaken = "test${num}";
    $mailNotTaken = "test${num}insert.user@mail.com";

    //cas login pris
    $resultCheckMailLoginTaken = $this->mySqlConnector->check_mail_login_taken("Users", $loginTaken, $mailTaken);

    //cas login non pris mais mail pris
    $resultCheckMailLoginNotTakenMailTaken = $this->mySqlConnector->check_mail_login_taken("Users", $loginNotTaken, $mailTaken);

    //cas login et mail non pris
    $resultCheckMailLoginMailNotTaken = $this->mySqlConnector->check_mail_login_taken("Users", $loginNotTaken, $mailNotTaken);

    $this->assertEquals(-1, count($resultCheckMailLoginTaken["result"]));
    $this->assertEquals(-2, count($resultCheckMailLoginNotTakenMailTaken["result"]));
    $this->assertEquals(1, count($resultCheckMailLoginMailNotTaken["result"]));
}

    public function testInsert_user()
    {
        $userInserted = $this->insertUserBefore();
    }

    public function testGet_users()
    {
        $result = $this->mySqlConnector->get_users("Users");
        $this->assertEquals($this->numberOfUsersInserted, count($result["result"]));

    }

    public function testGet_user_by_login()
{
    $userInserted =  $this->insertUserBefore();
    $resultGetUser = $this->mySqlConnector->get_user_by_login("Users", $userInserted->getLogin());

    $this->assertEquals(1, count($resultGetUser["result"]));
}

    public function testGet_user_by_mail()
{
    $userInserted =  $this->insertUserBefore();

    $resultGetUserByMail = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

    $this->assertEquals(1, count($resultGetUserByMail["result"]));
}

    public function testChange_user_mail()
    {
        $userInserted1 =  $this->insertUserBefore();
        $userInserted2 =  $this->insertUserBefore();

        //cas mail utilisé
        $mailTaken = $userInserted2->getMail();
        $resultChangeUserMailFalse = $this->mySqlConnector->change_user_mail("Users", $userInserted1->getId(), $mailTaken);
        $resultGetUser1 = $this->mySqlConnector->get_user_by_login("Users", $userInserted1);

        //cas mail non utilisé
        $mailNotTaken = "testnewuser.mail.com";
        $resultChangeUserMailTrue = $this->mySqlConnector->change_user_mail("Users", $userInserted1->getId(), $mailNotTaken);
        $resultGetUser2 = $this->mySqlConnector->get_user_by_mail("Users", $userInserted1);

        $this->assertEquals(false, $resultChangeUserMailFalse["result"]);
        $this->assertEquals(1, count($resultGetUser1["result"]));
        $this->assertEquals(true, $resultChangeUserMailTrue["result"]);
        $this->assertEquals(1, count($resultGetUser2["result"]));
    }

    public function testChange_user_login()
{
    $userInserted1 =  $this->insertUserBefore();
    $userInserted2 =  $this->insertUserBefore();

    //cas login utilisé
    $newLoginUsed = $userInserted2->getLogin();
    $resultChangeUserLoginFalse = $this->mySqlConnector->change_user_login("Users", $userInserted1->getId(), $newLoginUsed);
    $resultGetUser1 = $this->mySqlConnector->get_user_by_login("Users", $userInserted1->getLogin());

    //cas login non utilisé
    $newLoginUnused = "test_new_login";
    $resultChangeUserLoginTrue = $this->mySqlConnector->change_user_login("Users", $userInserted1->getId(), $newLoginUnused);
    $resultGetUser2 = $this->mySqlConnector->get_user_by_login("Users", $newLoginUnused);

    $this->assertEquals(false, $resultChangeUserLoginFalse["result"]);
    $this->assertEquals(1, count($resultGetUser1["result"]));
    $this->assertEquals(true, $resultChangeUserLoginTrue["result"]);
    $this->assertEquals(1, count($resultGetUser2["result"]));
}

    public function testChange_user_lastname()
{
    $userInserted =  $this->insertUserBefore();

    $newLastName = "test_changement_nom";

    $resultChangeUserLastName = $this->mySqlConnector->change_user_lastname("Users", $userInserted, $newLastName);
    $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

    $this->assertEquals(0, $resultChangeUserLastName["error"]);
    $this->assertEquals(0, $resultGetUser["result"]);
    $this->assertEquals($newLastName, $resultGetUser["result"][0]->getLastName());
}

    public function testChange_user_firstname()
{
    $userInserted =  $this->insertUserBefore();

    $newFirstName = "test_changement_prenom";

    $resultChangeUserFirstName = $this->mySqlConnector->change_user_firstname("Users", $userInserted, $newFirstName);
    $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

    $this->assertEquals(0, $resultChangeUserFirstName["error"]);
    $this->assertEquals($newFirstName, $resultGetUser["result"][0]->getFirstName());
}

    public function testChange_user_password()
{
    $userInserted =  $this->insertUserBefore();

    //cas password identique à l'ancien
    $samePassword = "azerty";
    $resultChangePasswordSame = $this->mySqlConnector->change_user_password("Users", "Weak_passwords",  $userInserted->getLogin(), $userInserted->getId(), $samePassword);
    $resultVerifPassword1 = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), $samePassword);

    //cas password trop fragile
    $weakPassword = "qwertyuiop";
    $resultChangePasswordWeak= $this->mySqlConnector->change_user_password("Users", "Weak_passwords",  $userInserted->getLogin(), $userInserted->getId(), $weakPassword);
    $resultVerifPassword2 = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), $samePassword);

    //cas password changé
    $newPassword = "aqzsedrftgyhuj";
    $resultChangePassword = $this->mySqlConnector->change_user_password("Users", "Weak_passwords",  $userInserted->getLogin(), $userInserted->getId(), $newPassword);
    $resultVerifPassword3 = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), $newPassword);


    $this->assertEquals(-1, $resultChangePasswordSame["result"]);
    $this->assertEquals(true, $resultVerifPassword1["result"]);
    $this->assertEquals(-2, $resultChangePasswordWeak["result"]);
    $this->assertEquals(true, $resultVerifPassword2["result"]);
    $this->assertEquals(1, $resultChangePassword["result"]);
    $this->assertEquals(true, $resultVerifPassword3["result"]);
}

    public function testVerif_password()
    {
        $userInserted =  $this->insertUserBefore();

        $resultVerifPasswordTrue = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), "azerty");
        $resultVerifPasswordFalse = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), "wrong_password");

        $this->assertEquals(true, $resultVerifPasswordTrue["result"]);
        $this->assertEquals(false, $resultVerifPasswordFalse["result"]);

    }

    public function testSupprimer_user()
    {
        $userInserted =  $this->insertUserBefore();

        $resultSupprimerUser = $this->mySqlConnector->supprimer_user("Users", $userInserted->getId());
        $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

        $this->assertEquals(0, $resultSupprimerUser["error"]);
        $this->assertEquals(0, count($resultGetUser["result"]));
    }

    public function testGet_users_with_attribute()
    {
        $userInserted =  $this->insertUserBefore();

        //cas recherche user by id
        $id = $userInserted->getId();
        $resultGetUserById = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $id);

        //cas recherche users by mail
        $mail = "insert.user@mail.com";
        $resultGetUsersByMail = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $mail);

        //cas recherche users by login
        $login = "test";
        $resultGetUsersByLogin = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $login);

        //cas recherche users by lastname
        $lastname = "nom";
        $resultGetUsersByLastName = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $lastname);

        //cas recherche users by firstname
        $fistname = "prénom";
        $resultGetUsersByFirstName = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $fistname);

        //cas recherche users by role
        $role = Enum_role_user::USER->name;
        $resultGetUsersByRole = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $role);

        //cas recherche users by registrationDate
        $registrationDate = getTodayDate()->format("Y-m-d");
        $resultGetUsersByRegistrationDate = $this->mySqlConnector->get_users_with_attribute("Users", "userId", $registrationDate);

        $this->assertEquals(1, count($resultGetUserById["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByMail["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByLogin["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByLastName["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByFirstName["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByRole["result"]));
        $this->assertEquals($this->numberOfUsersInserted, count($resultGetUsersByRegistrationDate["result"]));
    }

    public function testInsert_log()
    {
        $logInserted = $this->insertLogBefore("Test insertion log");
    }

    public function testGet_logs()
    {
        $resultGetLogs = $this->mySqlConnector->get_logs("Logging");

        $this->assertEquals($this->numberOfLogsInserted, count($resultGetLogs["result"]));
    }

    public function testGet_logs_with_attribute()
    {
        $logInserted =  $this->insertLogBefore("Insertion log - testGet_logs_with_attribute");
        $userInserted = $this->insertUserBefore();

        //cas recherche log by id
        $id = $logInserted->getLogId();
        $resultGetLogById = $this->mySqlConnector->get_logs_with_attribute("Logging", "logId", $id);

        //cas recherche logs by log level
        $logLevel = Enum_niveau_logger::INFO->name;
        $resultGetLogByLogLevel = $this->mySqlConnector->get_logs_with_attribute("Logging", "logLevel", $logLevel);

        //cas recherche logs by user id
        $userId = $userInserted->getId();
        $resultGetLogByUserId = $this->mySqlConnector->get_logs_with_attribute("Logging", "userId", $userId);

        //cas recherche logs by date
        $date = getTodayDate()->format("Y-m-d");;
        $resultGetLogDate = $this->mySqlConnector->get_logs_with_attribute("Logging", "date", $date);

        //cas recherche logs by ip
        $ip = "127.0.01";
        $resultGetLogByIp = $this->mySqlConnector->get_logs_with_attribute("Logging", "ip", $ip);

        //cas recherche logs by description
        $description = "testGet_logs_with_attribute";
        $resultGetLogByDescription = $this->mySqlConnector->get_logs_with_attribute("Logging", "description", $description);


        $this->assertEquals(1, count($resultGetLogById["result"]));
        $this->assertEquals($this->numberOfLogsInserted, count($resultGetLogByLogLevel["result"]));
        $this->assertEquals(1, count($resultGetLogByUserId["result"]));
        $this->assertEquals($this->numberOfLogsInserted, count($resultGetLogDate["result"]));
        $this->assertEquals($this->numberOfLogsInserted, count($resultGetLogByIp["result"]));
        $this->assertEquals(1, count($resultGetLogByDescription["result"]));

    }

    public function testGet_nb_module_uses_with_dates()
    {
        //insertion de deux logs pour l'utilisation du module 1
        $logInserted1 = $this->insertLogBefore("Utilisation module1");
        $logInserted2 = $this->insertLogBefore("Utilisation module2");

        $startDate = getTodayDate()->format("Y-m-d");
        $endDate = getTodayDate()->format("Y-m-d");
        $resultGetNbModuleUsesWithDate = $this->mySqlConnector->get_nb_module_uses_with_dates("Logging", $startDate, $endDate);

        $this->assertEquals(2, count($resultGetNbModuleUsesWithDate["result"]));
    }

    public function testGet_nb_visits_with_dates()
    {
        //insertion de deux logs pour la connexion d'un utilisateur
        $userInserted = $this->insertUserBefore();
        $logInserted1 = $this->insertLogBefore("Connexion utilisateur USER");
        $logInserted2 = $this->insertLogBefore("Connexion utilisateur USER");

        $startDate = getTodayDate()->format("Y-m-d");
        $endDate = getTodayDate()->format("Y-m-d");
        $resultGetNbModuleUsesWithDate = $this->mySqlConnector->get_nb_visits_with_dates("Logging", $startDate, $endDate);

        $this->assertEquals(2, count($resultGetNbModuleUsesWithDate["result"]));
    }

    public function testGet_nb_users_with_registration_dates()
    {
        $startDate = getTodayDate()->format("Y-m-d");
        $endDate = getTodayDate()->format("Y-m-d");
        $result = $this->mySqlConnector->get_nb_visits_with_dates("Users", $startDate, $endDate);

        $this->assertEquals($this->numberOfUsersInserted, count($result["result"]));
    }
}
