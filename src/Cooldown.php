<?php

namespace SafePHP;

class Cooldown {
    private int $maxEssais;
    private int $maxCooldownTime;
    
    /**
     * Summary of __construct
     */
    public function __construct() {
        $this->maxEssais = 5;
        $this->maxCooldownTime = 3600;
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION["attemps"] = 0;
        $_SESSION["cooldown"] = 0;
    }

    /**
     * Summary of canDoAttemps
     * @return void
     */
    public function canDoAttemps() {
        if(isset($_SESSION["cooldown"])) {
            $endCooldown = $_SESSION["cooldown"];

            if(time() > $endCooldown) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function resetCooldown(){
        return $_SESSION["cooldown"] = 0;
    }

    public function addAttemp(){
        if($_SESSION["attemps"] >= $this->maxEssais){
            $_SESSION["attemps"] = 0;
            return $_SESSION["cooldown"] = 3600;
        }
        return $_SESSION["attemps"]++;
    }

}