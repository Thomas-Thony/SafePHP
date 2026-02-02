<?php
namespace SafePHP;

/**
 * Verification of file uploaded (Type|MIME) and extensions
 */
class Verify {
    private static array $DocumentsFile = ["pdf", "doc", "docx", "txt", "odt", "ppt", "pptx"]; //Liste d'extension de documents valide
    private static array $ImagesFile = ["png", "jpeg", "jpg", "gif"]; //Liste d'extension d'image valide
    private static array $VideosFile = ["mov", "mp4", "m4a"]; //Liste d'extension vidéo valides

    /**
     * List of signature file accepted
     * @var array Mime Types authorized for each "type" of file
     */
    private static array $MimeTypes = [
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
     * @param string $AType File's type expected (Documents, Pictures, Videos)
     * @return array Return the extension list
     */
    public static function getTypeFileAviable($AType): array {
        switch ($AType) {
            case "Documents":
                return self::$DocumentsFile;
            case "Images":
                return self::$ImagesFile;
            case "Videos":
                return self::$VideosFile;
            default:
                return ["This type of file does not exist !"];
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
     * @param string  $File File's path to verify
     * @return int return 1 if the file extension is in the list of expected format, else return 0
     */
    public static function verifyExtensionImage($File){
        $Extension = strtolower(pathinfo($File, PATHINFO_EXTENSION));
        if (in_array($Extension, self::$ImagesFile)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Verify the signature (MIME type) AND the extension of the file uploaded
     * Principe : STRIC WHITE LIST only
     * @param string $FileTmpName Temp file's path
     * @param string $FileName Original name of the file
     * @param string $FileType File type expected (Documents, Pictures, Videos)
     * @return bool if everything is like expected, return true, else, return false
     */
    public static function verifySignatureFile($FileTmpName, $FileName, $FileType){
        if (!is_uploaded_file($FileTmpName)) {
            return false;
        }

        $Extension = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));

        $AllowedExtensions = self::getTypeFileAviable($FileType);
        if (!in_array($Extension, $AllowedExtensions)) {
            return false;
        }

        if (!isset(self::$MimeTypes[$Extension])) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMimeType = finfo_file($finfo, $FileTmpName);

        $allowedMimeTypesForExtension = self::$MimeTypes[$Extension];

        if (!in_array($detectedMimeType, $allowedMimeTypesForExtension)){
            return false;
        }
        return true;
    }
}