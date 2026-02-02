<?php
namespace SafePHP;
use SafePHP\Database;
/**
 *  Verification of each file included on server, database, or form
 */
class FileInclusion {
    /**
     * Include a file on the server or database
     * @param string $FileDirection the file path
     * @param string $SQLRequest SQL request to include the file
     * @return bool|string
     */
    public static function includeFile($FileDirection = null, $SQLRequest = null){
        //If a path is defined
        if($FileDirection != null) {
           //If a SQL request is set
        } elseif($SQLRequest != null) {
            return Database::InsertSQL($SQLRequest);
        }

        if(($FileDirection != null) && ($SQLRequest != null)) { //The two conditions cannot be set at the same time
            throw new \BadFunctionCallException("Merci de ne choisir qu'une seule option (Chemin d'accès ou requête SQL) !");
        }

        //At least one parameter should be set
        if(($FileDirection === null) && ($SQLRequest === null)){
            throw new \BadFunctionCallException("Merci de choisir une option (Chemin d'accès ou requête SQL) !");
        }
    }

    /**
     * Renamme a file et move it on a temp folder
     * @param string $tmpFilePath the path of the file
     * @param string $originalFileName file name when uploaded by user
     * @return bool|string
     */
    public static function renameFile($tmpFilePath, $originalFileName){
        $TempDir = "./.tmp";
        $ExtensionFile = pathinfo($originalFileName, PATHINFO_EXTENSION);

        $RandomName = bin2hex(random_bytes(24));
        $NewFilePath = "$TempDir/$RandomName.$ExtensionFile";

        $Movefile = move_uploaded_file($tmpFilePath, $NewFilePath);

        if ($Movefile) {
            echo "Déplacement du fichier réussi !";
            return $NewFilePath;
        } else {
            echo "Erreur lors du déplacement de fichier";
            return false;
        }
    }

    /**
     * Return the type "MIME" of the picture
     * @param mixed $File path of the file to verify
     * @return bool|int
     */
    public static function verifySignatureImage($File){
        return exif_imagetype($File);
    }

    /**
     *  Recreate an image uploaded,  this lead to, in case of a file uploaded is a script disguised, to avoid code injection
     * @param string $FileName Name of the file
     * @param string $tmpFilePath Path of the file
     * @return bool|string
     */
    public static function recreateImage($FileName, $tmpFilePath) {
        if($FileName != null) {
            self::verifySignatureImage($FileName);
            self::reSizeImage($FileName);
            self::renameFile($tmpFilePath, $FileName);
            return self::includeFile($FileName);
         }
    }

    /**
     * Resize the picture to be in sens with standard of type(MIME)
     * @param string $File the path of the file
     * @return bool
     */
    public static function reSizeImage($File) {
        //Get the picture content
        $FileContent = file_get_contents($File);
        $SizeFile = getimagesize($FileContent);
        
        //Get the dimensions of the picture
        $largeur = $SizeFile[0];
        $longeur = $SizeFile[1];

        //Re-creation of the pitcure as 'virgin' in memory
        $newImage = imagecreatetruecolor($largeur / 2,  $longeur / 2);

        //We adapt the picture creation by the signature
        switch($SizeFile["mime"]){
            case "image/png":
                $imageSource = imagecreatefrompng($File);
                break;
            case "image/jpeg":
                $imageSource = imagecreatefromjpeg($File);
                break;
            default:
                die("Format incompatible !");
        }

        //Copy the picture created in the switch to the 'virgin' one
        
        $copyImage = imagecopyresampled(
            $newImage, //Final picture
            $imageSource, //Starting  picture
            0,
            0,
            0,
            0,
            $largeur / 4,
            $longeur / 4,
            $largeur,
            $longeur
        );

        switch ($SizeFile["mime"]) {
            case "image/png":
                $uploadImage = imagepng($newImage, __DIR__ . "/.tmp" . $File);
                break;
            case "image/jpeg":
                $uploadImage = imagejpeg($newImage, __DIR__ . "/.tmp" . $File);
                break;
        }

        imagedestroy($imageSource); //trouver des alertnatives à la fonction imagedestroy (dépréciée)
        imagedestroy($newImage);

        return $uploadImage;
    }
}