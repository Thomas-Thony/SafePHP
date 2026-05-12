<?php

namespace SafePHP;
use SessionHandler;
use Exception;
use SafePHP\Auth;
use SafePHP\Globals\Globals;

/**
 * Manage user session with encryption, access verification, headers and lifetime session
 */
class Session extends SessionHandler{
    private string $encryptMethod = "AES-256-CBC";
    private string $secretKey ;

    /**
     * Construct the Session object with env key a secrets key
     * @param bool $ARegenCookie set if the constructor must regenerate the session's id or not
     * @return void session
     */
    public function __construct($userId, $userName, $userAccessCode){
        $secret = new Secret(__DIR__ . "../../config/");
        $secret->getEnv();
        $this->secretKey =  $_ENV["SESSION_SECRET_KEY"];
        return $this->createSession($userId, $userName, $userAccessCode);
    }

    /**
     * Create a session
     * @param mixed $userId
     * @param mixed $userName
     * @param mixed $userAccessCode
     * @return bool
     */
    public function createSession($userId, $userName, $userAccessCode){
        if($userName === null || !isset($userName)) {
            die("Invalide name");
        }
        try {
            // Session configuration
            session_set_cookie_params([
                'lifetime' => 0, //When browser is closed, the session is deleted
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'] ?? '',
                'secure' => true, // HTTPS only
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            // Session name
            session_name('SafePHPSESSION');

            session_start();

            // Regenerate id session
            session_regenerate_id(true);

            // Store user's data in session
            $userPermissions = new RBAC($userAccessCode);
            $_SESSION['user_id'] = $this->encryptSessionId($userId);
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_access_code'] = $userAccessCode;
            $_SESSION['user_access'] = $userPermissions;
            $_SESSION['created_at'] = time();
            $_SESSION['last_regeneration'] = time();
            $_SESSION['start_temp_perms'] = null;
            $_SESSION['end_temp_perms'] = null;
            $_SESSION["temp_access_code"] = null;
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

            // Headers for more security (even if the are already in .htaccess)
            if (!headers_sent()) {
                header('X-Frame-Options: DENY');
                header('X-Content-Type-Options: nosniff');
                header('X-XSS-Protection: 1; mode=block');
                header('Referrer-Policy: strict-origin-when-cross-origin');
                header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
                header('Cache-Control: no-cache, no-store, must-revalidate, private');
                header('Pragma: no-cache');
                header('Expires: 0');
            }

            return true;

        } catch (Exception $e) {
            error_log("Erreur création session: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Disable a session
     * @param string $ASessionName the session to disable
     * @return void Nothing...?
     */
    public static function disableSession(){
        unset($_SESSION);
    }

    /**
     * @param int $ASessionId the identifiant of session
     * @return bool return true if it succes, else false
     */
    public static function regenSession($ASessionId){
        return session_regenerate_id($ASessionId);
    }


    /** Encrypt the session's id on sha256
     * @param int $id
     * @return string Return the key encreypted
     */
    public function encryptSessionId($id) {
        $encryptedKey = hash('sha256', $this->secretKey);
        $createdIV = openssl_random_pseudo_bytes(16);
        $iv = substr(hash('sha256', $createdIV), 0, 16);
        $encryptedSessionId = openssl_encrypt($id, $this->encryptMethod, $encryptedKey, OPENSSL_RAW_DATA, $iv);
        $encryptedSessionId = base64_encode($iv . $encryptedSessionId);
        return $encryptedSessionId;
    }

    /** Dencrypt the session's id
     * @param int $id user iditifiant (get it in session)
     * @return string Return the key decreypted
     */
    public function decryptSessionId($data){
        $data = base64_decode($data);
        if (strlen($data) < 16) {
            return false; //Invalid data
        }
        $iv = substr($data, 0, 16); // Get the initialisation vector
        $encryptedSessionId = substr($data, 16); //Get encrypted text
        $encodedKey = hash("sha256", $this->secretKey);
        return openssl_decrypt($encryptedSessionId, $this->encryptMethod, $encodedKey, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * Get the id of a session
     * @return string session id or an error if session is not set (user not connected)
     */
    public function getInfosSession(){
        if (isset($_SESSION)) {
            return $this->decryptSessionId($_SESSION["user_id"]);
        } else {
            return "No session created !";
        }
    }

    public static function checkLastActivity(){
        if(isset($_SESSION["last_activity"])) {
            if($_SESSION["last_activity"] > 3600) {
                $logoutMessage = "Session deleted, inactive for an hour or more.";
                $logs = new Logs(Globals::$logsDir . "session.logs", "Session_Logs", " ", $logoutMessage);
                $logs->createLog("Error", $logoutMessage);
                Auth::logout();
                return 1;
            } else {
                return 0;
            }
        }
    }

    public static function resetTempPerms(){
        $_SESSION["start_temp_perms"] = null;
        $_SESSION["end_temp_perms"] = null;
        $_SESSION["temp_access_code"] = 0;
        return $_SESSION;
    }

    public static function checkTempPerms() {
        if(isset($_SESSION["start_temp_perms"]) && isset($_SESSION["end_temp_perms"])) {
            $actualDate = date(DATE_RFC2822);
            $startTempPerms = $_SESSION["start_temp_perms"];
            $endTempPerms = $_SESSION["end_temp_perms"];
    
            if(isset($startTempPerms) && isset($endTempPerms)) {
                if($actualDate > $endTempPerms){
                    return self::resetTempPerms();
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    }
}