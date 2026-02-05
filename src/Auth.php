<?php
namespace SafePHP;

use Error;
use PDO;
use SafePHP\CSRF;
use SafePHP\Network;
use SafePHP\Exceptions;
use SafePHP\Secret;
use SafePHP\Logs;
use SafePHP\LoginTry;

require_once "./src/Database.php";

/**
 * Manage authentification safely
 */
class Auth {
    private static array $listeLoginTry = [];
    private string $successLogin;
    private string $errorLogin;
    private Logs $logs;

    /**
     * @param string $envPath Folder where the .env file is
     * @param string $logsFile Path where save the logs about authentification success or fail
     */
    public function __construct(string $envPath, string $logsFile) {
        $secret = new Secret($envPath);
        $secret->getEnv();
        $this->successLogin = "Successfull login from " . Network::getClientIP();
        $this->errorLogin = "Failed login from " . Network::getClientIP();
        $this->logs = new Logs($_ENV["LOGS_DIR"] . $logsFile, "Authentification try", $this->successLogin, $this->errorLogin);
    }
    
    /**
     * Login function with form's name, name and password inputs
     * @param string $submit Name of the form
     * @param string $name input of the name used to login
     * @param string $password password to authentify
     * @return bool state of connexion (true or false)
     */
    public function login($submit, $name, $password) {
        $clientIP = Network::getClientIP();
        $this->checkIp($clientIP);
        if (isset($submit) && $submit != null) {
            if (!CSRF::verifyCSRF()) {
                die("Jeton CSRF invalide !");
            } else {
                if ($name === null || $password === null) {
                    die("Un ou plusieurs champs sont manquants !");
                }
                $filterName = Sanitize::sanitize($name, "text");
                $filterPassword = Sanitize::sanitize($password, "text");
                $ipClient = Network::getClientIP();
                try {
                    $connexion = Database::connectDatabase();
                    $stmt = $connexion->prepare("SELECT (name, password) FROM users WHERE name = :name");
                    $stmt->bindValue(":name", $filterName, PDO::PARAM_STR);
                    $stmt->execute();

                    $passwordverify = password_verify($filterPassword, PASSWORD_DEFAULT);

                    if ($passwordverify) {
                        $verificationUtilisateur = $connexion->prepare("SELECT * FROM users WHERE name = :name");
                        $verificationUtilisateur->bindValue(":name", $filterName, PDO::PARAM_STR);
                        $verificationUtilisateur->execute([$filterName]);
                        $unUtilisateur = $verificationUtilisateur->fetch(PDO::FETCH_ASSOC);

                        if ($unUtilisateur) {
                            if (password_verify($password, $unUtilisateur['mot_de_passe'])) {
                                new Session($unUtilisateur["idCompte"], $unUtilisateur["name"], $unUtilisateur["userAccess"]);
                                $this->logs->createLog("Info", $this->successLogin);
                                header("Location: ./index.php?action=accueilUtilisateur");
                                exit();
                            } else {
                                echo "Pseudo ou mot de passe incorrect";
                                $this->logs->createLog("Error", $this->errorLogin);
                                return self::countLoginAttemps($ipClient);
                            }
                        }
                    }
                } catch (Error $e) {
                    echo $e->getMessage();
                    die();
                }
            }
        }
    }


