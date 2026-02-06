<?php

use PHPUnit\Framework\TestCase;
use SafePHP\AccessHandler;
use SafePHP\Session;
class AccessHandlerTest extends TestCase {
    
    /**
     * @test
     */
    public function testVerifyAccess(){
       $session = new Session("478944784fzsdfz7f4ez89f", 'Thomas', 2);
        $accessHandler = new AccessHandler("test");
        $this->assertEquals(true, $accessHandler->verifyAccess($_SESSION["user_id"]));
    }
}