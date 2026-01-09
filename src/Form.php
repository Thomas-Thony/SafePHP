<?php
namespace SafePHP;
use SafePHP\CSRF;
class Form {
    public static function getForm(){
        if (!CSRF::verifyCSRF())
            return;

        
    }
}