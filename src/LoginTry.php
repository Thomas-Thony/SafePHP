<?php
namespace SafePHP;
class LoginTry {
    private string $ipAdress;
    private int $try = 0;
    private array $loginTry;
    private int $cooldown;

    public function __construct(string $aIPAdress) {
        $this->try++;
        $this->ipAdress = $aIPAdress;
        $this->loginTry = ["IP Adress" => $this->ipAdress, "Number of try" => $this->try, "Cooldown" => null];
    }

    public function addTry(string $aIPAdress){
        if(!in_array($aIPAdress, $this->loginTry)) {
            return new LoginTry($aIPAdress);
        } else {
            $this->try++;
            array_push($this->loginTry, $this->try);
            return $this->loginTry;
        }
    }

    public function createCooldown(string $ipAdress) {
        if (in_array($ipAdress, $this->loginTry["IP Adress"])) {
            $this->cooldown = 3600;
            $this->loginTry["cooldown"];
        }
    }

    public function verifyCooldown(string $ipAdress) : bool|string {
        if(in_array($ipAdress, $this->loginTry["IP Adress"])) {
            $cooldown = $this->loginTry["Cooldown"];
            if($cooldown === null) {
                return true;
            } else {
                return "Vous avez une attente de " . $cooldown . " minutes !";
            }
        } else {
            return false;
        }
    }
}