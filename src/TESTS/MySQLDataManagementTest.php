<?php

namespace PHP\TESTS;

use PHP\Enum_role_user;
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

    private function insertUserBefore(): User
    {
        $num = $this->numberOfUsersInserted;
        $userMail = "test${num}insert.user@mail.com";
        $userLogin = "test${num}";
        $userToInsert = new User(guidv4(), $userMail, $userLogin, "nom", "prénom", Enum_role_user::USER, getTodayDate()->format("Y-m-d H:i:s"));
        $userPassword =  hash_password("azerty");

        //on insert un nouvel utilisateur dans la table
        $result = $this->mySqlConnector->insert_user("Users", $userToInsert, $userPassword);

        $this->assertSame(0, $result["error"]);

        //on incrémente de 1 le nombre de users insérés dans la table
        $this->numberOfUsersInserted += 1;

        return $userToInsert;
    }

    public function setUpBeforeClass(): void
    {
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB";

        $this->mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);
    }

    public function tearDownAfterClass(): void
    {
        $this->mySqlConnector->close_connexion_to_db();
    }

    public function testInsert_user()
    {
        $userToInsert = new User(guidv4(), "test1insert.user@mail.com", "test1", "nom", "prénom", Enum_role_user::USER, getTodayDate()->format("Y-m-d H:i:s"));
        $userPassword =  hash_password("azerty");
        $result1 = $this->mySqlConnector->insert_user("Users", $userToInsert, $userPassword);

        $this->assertSame(0, $result1["error"]);

        $result2 = $this->mySqlConnector->get_user_by_login("Users", $userToInsert->getLogin());
        $this->assertSame(1, count($result2["result"]));
    }

    public function testGet_users()
    {
        $result = $this->mySqlConnector->get_users("Users");
        $this->assertSame($this->numberOfUsersInserted, count($result["result"]));

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

        $this->assertSame(false, $resultChangeUserMailFalse["result"]);
        $this->assertSame(1, count($resultGetUser1["result"]));
        $this->assertSame(true, $resultChangeUserMailTrue["result"]);
        $this->assertSame(1, count($resultGetUser2["result"]));
    }

    public function testVerif_solidite_password()
    {
        //test password fragile
        $weakPassword = "azerty";
        $resultVerifSoliditePasswordFalse = $this->mySqlConnector->verif_solidite_password("Weak_passwords", $weakPassword);

        //test password non fragile
        $strongPassword = "aqzsedrftgyh";
        $resultVerifSoliditePasswordTrue = $this->mySqlConnector->verif_solidite_password("Weak_passwords", $strongPassword);

        $this->assertSame(false, $resultVerifSoliditePasswordFalse["result"]);
        $this->assertSame(true, $resultVerifSoliditePasswordTrue["result"]);
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


        $this->assertSame(-1, $resultChangePasswordSame["result"]);
        $this->assertSame(true, $resultVerifPassword1["result"]);
        $this->assertSame(-2, $resultChangePasswordWeak["result"]);
        $this->assertSame(true, $resultVerifPassword2["result"]);
        $this->assertSame(1, $resultChangePassword["result"]);
        $this->assertSame(true, $resultVerifPassword3["result"]);
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

        $this->assertSame(false, $resultChangeUserLoginFalse["result"]);
        $this->assertSame(1, count($resultGetUser1["result"]));
        $this->assertSame(true, $resultChangeUserLoginTrue["result"]);
        $this->assertSame(1, count($resultGetUser2["result"]));
    }

    public function testVerif_password()
    {
        $userInserted =  $this->insertUserBefore();

        $resultVerifPasswordTrue = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), "azerty");
        $resultVerifPasswordFalse = $this->mySqlConnector->verif_password("Users", $userInserted->getLogin(), "wrong_password");

        $this->assertSame(true, $resultVerifPasswordTrue["result"]);
        $this->assertSame(false, $resultVerifPasswordFalse["result"]);

    }

    public function testSupprimer_user()
    {
        $userInserted =  $this->insertUserBefore();

        $resultSupprimerUser = $this->mySqlConnector->supprimer_user("Users", $userInserted->getId());
        $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

        $this->assertSame(0, $resultSupprimerUser["error"]);
        $this->assertSame(0, count($resultGetUser["result"]));
    }

    public function testChange_user_firstname()
    {
        $userInserted =  $this->insertUserBefore();

        $newFirstName = "test_changement_prenom";

        $resultChangeUserFirstName = $this->mySqlConnector->change_user_firstname("Users", $userInserted, $newFirstName);
        $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

        $this->assertSame(0, $resultChangeUserFirstName["error"]);
        $this->assertSame($newFirstName, $resultGetUser["result"][0]->getFirstName());
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
        $resultCheckMailLoginNotTakenMailTaken = $this->mySqlConnector->check_mail_login_taken("Users", $loginNotTaken, $mailTaken);

    }

    public function testGet_users_with_attribute()
    {

    }

    public function testInsert_log()
    {

    }

    public function testGet_logs_with_attribute()
    {

    }

    public function testGet_logs()
    {

    }

    public function testGet_nb_module_uses_with_dates()
    {

    }

    public function testGet_user_by_login()
    {
        $userInserted =  $this->insertUserBefore();
        $resultGetUser = $this->mySqlConnector->get_user_by_login("Users", $userInserted->getLogin());

        $this->assertSame(1, count($resultGetUser["result"]));
    }

    public function testGet_nb_visits_with_dates()
    {

    }

    public function testGet_user_by_mail()
    {
        $userInserted =  $this->insertUserBefore();

        $resultGetUserByMail = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

        $this->assertSame(1, count($resultGetUserByMail["result"]));
    }

    public function testChange_user_lastname()
    {
        $userInserted =  $this->insertUserBefore();

        $newLastName = "test_changement_nom";

        $resultChangeUserLastName = $this->mySqlConnector->change_user_lastname("Users", $userInserted, $newLastName);
        $resultGetUser = $this->mySqlConnector->get_user_by_mail("Users", $userInserted->getMail());

        $this->assertSame(0, $resultChangeUserLastName["error"]);
        $this->assertSame(0, $resultGetUser["result"]);
        $this->assertSame($newLastName, $resultGetUser["result"][0]->getLastName());
    }

    public function testGet_nb_users_with_registration_dates()
    {
        $startDate = getTodayDate()->format("Y-m-d");
        $endDate = getTodayDate()->format("Y-m-d");
        $result = $this->mySqlConnector->get_nb_visits_with_dates("Users", $startDate, $endDate);

        $this->assertSame($this->numberOfUsersInserted, count($result["result"]));
    }
}
