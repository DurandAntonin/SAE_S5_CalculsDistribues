<?php

namespace PHP\TESTS;

use PHP\Enum_role_user;
use PHP\User;
use PHPUnit\Framework\TestCase;
use function PHP\getTodayDate;
use function PHP\guidv4;

class UserTest extends TestCase
{

    private function createUserBefore(): User
    {
        $registrationDate = getTodayDate()->format("Y-m-d H:i:s");
        $user = new User(guidv4(), "user.test@gmail.com", "user test", "nom", "prénom", Enum_role_user::USER, $registrationDate);
        return $user;
    }

    public function testSerialise()
    {
        $user = $this->createUserBefore();
        $userSerialised = $user->serialise();

        $this->assertJson($userSerialised);
    }

    public function testGetClassName()
    {
        $this->assertEquals("User", User::getClassName());
    }

    public function testGetListFieldNames()
    {
        $this->assertEquals(["userId", "userMail", "login", "lastName", "firstName", "role", "registrationDate"], User::defaultUser()->getListFieldNames());
    }

    public function testGetId()
    {
        $registrationDate = getTodayDate()->format("Y-m-d");
        $userId = guidv4();
        $user = new User($userId, "user.test@gmail.com", "user test", "nom", "prénom", Enum_role_user::USER, $registrationDate);


        $this->assertEquals($userId, $user->getId());
    }

    public function testGetLastName()
    {
        $user = $this->createUserBefore();
        $this->assertEquals("nom", $user->getLastName());
    }

    public function testGetMail()
    {
        $user = $this->createUserBefore();
        $this->assertEquals("user.test@gmail.com", $user->getMail());
    }

    public function testGetRole()
    {
        $user = $this->createUserBefore();
        $this->assertEquals(Enum_role_user::USER, $user->getRole());
    }

    public function testGetLogin()
    {
        $user = $this->createUserBefore();
        $this->assertEquals("user test", $user->getLogin());
    }

    public function testGetFirstName()
    {
        $user = $this->createUserBefore();
        $this->assertEquals("prénom", $user->getFirstName());
    }

    public function testGetRegistrationDate()
    {
        $user = $this->createUserBefore();
        $this->assertEquals(getTodayDate()->format("Y-m-d"), $user->getRegistrationDate());
    }

    public function testSetMail()
    {
        $user = $this->createUserBefore();
        $newMail = "testset.mail@gmail.com";
        $user->setMail($newMail);

        $this->assertEquals($newMail, $user->getMail());
    }

    public function testSetLogin()
    {
        $user = $this->createUserBefore();
        $newMail = "testset.mail@gmail.com";
        $user->setMail($newMail);

        $this->assertEquals($newMail, $user->getMail());
    }

    public function testSetLastName()
    {
        $user = $this->createUserBefore();
        $newLastName = "testnouveaunom";
        $user->setMail($newLastName);

        $this->assertEquals($newLastName, $user->getLastName());
    }

    public function testSetFirstName()
    {
        $user = $this->createUserBefore();
        $newFirstName = "testnouveauprenom";
        $user->setMail($newFirstName);

        $this->assertEquals($newFirstName, $user->getFirstName());
    }
}
