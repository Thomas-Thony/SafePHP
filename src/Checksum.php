<?php

namespace SafePHP;

use Dompdf\Options;
use ErrorException;
use Exception;
use SafePHP\GLOBALS\Globals;
use Dompdf\Dompdf;


class Checksum
{

    public static function createCheckSum($file): bool|string
    {
        return hash_file("SHA256", $file, false);
    }

    public static function hasChanged($checksumfilename): bool
    {
        if (self::exist($checksumfilename) === true) {
            $fileContent = file_get_contents(Globals::$checksumDir . $checksumfilename . ".json");
            $file = json_decode($fileContent, true);
            $oldChecksum = $file["checksum"];
            $realPath = $file["Path"];
            $actualChecksum = self::createCheckSum($realPath);
            if ($actualChecksum !== $oldChecksum) {
                throw new ErrorException("Both are not the same ! \nChecksum of the file : " . $oldChecksum . "\nNew checksum :" . $actualChecksum);
            } else {
                return false;
            }
        } else {
            throw new ErrorException("This file does not exist in Checksum folder!");
        }
    }

    public static function exist(string $filename): bool
    {
        $checksumFolder = Globals::$checksumDir;
        $listOfChecksum = scandir($checksumFolder);
        $fullFileName = $filename . ".json";
        if ($listOfChecksum === false) {
            return false;
        } else {
            if (in_array($fullFileName, $listOfChecksum)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function addToChecksum($file, string $name): bool|int
    {
        date_default_timezone_set("UTC");
        $checksumExtension = ".json";
        $checkSumDir = Globals::$checksumDir;
        $hashFile = self::createCheckSum($file);
        if ($hashFile === false) {
            throw new ErrorException("The file specified was not found !");
        }

        $newFile = ["name" => $name, "Path" => $file, "checksum" => $hashFile, "last_time_hashed" => date(DATE_RFC2822)];
        $jsonFileContent = json_encode($newFile, JSON_PRETTY_PRINT);
        if (self::exist($name)) {
            throw new ErrorException("This file name is already taken, please use another one !");
        } else {
            return file_put_contents($checkSumDir . $name . $checksumExtension, $jsonFileContent);
        }
    }

    public static function createSummary()
    {
        $checksumDir = Globals::$checksumDir;
        $listOfChecksumFiles = scandir($checksumDir);

        // Nettoyage du buffer si nécessaire
        if (ob_get_length()) {
            ob_end_clean();
        }

        ob_start();
        ?>
        <html>

        <body>
            <style>
                table {
                    border: 2px solid rgb(140 140 140);
                    font-family: sans-serif;
                    text-align: start;
                }
                
                th, td {
                    border: 1px solid rgb(160 160 160);
                    max-width: 300px;
                    text-rendering: auto;
                    overflow-wrap: break-word;
                }

                tbody>tr:nth-of-type(even) {
                    background-color: rgb(237 238 242);
                }
            </style>
            <h1>Summary of cheksum files</h1>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Path</th>
                        <th>Value</th>
                        <th>Last change</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listOfChecksumFiles as $file) {
                        //We ignore the "." and ".." path (two firsts paths found with  scandir function)
                        if ($file === "." | $file === "..") {
                            continue;
                        }
                        $fileContent = json_decode(file_get_contents(Globals::$checksumDir . $file), true);
                        ?>
                        <tr>
                            <td><?= $fileContent["name"]; ?></td>
                            <td><?= $fileContent["Path"]; ?></td>
                            <td><?= $fileContent["checksum"]; ?></td>
                            <td><?= $fileContent["last_time_hashed"] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </body>

        </html>
        <?php

        $html = ob_get_clean();

        // Creation of PDF file
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $options = new Options();
        $options->set("defaultFont", "DejaVuSerif");

        $dompdf->setPaper("A4", "landscape");
        $dompdf->render();
        $dompdf->stream("Test", ["Attachment" => 0]);
        
        /* Create a new pdf file in your root project directory
        $pdfile = $dompdf->output();
        file_put_contents("Summary_of_checksum.pdf", $pdfile);
        */
        exit();
    }
}