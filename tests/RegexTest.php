<?php

use PHPUnit\Framework\TestCase;
use SafePHP\Regex;

class RegexTest extends TestCase {

    /**
     * @test
     */
    public function verifyTest(){
        $validInt = 45615;
        $invalidInt = 44.45;

        $validString = "fdhuoifhdom";
        $invalidString = "<script>alert('XSS Test ! This regex does not work');</script>";

        $validMail = "adress@mail.com";
        $invalidMail = "ujoifzçà)fèçzyfkfdojzp@fdsf";

        $validDate = "01/01/1970";
        $invalidDate = "100/1220/1561";


        // Int section
        $this->assertEquals(false, Regex::verify($invalidInt, "integer"));
        $this->assertEquals(true, Regex::verify($validInt, "integer"));

        // String section
        $this->assertEquals(false, Regex::verify($invalidString, "string"));
        $this->assertEquals(true, Regex::verify($validString, "string"));

        // Mail section
        $this->assertEquals(false, Regex::verify($invalidMail, "mail"));
        $this->assertEquals(true, Regex::verify($validMail, "mail"));

        // Date section
        $this->assertEquals(false, Regex::verify($invalidDate, "date"));
        $this->assertEquals(true, Regex::verify($validDate, "date"));
    }
}