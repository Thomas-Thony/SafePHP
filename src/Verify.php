<?php
namespace SafePHP;

use ErrorException;

/**
 * Verification of file uploaded (Type|MIME) and extensions
 */
class Verify {
    private static array $documentsFile = ["pdf", "doc", "docx", "txt", "odt", "ppt", "pptx"]; //Liste d'extension de documents valide
    private static array $imagesFile = ["png", "jpeg", "jpg", "gif"]; //Liste d'extension d'image valide
    private static array $videosFile = ["mov", "mp4", "m4a"]; //Liste d'extension vidéo valides

    /**
     * List of signature file accepted
     * @var array Mime Types authorized for each "type" of file
     */
    private static array $mimeTypes = [
        // Documents
        "pdf" => ["application/pdf"],
        "doc" => ["application/msword"],
        "docx" => [
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip"
        ],
        "txt" => ["text/plain"],
        "odt" => [
            "application/vnd.oasis.opendocument.text",
            "application/zip"
        ],
        "ppt" => ["application/vnd.ms-powerpoint"],
        "pptx" => [
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/zip"
        ],
        // Pictures
        "png" => ["image/png"],
        "jpeg" => ["image/jpeg"],
        "jpg" => ["image/jpeg"],
        "gif" => ["image/gif"],
        // Videos
        "mov" => ["video/quicktime"],
        "mp4" => ["video/mp4"],
        "m4a" => ["audio/mp4", "audio/x-m4a"]
    ];

    /**
     * Return an extension list about the type given in parameter
     * @param string $type File's type expected (Documents, Pictures, Videos)
     * @return array Return the extension list
     */
    public static function getTypeFileAviable($type): array {
        switch ($type) {
            case "Documents":
                return self::$documentsFile;
            case "Images":
                return self::$imagesFile;
            case "Videos":
                return self::$videosFile;
            default:
                throw new ErrorException("This type of file does not exist !");
        }
    }

    /**
     * Verify the type of the value sent
     * @param string $input The value to verify
     * @param string $typeToHave The type to expect
     * @return int|string return 1 if the type verified is correct le type vérifié, else false, if the type requested is unknow, the return null
     */
    public static function verify($input, $typeToHave){
        switch ($typeToHave) {
            case "bool":
                return is_bool($input) ? 1 : 0;
            case "integer":
                return is_integer($input) ? 1 : 0;
            case "float":
                return is_float($input) ? 1 : 0;
            case "int":
                return (is_int($input) || (is_numeric($input) && (int) $input == $input)) ? 1 : 0;
            case "double":
                return is_double($input) ? 1 : 0;
            case "string":
                return is_string($input) ? 1 : 0;
            case "array":
                return is_array($input) ? 1 : 0;
            case "object":
                return is_object($input) ? 1 : 0;
            case "resource":
                return is_resource($input) ? 1 : 0;
            case "NULL":
                return is_null($input) ? 1 : 0;
            case "unknown type":
                return "null";
            default:
                return "null";
        }
    }


    /**
     * Verify the picture extension sent
     * @param string  $file File's path to verify
     * @return int return 1 if the file extension is in the list of expected format, else return 0
     */
    public static function verifyExtensionImage($file){
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($extension, self::$imagesFile)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Verify the signature (MIME type) AND the extension of the file uploaded
     * Principe : STRIC WHITE LIST only
     * @param string $fileTmpName Temp file's path
     * @param string $fileName Original name of the file
     * @param string $fileType File type expected (Documents, Pictures, Videos)
     * @return bool if everything is like expected, return true, else, return false
     */
    public static function verifySignatureFile($fileTmpName, $fileName, $fileType){
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = self::getTypeFileAviable($fileType);

        if (!in_array($extension, $allowedExtensions) || !isset(self::$MimeTypes[$extension]) || !is_uploaded_file($fileTmpName)) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMimeType = finfo_file($finfo, $fileTmpName);

        $allowedMimeTypesForExtension = self::$mimeTypes[$extension];

        if (!in_array($detectedMimeType, $allowedMimeTypesForExtension)){
            return false;
        }
        return true;
    }
}