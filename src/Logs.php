<?php
namespace SafePHP;
require_once "./vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use PHPUnit\Framework\Constraint\ExceptionMessageIsOrContains;

class Logs {
    private string $logsFile;
    private string $channelName;
    private string $infoMessage;
    private string $errorMessage;
    private Logger $logger;
    private StreamHandler $fileHandler;
    
    
    public function __construct(string $fileLogs, string $channelName, string $anInfoMessage, string $anErroMessage) {
        $this->logsFile = $fileLogs;
        $this->channelName = $channelName;
        $this->infoMessage = $anInfoMessage;
        $this->errorMessage = $anErroMessage;
        
        $this->logger = new Logger($this->channelName);
        $this->fileHandler = new StreamHandler($this->logsFile, Level::Info);
        
        $this->logger->pushHandler($this->fileHandler);
    
    }

    /**
     * Create a custom info or error log
     * @param string $type type of log => Error or Info
     * @return string Log created or error
     */
    public function createLog(string $type, string $customMessage){
        switch($type) {
            case "Error":
                $this->logger->error($customMessage);
                break;

            case "Info":
                $this->logger->error($customMessage);
                break;

            default:
                return Exceptions::setErreurCustom("Le choix 'Error' ou 'Info' est attendu !");
        }
        return;
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }
}