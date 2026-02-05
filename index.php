<?php
require_once __DIR__ . '/vendor/autoload.php';

use SafePHP\Session;

Session::checkLastActivity();
Session::checkTempPerms();

if (!isset($_SESSION['user_id'])) {
    $session = new Session("478944784fzsdfz7f4ez89f", 'Thomas', 2);
    $_SESSION["session"] = $session;
} else {
    $session = $_SESSION["session"];
}

require_once "./config/router.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/main.css">
    <link rel="stylesheet" href="./styles/footer.css">
    <link rel="stylesheet" href="
        <?php
        if (isset($ressourceCSS)) {
            echo $ressourceCSS;
        } else {
            echo "";
        }
        ?>
    ">
    <script src="
        <?php
            if(isset($ressourceJS)) {
                echo $ressourceJS;
            } else {
                echo "";
            }
        ?>">
    </script>
    <title>
        <?php
            if(isset($title)) {
                echo $title;
            } else {
                echo "SafePHP | Accueil";
            }
        ?>
    </title>
</head>
<header>
    <?php
    include_once "./Components/navbar.php";
    ?>
</header>

<body>
    <section>
        <?php
            if(isset($content)) {
                include_once $content;
            }
        ?>
    </section>
</body>

<footer>
    <?php
    include_once "./Components/footer.php";
    ?>
</footer>
</html>