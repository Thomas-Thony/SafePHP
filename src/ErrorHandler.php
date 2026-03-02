<?php

namespace SafePHP;
use SafePHP\Logs;
use SafePHP\Globals\Globals;

/**
 * Manage http/https responses
 */
class ErrorHandler {
    /**
     * Folder direction of error pages
     * @var string
     */
    private string $errorHandlerDir = __DIR__ . "/ErrorHandler/";

    /**
     * Create a response on client and server side
     * @param int $errorCode code to give as http response
     * @param string $fileErrorInclusion path of the error file on the folder defined by $accessHandlerDir
     * @return void
     */
    public function __construct($errorCode, $fileErrorInclusion, $logPath){
        $logsHTTP = new Logs(Globals::$logsDir . $logPath, "HTTP/HTTPS reponse", "Request response : 200", "Request response : " . $errorCode);
        $logsHTTP->createLog("Error", $logsHTTP->getErrorMessage());
        http_response_code($errorCode);
        http_response_code();
        require_once $this->errorHandlerDir . $fileErrorInclusion;
        exit;
    }
}