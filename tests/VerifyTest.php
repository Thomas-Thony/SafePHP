<?php

use PHPUnit\Framework\TestCase;
use SafePHP\Verify;

class VerifyTest extends TestCase {
    /**
     * @test
     */
    public function testVerify(){
        $boolValue = false;
        $intValue = 500;
        $floatValue = 54564.23;
        $doubleValue = 189762.356515165;
        $stringValue = "Lorem ipsum";
        $arrayValue = ["huids", 4646, "kdopsqj", false, 5.26, null];
        $objectValue = (object) 'ciao';
        $nullValue = null;


        $this->assertEquals(true, true, Verify::verify($intValue, "bool"));
        $this->assertEquals(true, true, Verify::verify($boolValue, "bool"));
        $this->assertEquals(false, false, Verify::verify($boolValue, "float"));
        $this->assertEquals(false, false, Verify::verify($floatValue, "string"));
        $this->assertEquals(true, true, Verify::verify($doubleValue, "double"));
        $this->assertEquals(true, true, Verify::verify($objectValue, "object"));
        $this->assertEquals(false, false, Verify::verify($arrayValue, "int"));
        $this->assertEquals(true, true, Verify::verify($stringValue, "string"));
        $this->assertEquals(false, false, Verify::verify($nullValue, "string"));

    }

    /**
     * @test
     */
    public function testVerifyExtensionImage(){
        $File = "something.jpeg";
        $FileBis = "something.bin";
        $FileBisBis = "something.jpeg";

        $this->assertSame(1, Verify::verifyExtensionImage($File));
        $this->assertSame( 0, Verify::verifyExtensionImage($FileBis));
        $this->assertSame( 1, Verify::verifyExtensionImage($FileBisBis));
    }

    /**
     * @test
     */
    public function testVerifySignatureFile(){
        $FileTmpName = "hoirio.kgop";
        $FileName = "fiezh";
        $FileType = "video";

        $this->assertSame(false, Verify::verifySignatureFile($FileTmpName, $FileName, $FileType));
    }
}