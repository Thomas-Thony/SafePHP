<?php

namespace SafePHP;
use SafePHP\SRI;
use SafePHP\AccessHandler;
use SafePHP\ErrorHandler;
use SafePHP\Exceptions;

/**
 * Router management with list of ressources authorized (avoid file inclusion or code inclusion)
 */
class Router {
    private array $whiteListOfPages = [];
    private array $whiteListOfTitle = [];
    private array $whiteListeOfCSS = [];
    private array $whiteListOfJS = [];
    private array $whiteListAccesPages = [];

    private string $stylesFolder;
    private string $pagesFolder;
    private string $scriptFolder;

    private AccessHandler $accesHandler;

    /**
     * Construct a router
     * Create a list of pages, styles and scripts files to accept only them on GET method
     */
    public function __construct(array $aWhiteListOfPages, array $aWhiteListOfTitle, array $aWhiteListOfCSS, array $whiteListOfJS, array $aWhiteListOfAccess, string $aStyleFolder, string $aPagesFolder, string $aScriptFolder){
        $this->whiteListOfPages = $aWhiteListOfPages;
        $this->whiteListOfTitle = $aWhiteListOfTitle;
        $this->whiteListeOfCSS = $aWhiteListOfCSS;
        $this->whiteListOfJS = $whiteListOfJS;
        $this->whiteListAccesPages = $aWhiteListOfAccess;
        $this->stylesFolder = $aStyleFolder;
        $this->pagesFolder = $aPagesFolder;
        $this->scriptFolder = $aScriptFolder;

        $this->accesHandler = new AccessHandler(__DIR__ . "/../SafePHP-Logs/auth.logs");
    }

    /**
     * Return the whole properties constructed
     * @return void A list at HTML format of each properties
     */
    public function getListsComponents(){
        echo "Liste des pages : <ul>";
        echo "<br>";
        foreach ($this->whiteListOfPages as $aWhitePage) {
            echo "<li>";
            echo $aWhitePage;
            echo "</li>";
            echo "<br>";
        }
        echo "</ul>";

        echo "Liste des titres de pages : <ul>";
        echo "<br>";
        foreach ($this->whiteListOfTitle as $aWhiteItemTitle) {
            echo "<li>";
            echo $aWhiteItemTitle;
            echo "</li>";
            echo "<br>";
        }
        echo "</ul>";

        echo "Liste des styles : <ul>";
        echo "<br>";
        foreach ($this->whiteListeOfCSS as $aWhitePageOfCSS) {
            echo "<li>";
            echo $aWhitePageOfCSS;
            echo "</li>";
            echo "<br>";
        }
        echo "</ul>";

        echo "Liste des scripts : <ul>";
        echo "<br>";
        foreach ($this->whiteListOfJS as $aWhitePageOfJS) {
            echo "<li>";
            echo $aWhitePageOfJS;
            echo "</li>";
            echo "<br>";
        }
        echo "</ul>";

        echo "Liste des accès :";
        echo "<br>";
        foreach ($this->whiteListAccesPages as $aWhitePageOfAccess) {
            echo "<li>";
            echo $aWhitePageOfAccess;
            echo "</li>";
            echo "<br>";
        }
        echo "</ul>";

        echo "Chemin des pages : " . $this->pagesFolder;
        echo "<br>";

        echo "Chemin des styles : " . $this->stylesFolder;
        echo "<br>";

        echo "Chemin des scripts : " . $this->scriptFolder;
        return;
    }

    public function getWhiteListOfPages(){
        return $this->whiteListOfPages;
    }
    
    public function getWhiteListOfTitles(){
        return $this->whiteListOfTitle;
    }

    public function getWhiteListOfCSS(){
        return $this->whiteListeOfCSS;
    }

    public function getWhiteListOfJS(){
        return $this->whiteListOfJS;
    }

    public function getWhiteListOfAccessPages(){
        return $this->whiteListAccesPages;
    }

    /**
     * Summary of linkTo
     * @param string $link the link from the GET method
     * @param Session $session Session object to verify role access
     * @return array<mixed|string> return on a array each file inclusion
     */
    public function linkTo(string $link, Session $session){
        if (isset($link)) {
            if (array_key_exists($link, $this->whiteListOfPages)) {
                $this->accesHandler->verifyAccess($session, $this->whiteListAccesPages[$link]);

                $content = $this->pagesFolder . $this->whiteListOfPages[$link];
                $title = $this->whiteListOfTitle[$link];

                if ($this->whiteListeOfCSS[$link] === "") {
                    $fileCSS = "default.css";
                    SRI::createSRI("css", $this->stylesFolder . $fileCSS);
                } else {
                    $ressourceCSS = SRI::createSRI("css", $this->stylesFolder . $this->whiteListeOfCSS[$link]);
                }

                if ($this->whiteListOfJS[$link] === "") {
                    $fileJS = "default.js";
                    $ressourceJS = SRI::createSRI("js", $this->scriptFolder . $fileJS);
                } else {
                    $ressourceJS = SRI::createSRI("js", $this->scriptFolder . $this->whiteListOfJS[$link]);
                }

                return array(
                    $content,
                    $title,
                    $ressourceCSS,
                    $ressourceJS
                );
                
            } else {
                new ErrorHandler(404, "404.php", __DIR__ . '/../SafePHP-Logs/router.logs');
            }
        }
    }

    /**
     * 
     * @param mixed $ressourceType the type of file to include
     * @param mixed $elementIndex the place where the item is store on the list
     * @param mixed $nomLien if the type of file is page, then give a name to the link
     * @return string output the link or error
     */
    public function createLink($ressourceType, $elementIndex, $nomLien = null){
        $link = "";
        switch ($ressourceType) {
            case "page":
                if (!isset($nomLien) || $nomLien === null || $nomLien === " ") {
                    Exceptions::setErreurCustom("Merci de mettre un texte de lien !");
                } else {
                    $link = "<a href='index.php?action=" . $elementIndex . "'>" . $nomLien . "</a>";
                }
                break;

            case "style":
                $link = "<link rel='stylesheet' href='" . $elementIndex . "'>";
                break;

            case "script":
                $link = "<script src='" . $elementIndex . "'></script>";
                break;

            default:
                Exceptions::setErreurCustom("Merci de rentrer un type de lien valide ! (page, style ou script)");
                break;
        }
        echo $link;
    }
}