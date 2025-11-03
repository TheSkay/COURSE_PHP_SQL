<?php

declare(strict_types=0);

// Exemple de fonctions

// Fonction sans paramètre
function direBonjour() {
    echo "Bonjour !\n";
}

// Fonction avec paramètres
function addition(int $a, int $b) {
    return $a + $b;
}

function addition_all1() {
    $args = func_get_args(); // récupère tous les arguments
    return array_sum($args); // fait la somme de tous les éléments
}

// avec le splat operator ... (PHP 5.6+)
function addition_all2(...$args) {
    return array_sum($args);
}

// Appel des fonctions
direBonjour();
$resultat = addition(5, 7);
echo "Résultat de l'addition 1 : $resultat" . PHP_EOL;

echo "Résultat de l'addition 2 : " . addition(5.9, 7.1) . PHP_EOL;

echo "Résultat de l'addition 3 : " . addition_all1(1, 2, 3, 4) . PHP_EOL; // affiche 10
echo "Résultat de l'addition 4 : " . addition_all2(4, 2, 1, 5) . PHP_EOL; // affiche 12
