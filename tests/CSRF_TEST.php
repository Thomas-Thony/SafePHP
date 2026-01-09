<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SafePHP\CSRF;
use SafePHP\Form;

session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafePHP | Test CSRF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .result {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .success {
            color: green;
            font-size: 24px;
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 24px;
            font-weight: bold;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="test-csrf">
    <form action="" method="POST">
        <?php CSRF::createCSRF();?>
        
        <input type="text" name="text_test" id="text_test" placeholder="Votre texte...">

        <button type="submit" onclick="<?php ?>">
            Envoyer
        </button>
    </form>
</div>

</body>
</html>