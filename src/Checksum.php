<?php

namespace SafePHP;

use ErrorException;
use SafePHP\GLOBALS\Globals;


class Checksum {

    public static function createCheckSum($file) : bool | string {
        return hash_file("SHA256", $file, false);
    }

    public static function hasChanged($aFile) : bool {
        if (self::exist($aFile) === true) {
            $fileContent = file_get_contents(Globals::$checksumDir . $aFile);
            $file = json_decode($fileContent, true);
            $oldChecksum = $file["checksum"];
            $actualChecksum = self::createCheckSum($aFile);
            if ($actualChecksum !== $oldChecksum) {
                throw new ErrorException("Both are not the same ! \n Checksum of the file : " . $oldChecksum . "\nNew checksum :" . $actualChecksum);
            } else {
                return false;
            }
        } else {
            throw new ErrorException("This file does not exist in Checksum folder!");
        }
    }

    public static function exist(string $filename) : bool{
        $checksumFolder = Globals::$checksumDir;
        $listOfChecksum = scandir($checksumFolder);
        $fullFileName = $filename . ".json";
        if ($listOfChecksum === false) {
            return false;
        } else {
            if (in_array($fullFileName, $listOfChecksum)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function addToChecksum($file, string $name): bool | int{
        $checksumExtension = ".json";
        $checkSumDir = Globals::$checksumDir;
        $hashFile = self::createCheckSum($file);
        if($hashFile === false){
            throw new ErrorException("The file specified was not found !");
        }

        $newFile = ["name" => $name, "Path" => $file, "checksum" => $hashFile];
        $jsonFileContent = json_encode($newFile, JSON_PRETTY_PRINT);
        if(self::exist($name)){
            throw new ErrorException("This file name is already taken, please use another one !");
        } else {
            return file_put_contents($checkSumDir . $name . $checksumExtension, $jsonFileContent);
        }
    }
}