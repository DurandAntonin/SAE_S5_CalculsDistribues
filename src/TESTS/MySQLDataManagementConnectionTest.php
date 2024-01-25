<?php

namespace PHP\TESTS;

use PHP\MySQLDataManagement;
use PHPUnit\Framework\TestCase;

class MySQLDataManagementConnectionTest extends TestCase
{
    public function test__construct()
    {
        //test de la connexion à une BD MySQL qui réussie
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test";

        $mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);

        $this->assertEquals(0, $mySqlConnector->getConnectionErreur());

        $mySqlConnector->close_connexion_to_db();
    }


    public function testGetConnectionErreur()
    {
        //connexion à une BD MySQL qui échoue
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test_connexion_echoue";

        $mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);

        $this->assertEquals(1, $mySqlConnector->getConnectionErreur());

        $mySqlConnector->close_connexion_to_db();
    }

    public function testGetConnectionErreurMessage()
    {
        //connexion à une BD MySQL qui échoue
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test_connexion_echoue";

        $mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);

        $this->assertEquals(1, $mySqlConnector->getConnectionErreur());

        $mySqlConnector->close_connexion_to_db();
    }

    public function testClose_connexion_to_db()
    {
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test";

        $mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);

        $resultCloseConnexionToBD = $mySqlConnector->close_connexion_to_db();

        $this->assertEquals(true, $resultCloseConnexionToBD);

    }

    public function testReconnect_to_bd()
    {
        $hostname = "172.19.181.254";
        $username = "BlitzCalc_DB_user";
        $password = "azerty";
        $database = "BlitzCalc_DB_test";

        $mySqlConnector = new MySQLDataManagement($hostname, $username, $password, $database);
        $mySqlConnector->close_connexion_to_db();

        $this->assertEquals(0, $mySqlConnector->getConnectionErreur());

        //on essaie de se reconnecter
        $mySqlConnector->reconnect_to_bd();
        $this->assertEquals(0, $mySqlConnector->getConnectionErreur());
    }
}
