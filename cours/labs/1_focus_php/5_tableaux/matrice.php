<?php
// Exemple d'une matrice (tableau à deux dimensions)
$matrice = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];

// Affichage de la matrice ligne par ligne
echo "Affichage de la matrice :\n";
foreach ($matrice as $ligne) {
    foreach ($ligne as $valeur) {
        echo $valeur . " "; // Affiche chaque élément séparé par un espace
    }
    echo PHP_EOL ; // Nouvelle ligne après chaque ligne de la matrice
}

// Somme de tous les éléments
$sommetotal = 0;
foreach ($matrice as $ligne) {
    foreach ($ligne as $valeur) {
        $sommetotal += $valeur;
    }
}
echo "Somme totale des éléments : $sommetotal\n";

// Exemple d'accès à un élément spécifique (ligne 2, colonne 3)
echo "Élément ligne 2, colonne 3 : " . $matrice[1][2] . "\n"; // Attention, les indices commencent à 0
