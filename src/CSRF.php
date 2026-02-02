<?php
namespace SafePHP;

/**
 * Manage CSRF protection
 */
class CSRF {

    /** Create a CSRF token
     * @return void the CSRF token created on HTML input
     */
    public static function createCSRF(){
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $CSRF_TOKEN = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $CSRF_TOKEN;
        echo(sprintf("<input type='hidden' name='csrf_token' value='%s'>", htmlspecialchars($CSRF_TOKEN, ENT_QUOTES, 'UTF-8')));
        return;
    }

    /** Verify if there is a CSRF token and if it's correct
     * @return void error or a message that said the token is correct
     */
    public static function verifyCSRF(){
        $CSRF_INPUT = $_POST["csrf_token"];
        if(!isset($CSRF_INPUT) || $CSRF_INPUT == null || !(hash_equals($CSRF_INPUT, $_SESSION["csrf_token"]))){
            die("Invalid CSRF Token !");
        } else {
            echo "CSRF valide !!";
        }
    }
}