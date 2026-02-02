<?php

namespace SafePHP;

/**
 * Local Exceptions handler class
 */
class Exceptions {
    /**
     * @var string File signature error
     */
    private static string $ErreurSignature = "<p style='color:red; font-weight: 600;'>Error, the file sent is not on the right type for : ";

    /**
     * @var string Extension file error
     */
    private static string $ErreurExtension = "<p style='color:red; font-weight: 600;'>Error, the file sent is not on the asked type !</p>";

    /**
     * @var string Empty file error
     */
    private static string $ErreurFichierVide = "<p style='color:red; font-weight: 600;'>Nothing has been sent !</p>";

    /**
     * @var string Invalid session error
     */
    private static string $ErreurSession = "<p style='color:red; font-weight: 600;'>The session is not valid !</p>";

    /**
     * @var string Cooldown login error
     */
    private static string $ErreurCooldown = "<p style='color:red; font-weight: 600;'>A cooldown is active!</p>";

    /**
     * @var string Error set by the method setErreurCustom($aCustomError)
     */
    private static string $ErreurCustom;

    /**
     * Create a custom error
     * @param string $AnError Custom message
     * @return string The output of the custom error
     */
    public static function setErreurCustom(string $AnError) : string {
        return self::$ErreurCustom = $AnError;
    }

    public static function getErreurSignature() : string {
        return self::$ErreurSignature;
    }

    /**
     *  Get the extension file error
     * @return string The extension file error
     */
    public static function getErreurExtension(): string {
        return self::$ErreurExtension;
    }

    /**
     *  Get the empty file error
     * @return string The empty file error
     */
    public static function getErreurFichierVide(): string {
        return self::$ErreurFichierVide;
    }

    /**
     * Get the session error
     * @return string The session error
     */
    public static function getErreurSession(): string{
        return self::$ErreurSession;
    }

    /**
     * Get the cooldown error
     * @return string The cooldown error
     */
    public static function getErreurCooldown(): string{
        return self::$ErreurCooldown;
    }
}