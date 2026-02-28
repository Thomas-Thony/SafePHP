<?php

use PHPUnit\Framework\TestCase;
use SafePHP\Checksum;

class ChecksumTest extends TestCase {

    /**
     * @test
     */
    public function testExist(){
        Checksum::addToChecksum(__DIR__ . "/../config/.env.example", "testChecksumNumber" . bin2hex(random_bytes(2)));
        $this->assertEquals(false, Checksum::exist("testChecksumBis"));

        Checksum::addToChecksum(__DIR__ . "/../config/php.ini", "testChecksum" . bin2hex(random_bytes(2)));
        $this->assertEquals(false, Checksum::exist("testChecksum.json"));

        Checksum::addToChecksum(__DIR__ . "/../composer.lock", "helloWorld" . bin2hex(random_bytes(2)));
        $this->assertEquals(false, Checksum::exist("johndoe.json"));
    }

    /**
     * @test
     */
    public function testHasChanged(){
        $file = __DIR__ . "/../index.php";
        $fileName = "Testfezfzeds" . bin2hex(random_int(1, 4));
        Checksum::addToChecksum($file, $fileName);
        $this->assertEquals(false, Checksum::hasChanged($fileName));
    }
}