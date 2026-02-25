<?php

namespace SafePHP;

use SafePHP\GLOBALS\Globals;

class Checksum {

    public static function createCheckSum($file){
        return hash_file("SHA256", $file, false);
    }

    public static function HasChanged($aFile, $name) : bool { //Supprimer oldchecksum et le chercher
        if(self::exist($aFile)){
            $oldChecksum = json_decode($aFile);
            $actualChecksum = self::createCheckSum($aFile);
            if($actualChecksum !== $oldChecksum) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function exist(string $filename){
        $checksumFolder = Globals::$checksumDir;
        $listOfChecksum = scandir($checksumFolder);
        if(in_array($filename, $listOfChecksum)){
            return true;
        } else {
            return false;
        }
    }

    public static function addToChecksum($file, string $name){
        $checkSumDir = Globals::$checksumDir;
        $hashFile = self::createCheckSum($file);
        
        $newFile = ["name" => $name, "checksum" => $hashFile];
        $jsonFileContent = json_encode($newFile, JSON_PRETTY_PRINT);
        return file_put_contents($checkSumDir . $name, $jsonFileContent);
    }
}