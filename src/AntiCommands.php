<?php

namespace SafePHP;

/**
 * Avoid command injection 
 */
class AntiCommands {

    /**
     * @param string $Input path of the file
     * @return string the escaped string
     */
    public static function deleteShellArgs($Input) : string {
        return escapeshellarg($Input);
    }

    /**
     * @param string $Input path of the file
     * @return string the escaped string
     */
    public static function deleteShellCmd($Input) : string{
        return escapeshellcmd($Input);
    }
}