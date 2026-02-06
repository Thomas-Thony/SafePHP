<?php

use PHPUnit\Framework\TestCase;
use SafePHP\CSRF;

class CSRFTest extends TestCase {

    /**
     * @test
     */
    public function testVerifyCSRF(){
        $csrfRandomToken = "fdf46fe486ds556af4s4x69s4sx";
        $_POST["csrf_token"] = $csrfRandomToken;
        $this->assertEquals(false, CSRF::verifyCSRF());

        $_POST["csrf_token"] = CSRF::createCSRF();
        $otherCSRF = $_POST["csrf_token"];
        $this->assertEquals(true, $otherCSRF);
    }
}