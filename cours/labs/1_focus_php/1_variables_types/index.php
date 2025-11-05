<?php
// Exemple d'utilisation des variables et des types en PHP

// Déclaration de variables
$nom = "Alice";              // string
$age = 25;                   // integer
$taille = 1.68;              // float
$estEtudiant = true;         // boolean

// Affichage des variables
echo "Nom : $nom\n";
echo "Age : " . $age . PHP_EOL;
echo "Taille : " . $taille . " m\n";
echo "Est étudiant : " . ($estEtudiant ? "Oui" : "Non") . "\n";

// Affichage du type de chaque variable
var_dump($nom);
var_dump($age);
var_dump($taille);
var_dump($estEtudiant);
