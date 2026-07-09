<?php

use SafePHP\POW;

/**
 * Small helper to print a titled section in the console output
 * @param string $title section title
 * @return void
 */
function section(string $title): void
{
    echo "\n=== $title ===\n";
}

// ------------------------------------------------------------------
// Test 1 : constructeur avec une difficulté invalide (< 1)
// ------------------------------------------------------------------
section("Test 1 : difficulté invalide dans le constructeur");
try {
    new POW(0);
    echo "❌ Échec : aucune exception levée alors qu'une RangeException était attendue.\n";
} catch (RangeException $e) {
    echo "✅ RangeException correctement levée : {$e->getMessage()}\n";
} catch (Throwable $e) {
    echo "⚠️ Exception inattendue : " . get_class($e) . " - {$e->getMessage()}\n";
}

// ------------------------------------------------------------------
// Test 2 : constructeur avec une difficulté valide + generateProblem()
// ------------------------------------------------------------------
section("Test 2 : génération d'un challenge");
try {
    $pow = new POW(3);
    $challenge = $pow->generateProblem();
    echo "Challenge généré : $challenge\n";
    echo "Longueur attendue : " . (3 * 2) . " caractères hexa (3 bytes)\n";
    echo "Longueur obtenue  : " . strlen($challenge) . " caractères\n";
} catch (Throwable $e) {
    echo "⚠️ Exception inattendue : " . get_class($e) . " - {$e->getMessage()}\n";
}

// ------------------------------------------------------------------
// Test 3 : hashBasedPOW() pour différentes difficultés (mesure du temps)
// ------------------------------------------------------------------
section("Test 3 : hashBasedPOW() sur plusieurs difficultés");

$difficulties = [1, 2, 3, 4, 5];

foreach ($difficulties as $difficulty) {
    try {
        $pow = new POW($difficulty);
        $start = microtime(true);
        $pow->hashBasedPOW($difficulty);
        $elapsed = microtime(true) - $start;

        echo "Difficulté $difficulty : ✅ résolu en " . round($elapsed, 4) . " s\n";
    } catch (RuntimeException $e) {
        echo "Difficulté $difficulty : ❌ RuntimeException - {$e->getMessage()}\n";
    } catch (Throwable $e) {
        echo "Difficulté $difficulty : ⚠️ Exception inattendue - " . get_class($e) . " - {$e->getMessage()}\n";
    }
}

section("Fin des tests");