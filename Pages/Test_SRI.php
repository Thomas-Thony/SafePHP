<?php
require_once __DIR__ . '/../vendor/autoload.php';
use SafePHP\SRI;

$CSSFile = "./styles/main.css";
$JSFile = "./JS/default.js";

echo htmlspecialchars(SRI::createSRI("css", $CSSFile));
echo "<br>";
echo htmlspecialchars(SRI::createSRI("js", $JSFile));