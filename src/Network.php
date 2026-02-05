<?php
namespace SafePHP;

/**
 * Network management with white, grey, black lists  getters/setters and IP getters
 */
class Network {
    /**
     * Authorized IP adress
     * @var array List of IP adress
     */
    private static array $WhiteList;

    /**
     * Authorized IP adress with supervision
     * @var array List of IP adress
     */
    private static array $GreyList;

    /**
     * Banned IP adress
     * @var array List of IP adress
     */
    private static array $BlackList;

    public static function getWhiteList(){
        return self::$WhiteList;
    }
    public static function getGreyList(){
        return self::$GreyList;
    }
    public static function getBlackList(){
        return self::$BlackList;
    }
    public static function createWhiteList($AList) {
        return self::$WhiteList = $AList;
    }
    public static function createGreyList($AList){
        return self::$GreyList = $AList;
    }
    public static function createBlackList($AList){
        return self::$BlackList = $AList;
    }

    public static function addWhiteList($AnIPAdress){
        return array_push(self::$WhiteList, $AnIPAdress);
    }
    public static function addGreyList($AnIPAdress){
        return array_push(self::$GreyList, $AnIPAdress);
    }
    public static function addBlackList($AnIPAdress){
        return array_push(self::$BlackList, $AnIPAdress);
    }

    public static function deleteWhiteList(){
        return self::$WhiteList = [];
    }
    public static function deleteGreyList(){
        return self::$GreyList = [];
    }
    public static function deleteBlackList(){
        return self::$BlackList = [];
    }

    /**
     * Get the client IP adress
     * @return string IP adress
     */
    public static function getClientIP(){
        $ipaddress = '';
        switch($ipaddress){
            case (isset($_SERVER['HTTP_CLIENT_IP'])):
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                break;

            case (isset($_SERVER['HTTP_X_FORWARDED_FOR'])):
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                break;

            case (isset($_SERVER["HTTP_X_FORWARDED"])) :
                $ipaddress = $_SERVER["HTTP_X_FORWARDED"];
                break;

            case (isset($_SERVER["HTTP_FORWARDED_FOR"])):
                $ipaddress = $_SERVER["HTTP_FORWARDED_FOR"];
                break;

            case (isset($_SERVER["HTTP_FORWARDED"])):
                $ipaddress = $_SERVER["HTTP_FORWARDED"];
                break;

            case (isset($_SERVER["REMOTE_ADDR"])):
                $ipaddress = $_SERVER["REMOTE_ADDR"];
                break;

            default:
                $ipaddress = "UNKNOWN";
                break;
        }
        return $ipaddress;
    }

    /**
     * Get the IPV4 format of the ip adress
     * @param mixed $ip IP adress
     * @return bool
     */
    public static function getIPv4($ip){
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
    }

    /**
     * Get the IPV6 format of the ip adress
     * @param mixed $ip IP adress
     * @return bool
     */
    public static function getIPv6($ip){
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    /**
     * Summary of checkIp
     * @param mixed $clientIp
     * @return ErrorHandler|int|null
     */
    public static function checkIp($clientIp)
    {
        $blackList = self::getBlackList();
        $greyList = self::getGreyList();
        $whiteList = self::getWhiteList();

        $exist = null;

        switch ($clientIp) {
            case in_array($clientIp, $blackList):
                $exist = new ErrorHandler(403, "403.php", __DIR__ . "/../SafePHP-Logs/auth.logs");
                break;

            case in_array($clientIp, $greyList):
                $exist = 1;
                break;

            case in_array($clientIp, $whiteList):
                $exist = 0;
                break;

            default:
                $exist = null;
                break;
        }

        return $exist;
    }
}