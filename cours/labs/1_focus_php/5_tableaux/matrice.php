<?php
// Exemple d'une matrice (tableau à deux dimensions)
$matrice = [
    [1, 2, 3, 1.5],
    [4, 5, 6, "y"],
    [7, 8, 9, false],
    ["x", "x", "x"]
];

// Affichage de la matrice ligne par ligne
echo "Affichage de la matrice :\n";
for ($i = 0; $i < count($matrice); $i++) {
    for ($j = 0; $j < count($matrice[$i]); $j++) {
        $valeur = $matrice[$i][$j];
        echo $valeur . " "; // Affiche chaque élément séparé par un espace
    }
    echo PHP_EOL ; // Nouvelle ligne après chaque ligne de la matrice
}

// Somme de tous les éléments
$sommeTotal = 0;
foreach ($matrice as $ligne) {
    foreach ($ligne as $case) {
        $sommetotal = $sommeTotal + $case;
        echo "Variable : $case -> " . gettype($case) . PHP_EOL ;
        if( gettype($case) == "integer" || gettype($case) == "double" ){
            if( ($case%2) == 0 ){
                $sommeTotal += $case;
            } else {
                $sommeTotal -= $case;
            }
        }
    }
}
// $sommeTotal = $sommeTotal * -1 ;
// $sommeTotal *= -1 ;

echo "Somme totale des éléments : $sommeTotal\n";

// // Exemple d'accès à un élément spécifique (ligne 2, colonne 3)
echo "Élément ligne 2, colonne 3 : " . $matrice[1][2] . "\n"; // Attention, les indices commencent à 0
