<?php

namespace PHP;

use PHPUnit\Framework\TestCase;

class LoggingTest extends TestCase
{
    private function createLogBefore(): Logging
    {
        $log = new Logging(guidv4(), Enum_niveau_logger::INFO, guidv4(), getTodayDate(), "127.0.0.1", "Test log");
        return $log;
    }

    public function testSerialise()
    {
        $log = $this->createLogBefore();
        $logSerialised = $log->serialise();

        $this->assertJson($logSerialised);
    }

    public function testGetClassName()
    {
        $this->assertEquals("User", User::getClassName());
    }

    public function testGetListFieldNames()
    {
        $this->assertEquals(["logId", "logLevel", "userId", "date", "ip", "description"], Logging::defaultLogging()->getListFieldNames());
    }

    public function testGetLogId()
    {
        $logId = guidv4();
        $log = new Logging(guidv4(), Enum_niveau_logger::INFO, $logId, getTodayDate(), "127.0.0.1", "Test log");


        $this->assertEquals($logId, $log->getLogId());
    }

    public function testGetUserId()
    {
        $userId = guidv4();
        $log = new Logging(guidv4(), Enum_niveau_logger::INFO, $userId, getTodayDate(), "127.0.0.1", "Test log");

        $this->assertEquals($userId, $log->getUserId());
    }

    public function testGetDate()
    {
        $log = $this->createLogBefore();
        $this->assertEquals(getTodayDate(), $log->getDate());
    }

    public function testGetIp()
    {
        $log = $this->createLogBefore();
        $this->assertEquals("127.0.0.1", $log->getIp());
    }

    public function testGetDescription()
    {
        $log = $this->createLogBefore();
        $this->assertEquals("Test log", $log->getDescription());
    }

    public function testGetLogLevel()
    {
        $log = $this->createLogBefore();
        $this->assertEquals(Enum_niveau_logger::INFO, $log->getLogLevel());
    }

    public function testSetMail()
    {
        $log = $this->createLogBefore();
        $newMail = "testset.mail@gmail.com";
        $log->setMail($newMail);

        $this->assertEquals($newMail, $log->getMail());
    }

    public function testSetLogId()
    {
        $log = $this->createLogBefore();
        $newLogId = guidv4();
        $log->setLogId($newLogId);

        $this->assertEquals($newLogId, $log->getLogId());
    }

    public function testSetLogLevel()
    {
        $log = $this->createLogBefore();
        $newLogLevel = Enum_niveau_logger::ERROR;
        $log->setLogLevel($newLogLevel);

        $this->assertEquals($newLogLevel, $log->getLogLevel());
    }

    public function testSetUserId()
    {
        $log = $this->createLogBefore();
        $newUserId = guidv4();
        $log->setUserId($newUserId);

        $this->assertEquals($newUserId, $log->getUserId());
    }

    public function testSetDate()
    {
        $log = $this->createLogBefore();
        $newDate = getTodayDate();
        $log->setDate($newDate);

        $this->assertEquals($newDate, $log->getDate());
    }

    public function testSetIp()
    {
        $log = $this->createLogBefore();
        $newIp = "172.19.181.254";
        $log->setIp($newIp);

        $this->assertEquals($newIp, $log->getIp());
    }

    public function testSetDescription()
    {
        $log = $this->createLogBefore();
        $newDescription = "test nouvelle description";
        $log->setDescription($newDescription);

        $this->assertEquals($newDescription, $log->getDescription());
    }
}
