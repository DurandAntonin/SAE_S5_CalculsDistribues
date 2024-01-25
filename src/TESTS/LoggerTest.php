<?php

namespace PHP\TEST;

use PHP\Logger;
use PHP\LoggerInstance;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LoggerTest extends TestCase
{
    private string $pathToLoggerConfig = "LoggerTests/loggerConf.json";

    private string $loggerConf;

    public function setUp(): void
    {
        //on load la config des logger contnenue dans un fichier json
        $file_content = file_get_contents($this->pathToLoggerConfig);
        $this->loggerConf = json_decode($file_content, true);

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
        });
    }

    public function tearDown(): void
    {
        restore_error_handler();
    }

    public function test__construct()
    {
        try{
            $logger = new Logger($this->loggerConf);
            $this->assertTrue(true);
        }
        catch (\Exception $exception) {
            $this->fail($exception);
        }
    }

    public function testGetLoggerInstance()
    {
        $logger = new Logger($this->loggerConf);

        //2 loggers doivent être récupérés
        $this->assertInstanceOf(LoggerInstance::class, $logger->getLoggerInstance("loggerFile"));
        $this->assertInstanceOf(LoggerInstance::class, $logger->getLoggerInstance("loggerDb"));
    }
}