    /**
     * Register function with name, email and password inputs
     * @param string $name name of the user
     * @param string $email input of the email used to register
     * @param string $password password to authentify
     * @return void state of connexion (string error or header to the account page)
     */
    public function register($name, $email, $password){
        $clientIP = Network::getClientIP();
        $this->checkIp($clientIP);
        if (isset($email, $name, $password) && !empty($name) && !empty($email) && !empty($password)) {

            $filterName = Sanitize::sanitize($name, "text");
            $filterEmail = Sanitize::sanitize($email, "email");
            $filterPassword = Sanitize::sanitize($password, "text");
            $connexion = Database::connectDatabase();

            $password_hash = password_hash($filterPassword, PASSWORD_DEFAULT);
            $stmt = $connexion->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password);");
            $stmt->bindValue(":name", $filterName, PDO::PARAM_STR);
            $stmt->bindValue(":email", $filterEmail, PDO::PARAM_STR);
            $stmt->bindValue(":password", $password_hash, PDO::PARAM_STR);

            $inscription = $stmt->execute([]);

            if ($inscription) {
                session_start();
                $idCompte = $connexion->lastInsertId();

                new Session($idCompte, $name, 0);
                $this->logs->createLog("Error", "New user created at :" . Network::getClientIP());
                header("Location: ./index.php?action=accueilUtilisateur");
                exit();
            } else {
                echo "Erreur lors de l'inscription";
            }
        }
    }


    /**
     * Destroy every sessions sets
     * @return void nothing...?
     */
    public static function logout(){
        session_unset();
        session_destroy();
    }

    /**
     * Verify the authentification by the user
     * @param string $sessionName the session to verify
     * @return void return error in case of false
     */
    public static function verifAuth($sessionId) : bool{
        if ($_SESSION["user_id"] !== $sessionId) {
            echo Exceptions::getErreurSession();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Verify the authentification by the user
     * @param string $ipClient the IP Adresse that tried login
     * @return bool return true if cool if dosen't have cooldown
     */
    public static function countLoginAttemps($ipClient) : bool {
        new LoginTry($ipClient);
        //============================Change the code below...
        if (!self::hasIp($ipClient)) {
            self::$listeLoginTry[$ipClient] = [
                
            ];
        }

        //=================================================

        if (self::$listeLoginTry[$ipClient]["cooldown"] > time()) {
            echo Exceptions::getErreurCooldown();
            return false;
        }

        self::$listeLoginTry[$ipClient]["loginTry"]++;

        if (self::$listeLoginTry[$ipClient]["loginTry"] >= 5) {
            self::$listeLoginTry[$ipClient]["cooldown"] = time() + (2 * 3600); // + 2 hours
            self::$listeLoginTry[$ipClient]["loginTry"] = 0;
            Exceptions::getErreurCooldown();
            return false;
        }

        return true;
    }

    /**
     * @return array Retourne toutes les tentatives de connexion
     */
    public static function getHashMapTryLogin(): array {
        return self::$listeLoginTry;
    }

    /**
     * @return void les informations de tentatives de connexion
     */
    public static function displayLoginAttempts(): void {
        foreach (self::$listeLoginTry as $ip => $data) {
            echo "IP: {$ip} - Tentatives: {$data['loginTry']} - Cooldown: {$data['cooldown']}\n";
        }
    }

    /**
     * Verify the authentification by the user
     * @param string $clientIp the IP adresse to look for
     * @return bool return true if this IP adresse already tried connexion
     */
    public static function hasIp(string $clientIp): bool{
        return isset(self::$listeLoginTry[$clientIp]);
    }


    /**
     * Verify the authentification by the user
     * @param string $ip client that try login
     * @param int $count number of try aviable before cooldown
     * @param string $cooldown timer of the cooldown until new try for this IP
     * @return void nothing....for the moment
     */
    public static function addIpTryLogin($ip) {
        $loginTry = new LoginTry($ip);
        return $loginTry->addTry($ip);
    }

    public function checkIp($clientIp){
        $blackList = Network::getBlackList();
        $greyList =  Network::getGreyList();
        $whiteList = Network::getWhiteList();

        $exist = null;

        switch($clientIp) {
            case in_array($clientIp, $blackList) :
                $exist = new ErrorHandler(403, "403.php",__DIR__ . "/../SafePHP-Logs/auth.logs");
                break;

            case in_array($clientIp, $greyList):
                $exist = 1;
                break;

            case in_array($clientIp, $whiteList):
                $exist = 0;
                break;

            default:
                $exist = null;
                break;
        }

        return $exist;
    }
}