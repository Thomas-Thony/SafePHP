<?php

namespace SafePHP;

use SafePHP\Auth;

/**
 * Manage access of user for ressources
 */
class AccessHandler {

    private string $logFile;

    /** Get the code acces of an user
     * @return int the acces code of user
     */
    public static function getPermissionsUtilisateur() : int {
        return $_SESSION['user_access_code'];
    }

    /**
     * Construct an AccessHandler
     * @param string $aLogFile the path to the logs for the router
     */
    public function __construct(string $aLogFile) {
        $this->logFile = $aLogFile;
    }

    /**
     * Verify if the actual user can access a ressource by comparing his access's level
     * @param int $codeAcces to have to pass
     * @throws ErrorHandler if false
     * @return void Return http code 200, or ErrorHandler object
     */
    public function verifyAccess($codeAcces): bool|ErrorHandler|int {
       $userId =  Auth::verifAuth($_SESSION["user_id"]);
       
       if ($userId === false || $userId === null) {
           return new ErrorHandler(401, "401.php", $this->logFile); /*Access unauthorized */
       } else {
           /*If there are temporary permissions given */
            if(isset($_SESSION["temp_access_code"]) && $_SESSION["temp_access_code"] !== null){
                if ($_SESSION["temp_access_code"] > $_SESSION["user_access_code"]) {
                    $codeAccessUser = $_SESSION["temp_access_code"];
                } else {
                    $codeAccessUser = $_SESSION["user_access_code"];
                }
            } else {
                $codeAccessUser = $_SESSION["user_access_code"];
            }
    
            if ($codeAccessUser < $codeAcces) {
                return new ErrorHandler(403, "403.php", $this->logFile); /*Access forbidden*/
            } else {
                http_response_code(200);
                return http_response_code();
            }
       }
    }
}