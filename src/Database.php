<?php
namespace SafePHP;
use Exception;
use PDO;
use PDOException;
use PDOStatement;
use SafePHP\Secret;
/**
 * Interaction in SQL with safe statements
 */
class Database {
    /**
     * Connexion to the database with secrets keys aviables on .env file
     * @return PDO object to manipulate SQL
     */
    public static function connectDatabase() : PDO {
        $secret = new Secret(__DIR__);
        $secret->getEnv();
        $host = $_ENV["HOST"];
        $port = $_ENV["PORT"];
        $dbname = $_ENV["DB_NAME"];
        $user_name = $_ENV["USER_NAME"];
        $password = $_ENV["PASSWORD_DB"];
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8; port=$port", $user_name, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Error by login to database : " . $e->getMessage());
        }
    }

    /**
     * Create SQL request prepared to avoid injection, don't forget to add verify &/or sanitize functions for more safety
     * @param string $query request to forge
     * @return bool|PDOStatement return prepared SQL request
     */
    public static function InsertSQL(string $query) : bool | PDOStatement {
        $connexion = self::connectDatabase();
        return $connexion->prepare($query);
    }

    /**
     * Return an array with binded parameters for each value of the SQL request
     * @param array $params The array of value and parameter by key
     * @example [":mail" => $mail, "string"] Assuming that you have an SQL request with an email parameter
     * @throws Exception Every value must be typed
     * @return array<int|mixed>[] List of binded value
     */
    public static function setParams(array $params) : array{
        $paramsBind = [];
        foreach($params as $key => [$value, $type]){
            switch($type){
                case "string":
                    $paramsBind[$key] = [$value, PDO::PARAM_STR];
                    break;
                
                case "int":
                    $paramsBind[$key] = [$value, PDO::PARAM_INT];
                    break;

                case "bool":
                    $paramsBind[$key] = [$value, PDO::PARAM_BOOL];
                    break;

                case "lob":
                    $paramsBind[$key] = [$value, PDO::PARAM_LOB];
                    break;

                case "null":
                    $paramsBind[$key] = [$value, PDO::PARAM_NULL];
                    break;

                default:
                throw new Exception("Each value must be typed !");
            }
        }

        return $paramsBind;
    }

    /**
     * Summary of executeSQL
     * @param string $request Your SQL request
     * @param array $params // Array as : [":key_value" => [$value, typeof]], it's fortly recommanded to use the setParams function
     * @throws Exception
     * @return bool State of SQL request execution, true is succes, false if failed
     */
    public static function executeSQL(string $request, array $params) : bool {
        try {
            $sqlRequest = self::InsertSQL($request);
            foreach($params as $key=>[$value, $bind]){
                $sqlRequest->bindValue($key, $value, $bind);
            }
            return $sqlRequest->execute();
        } catch (Exception $e){
            throw new Exception("An error has been detected while executing SQL request :" . $e->getMessage());
        }
    }
}