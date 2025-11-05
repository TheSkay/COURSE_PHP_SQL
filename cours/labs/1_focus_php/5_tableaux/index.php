<?php
// Exemple de tableaux indexés et associatifs

// Tableau indexé
$notes = [16, 7];
echo "Note 1 : " . $notes[0] . PHP_EOL;
echo "Note 2 : " . $notes[1] . "\n";
echo "Nombre d'élève évalué : " . count($notes) . PHP_EOL;

// Tableau associatif
$etudiant = [
    "nom" => "Alice",
    "age" => 25,
    "filiere" => "Informatique",
    "tension" => 12
];

echo "Nom : " . $etudiant["nom"] . "\n";
echo "Filière : " . $etudiant["filiere"] . "\n";

// Ajouter un élément
$etudiant["ville"] = "Paris";
$etudiant["type"] = false ;
var_dump($etudiant);