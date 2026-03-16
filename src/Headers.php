<?php

namespace SafePHP;

/**
 * Send header when somes already sent or create somes
 */
class Header {
    public function __construct(){

    }

    public function sendCustomHeaders(int $maxAgeHSTS, string $xFrameOptions){
        header("HTTP STRICT TRANSPORT SECURITY");
        header("Strict-Transport-Security 'max-age=" . $maxAgeHSTS . "'");
        header("X-Frame-Options: " . $xFrameOptions . "");
        header('X-Content-Type-Options: nosniff');
    }

    /**
     * Make redirection without header function in php
     * @param string $url the url where to redirect
     * @return
     */
    public static function redirect(string $url){
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href="' . $url . '";';
            echo '</script>';
            echo '<noscript>';
            echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
            echo '</noscript>';
            exit(0);
        }
    }
}