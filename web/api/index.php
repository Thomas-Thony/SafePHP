<?php
// Récupère le fichier avec toutes les inclusions (équivalent au header dans un fichier c++)
require_once "./requirements.php";

// CORS headers si nécessaire (pour Rested ou autres clients)
new HeaderHttpHandler();

use Dotenv\Dotenv;
use SafePHP\Database;

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD']; //On récupère la méthode envoyée par le client
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $path);

//On récupère les variables d'environnement avec le chemin relatif jusqu'au fichier (sans le nom ni l'extension de ce derner, la librairie se charge du reste)
/*
$dotenv = Dotenv::createImmutable("./"); //Indique ou se trouve le fichier d'environnement
$dotenv->load(); // Indispensable pour récupérer les valeurs
*/

$pdo = Database::connectDatabase(); //Objet PDO pour accéder à la BDD
$apiMessage = new ApiMessage(); //Gestionnaire de messages de l'API en cas d'erreurs
$resultat = null; //Valeur par défaut affichée par l'API

if (isset($segments[3])) {
    // TODO : Remplacer $segments[i] par les variables définies ci dessous...
    $mainPath = $segments[3] ?? null; // Valeur de l'url
    $subPath = $segments[4] ?? null; // Valeur de l'url
    $subSubPath = $subSubPath ?? null; // Valeur de l'url

    if ($mainPath === "admin" && $method === "POST") {
        $data = json_decode(file_get_contents('php://input'), true);
        $resultat = tryLoginAdmin($pdo, $data);
    }
}

#region IMPORTANT: Gérer le cas où aucune route ne corresponds
if ($resultat === null) {
    // Si on cherche un chemin non existant
    if (isset($segments[3])) {
        $httpResponse = new HttpHandler(404, "Route non trouvée");
        $resultat = $httpResponse->sendHttpResponse();
    } else {
        // Si on est sur le chemin principal (route racine '/')
        $resultat = mainRoute();
    }
}
#endregion
echo $resultat;