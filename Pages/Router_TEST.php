<?php

use SafePHP\Router;

$pagesFolder = "";
$stylesFolder = "../styles/";
$scriptFolder = "../scripts/";

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
);

$whiteListeOfJS = array(

);

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
);

$router = new Router($whiteListOfPages,  $whiteListOfTitle, $whiteListeOfCSS, $whiteListeOfJS, $whiteListAccesPages, $stylesFolder, $pagesFolder, $scriptFolder);

echo $router->getListsComponents();

?>

<p>
    <?php
        $CSSContent = $router->getWhiteListOfCSS();
        foreach ($CSSContent as $key => $cssFile) {
            echo "Clé: [$key] => Valeur: $cssFile<br>";
        }
    ?>
</p>

<p>
    Lien hypertexte :
    <?php
        $router->createLink("page",  2 ,"lien");
    ?>
</p>

<p>
    Lien hypertexte Bis :
    <?php
        $router->linkTo("test",$_SESSION["session"] );
    ?>
</p>