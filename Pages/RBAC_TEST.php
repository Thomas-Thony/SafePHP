<?php
require_once __DIR__ . '/../vendor/autoload.php';

use SafePHP\RBAC;

$userRole = new RBAC(0);

$lesPermsUtilisateur = $userRole->getPermsUser();

echo "Permissions de l'utilisateur :\n";
foreach ($lesPermsUtilisateur as $permUtilisateur) {
    echo "-" . $permUtilisateur . "\n";
}

?>
<br>
<?php

$allPerms = $userRole->getAllPerms();

foreach ($allPerms as $key => $aPermUtilisateur) {
    echo $key . " : ";
    echo implode(", ", $aPermUtilisateur) . "\n";
    echo "<br>";
}