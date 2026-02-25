<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SafePHP\Router;
use SafePHP\SRI;
use SafePHP\ErrorHandler;
use SafePHP\AccessHandler;

$session = $_SESSION["session"];
$envPath = __DIR__ . "/../config/";
$accessHandler = new AccessHandler($envPath, __DIR__ . "/../SafePHP-Logs/router.logs");

$pagesFolder = "./Pages/";
$stylesFolder = "./styles/";
$scriptFolder = "./JS/";

$whiteListOfTitle = array(
    "0" => "",
    "1" => "",
    "2" => "",
    "3" => "",
    "4" => "",
    "5" => "",
    "6" => "",
    "7" => "",
    "8" => "",
    "9" => "",
    "10" => "",
    "11" => "",
    "12" => "",
);

$whiteListAccesPages = array(
    "0" => 0,
    "1" => 0,
    "2" => 0,
    "3" => 0,
    "4" => 0,
    "5" => 0,
    "6" => 0,
    "7" => 0,
    "8" => 5,
    "9" => 0,
    "10" => 0,
    "11" => 4,
    "12" => 1,
);

$whiteListOfPages = array(
    "0" => "/../Components/accueill.php",
    "1" => "CSRF_TEST.php",
    "2" => "Verify_TEST.php",
    "3" => "Sanitize_TEST.php",
    "4" => "Auth_TEST.php",
    "5" => "Form_TEST.php",
    "6" => "FileInclusion_TEST.php",
    "7" => "Test_SRI.php",
    "8" => "Network_TEST.php",
    "9" => "AccessHandler_TEST.php",
    "10" => "Router_TEST.php",
    "11" => "Logs_TEST.php",
    "12" => "RBAC_TEST.php",
);

$whiteListeOfCSS = array(
    "0" => "accueil.css",
    "1" => "CSRF_TEST.css",
    "2" => "",
    "3" => "",
    "4" => "",
    "5" => "Form_TEST.css",
    "6" => "Form_TEST.css",
    "7" => "Form_TEST.css",
    "8" => "Form_TEST.css",
    "9" => "Form_TEST.css",
    "10" => "Form_TEST.css",
    "11" => "Form_TEST.css",
    "12" => "Form_TEST.css",
);

$whiteListeOfJS = array(
    "0" => "",
    "1" => "",
    "2" => "",
    "3" => "",
    "4" => "",
    "5" => "",
    "6" => "",
    "7" => "",
    "8" => "",
    "9" => "",
    "10" => "",
    "11" => "",
    "12" => "",
);

if (isset($_GET['action'])) {
    $page = $_GET["action"];
    if (array_key_exists($page, $whiteListOfPages)) {
        $accessHandler->verifyAccess($session, $whiteListAccesPages[$page]);

        $content = $pagesFolder . $whiteListOfPages[$page];
        $title = $whiteListOfTitle[$page];

        if ($whiteListeOfCSS[$page] === "") {
            $fileCSS = "default.css";
            SRI::createSRI("css", $stylesFolder . $fileCSS);
        } else {
            $ressourceCSS = SRI::createSRI("css", $stylesFolder . $whiteListeOfCSS[$page]);
        }

        if ($whiteListeOfJS[$page] === "") {
            $fileJS = "default.js";
            $ressourceJS = SRI::createSRI("js", $scriptFolder . $fileJS);
        } else {
            $ressourceJS = SRI::createSRI("js", $scriptFolder . $whiteListeOfJS[$page]);
        }

    } else {
        new ErrorHandler(404, "404.php", "/router.log");
    }
}