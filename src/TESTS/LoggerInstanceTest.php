<?php

namespace PHP\TESTS;

use PHP\Enum_niveau_logger;
use PHP\LoggerInstance;
use PHP\MySQLDataManagement;
use PHPUnit\Framework\TestCase;
use function PHP\getTodayDate;
use function PHP\guidv4;

class LoggerInstanceTest extends TestCase
{
    private LoggerInstance $loggerInstanceFileBd;

    private string $pathToLogs = "LoggerTests/LOGS/";

    private MySQLDataManagement $mySQLConnector;

    private function setUp(): void
    {
        //on se connecte au serveur MySQL
        //test de la connexion à une BD MySQL qui réussie
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test";

        $this->mySQLConnector = new MySQLDataManagement($hostname, $username, $password, $database);
        $mySqlConnectorForLogger = new MySQLDataManagement($hostname, $username, $password, $database);
        $this->assertEquals(0, $this->mySQLConnector->getConnectionErreur());
        $this->assertEquals(0, $mySqlConnectorForLogger->getConnectionErreur());

        //on crée un objet de la classe LoggerInstance
        $this->loggerInstanceFileBd = new LoggerInstance("loggerFileBd", Enum_niveau_logger::DEBUG, $this->pathToLogs, $mySqlConnectorForLogger, "Logging");
    }

    public function testDebug()
    {
        //on stocke l'id d'un utilisateur pour pouvoir ensuite tester si le log dans la bd avec cet userId est présent
        $userId = guidv4();

        //test écriture d'un log dans un fichier, et dans la bd
        $this->loggerInstanceFileBd->debug($this->userId, getTodayDate(), "127.0.0.1", "Test log DEBUG");
        $pathToLogFile = $this->pathToLogs . "DEBUG/" . getTodayDate()->format("Ym");

        //on exécute une requete sql pour récupérer le log inséré
        $resultGetLogs = $this->mySQLConnector->get_logs_with_attribute("Logging", "userId", $userId);

        $this->assertFileIsReadable($pathToLogFile);
        $this->assertEquals(1, count($resultGetLogs["result"]));
    }

    public function testInfo()
    {
        //on stocke l'id d'un utilisateur pour pouvoir ensuite tester si le log dans la bd avec cet userId est présent
        $userId = guidv4();

        //test écriture d'un log dans un fichier, et dans la bd
        $this->loggerInstanceFileBd->info($this->userId, getTodayDate(), "127.0.0.1", "Test log INFO");
        $pathToLogFile = $this->pathToLogs . "INFO/" . getTodayDate()->format("Ym");

        //on exécute une requete sql pour récupérer le log inséré
        $resultGetLogs = $this->mySQLConnector->get_logs_with_attribute("Logging", "userId", $userId);

        $this->assertFileIsReadable($pathToLogFile);
        $this->assertEquals(1, count($resultGetLogs["result"]));
    }

    public function testCritical()
    {
        //on stocke l'id d'un utilisateur pour pouvoir ensuite tester si le log dans la bd avec cet userId est présent
        $userId = guidv4();

        //test écriture d'un log dans un fichier, et dans la bd
        $this->loggerInstanceFileBd->critical($this->userId, getTodayDate(), "127.0.0.1", "Test log CRITICAL");
        $pathToLogFile = $this->pathToLogs . "CRITICAL/" . getTodayDate()->format("Ym");

        //on exécute une requete sql pour récupérer le log inséré
        $resultGetLogs = $this->mySQLConnector->get_logs_with_attribute("Logging", "userId", $userId);

        $this->assertFileIsReadable($pathToLogFile);
        $this->assertEquals(1, count($resultGetLogs["result"]));
    }

    public function testWarning()
    {
        //on stocke l'id d'un utilisateur pour pouvoir ensuite tester si le log dans la bd avec cet userId est présent
        $userId = guidv4();

        //test écriture d'un log dans un fichier, et dans la bd
        $this->loggerInstanceFileBd->warning($this->userId, getTodayDate(), "127.0.0.1", "Test log WARNING");
        $pathToLogFile = $this->pathToLogs . "WARNING/" . getTodayDate()->format("Ym");

        //on exécute une requete sql pour récupérer le log inséré
        $resultGetLogs = $this->mySQLConnector->get_logs_with_attribute("Logging", "userId", $userId);

        $this->assertFileIsReadable($pathToLogFile);
        $this->assertEquals(1, count($resultGetLogs["result"]));
    }

    public function testError()
    {
        //on stocke l'id d'un utilisateur pour pouvoir ensuite tester si le log dans la bd avec cet userId est présent
        $userId = guidv4();

        //test écriture d'un log dans un fichier, et dans la bd
        $this->loggerInstanceFileBd->error($this->userId, getTodayDate(), "127.0.0.1", "Test log ERROR");
        $pathToLogFile = $this->pathToLogs . "ERROR/" . getTodayDate()->format("Ym");

        //on exécute une requete sql pour récupérer le log inséré
        $resultGetLogs = $this->mySQLConnector->get_logs_with_attribute("Logging", "userId", $userId);

        $this->assertFileIsReadable($pathToLogFile);
        $this->assertEquals(1, count($resultGetLogs["result"]));
    }
}
