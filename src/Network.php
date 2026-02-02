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
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        if ($ip === '::1') {
            $ip = '127.0.0.1';
        }
        return $ip;
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
}