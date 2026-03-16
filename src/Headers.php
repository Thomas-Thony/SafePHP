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
        header("Header set Content-Security-Policy 'default-src 'self'; script-src 'self' www.google-apis.com *.cloudflare.com someotherDomain.com; img-src 'self' *.cloudflare.com;'");
        header('Referrer: never');
        header("X-XSS-Protection: 1; mode=block");
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