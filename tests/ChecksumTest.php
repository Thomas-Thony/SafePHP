<?php

use PHPUnit\Framework\TestCase;
use SafePHP\Checksum;

class ChecksumTest extends TestCase {

    /**
     * @test
     */
    public function testExist(){
        
        Checksum::addToChecksum(__DIR__ . "/../config/.env.example", "testChecksumBis");
        $this->assertEquals(true, Checksum::exist("testChecksumBis.json"));
        
        Checksum::addToChecksum(__DIR__ . "/../config/php.ini", "testChecksum");
        $this->assertEquals(true, Checksum::exist("testChecksum.json"));

        Checksum::addToChecksum(__DIR__ . "/../composer.lock", "helloWorld123");
        $this->assertEquals(false, Checksum::exist("johndoe.json"));
    }

    /**
     * @test
     */
    public function testHasChanged(){
        $file = __DIR__ . "/../index.php";
        Checksum::addToChecksum($file, "index");
        $this->assertEquals(false, Checksum::hasChanged("index"));
    }
}