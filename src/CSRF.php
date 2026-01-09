<?php
namespace SafePHP;

class CSRF {
    public static function createCSRF(){
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $CSRF_TOKEN = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $CSRF_TOKEN;
        echo(sprintf("<input type='hidden' name='csrf_token' value='%s'>", htmlspecialchars($CSRF_TOKEN, ENT_QUOTES, 'UTF-8')));
        return;
    }

    public static function verifyCSRF(){
        $CSRF_INPUT = $_POST["csrf_token"];
        if(!isset($CSRF_INPUT) || $CSRF_INPUT == null || $CSRF_INPUT !== $_SESSION["csrf_token"]){
            die("Invalid CSRF Token !");
        }
    }
}