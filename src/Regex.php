<?php

namespace SafePHP;

class Regex {
    private static string $regexMail = "/^[-!#$%&'*+\/0-9=?A-Z^_a-z{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z{|}~])*@[a-zA-Z](-?[a-zA-Z0-9])*(\.[a-zA-Z](-?[a-zA-Z0-9])*)+$/";
    private static string $regexString = "/^[a-zA-Z]+$/";
    private static string $regexDate = "/^[0-9]{1,2}\\/[0-9]{1,2}\\/[0-9]{4}$/";
    private static string $regexNumber = "/^[0-9]+[a-z0-9]?$/";
    private static string $regexInt = "/^-?\d+$/";

    /**
     * Verify if an input is valid with the regex expected
     * @param mixed $anInputToVerify anything you want
     * @param mixed $theTypeOfRegexToUse : mail, string, date, digit or integer
     * @return bool true if the input is valid according to the regex used
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
                $regex = false;
                break;
        }
        return (bool) preg_match($regex, (string) $anInputToVerify);
    }
}