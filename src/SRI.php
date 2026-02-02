<?php

namespace SafePHP;

/**
 * Ensure Sub-Ressource Integrity
 */
class SRI {
    /**
     * Create a hash on a ressource file with sha384 hash algorithm
     * @param string $FileContent File path
     * @return string
     */
    public static function hashSRI($FileContent) {
        $hash = hash("sha384", $FileContent, true);
        $hash_base64 = base64_encode($hash);
        return "sha384-$hash_base64";
    }

    /**
     * Input a ressource file with Sub-Ressource Integrity
     * @param string $InputType CSS or JS, else, it return an error
     * @param string $FileDirection File path
     * @return string return the whole ressource input (you can see on web dev tools on your browser)
     */
    public static function createSRI($InputType, $FileDirection) {
        $FileContent = file_get_contents($FileDirection);
        $SRIHash = self::hashSRI($FileContent);
        if($InputType === "css") {
            return  "<link rel='stylesheet' href='" . $FileDirection . "' integrity='" . $SRIHash . "' crossorigin='anonymous'>";
        } elseif($InputType === "js") {
            return  "<script src='" . $FileDirection ."' integrity='" . $SRIHash . "' crossorigin='anonymous'></script>";
        } else {
            die("Le format de ressource saisi n'est pas correct !");
        }
    }
}