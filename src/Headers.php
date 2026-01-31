<?php

namespace SafePHP;

class Header {
    /**
     * Make redirection without header function in php
     * @param string $url the url where to redirect
     * @return never
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
            exit;
        }
    }
}