<?php

namespace SafePHP;

class Regex {
    private static string $regexMail = "^[-!#$%&'*+/0-9=?A-Z^_a-z{|}~](\.?[-!#$%&'*+/0-9=?A-Z^_a-z{|}~])*@[a-zA-Z](-?[a-zA-Z0-9])*(\.[a-zA-Z](-?[a-zA-Z0-9])*)+$";
    private static string $regexString = "^[a-zA-Z]+$";
    private static string $regexDate = "^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$";
    private static string $regexNumber = "^[0-9]+[a-z0-9]?$";
    private static string $regexInt = "^-?\d+$";
    

    /**
     * Verify if an input is valid with the regex expected
     * @param mixed $anInputToVerify
     * @param mixed $theTypeOfRegexToUse
     * @return bool
     */
    public static function verify($anInputToVerify, $theTypeOfRegexToUse) : bool {

        $regex = "";

        switch($theTypeOfRegexToUse){
            case "mail":
                $regex = self::$regexMail;
                break;

            case "string":
                $regex = self::$regexString;
                break;

            case "date":
                $regex = self::$regexDate;
                break;

            case "digit":
                $regex = self::$regexNumber;
                break;

            case "integer":
                $regex = self::$regexInt;
                break;

            default:
                $regex = null;
                break;
        }

        if(preg_match($regex, $anInputToVerify)){
            return true;
        } else {
            return false;
        }

    }
}