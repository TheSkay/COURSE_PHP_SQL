<?php
// Exemple de tableaux indexés et associatifs

// Tableau indexé
$notes = [12, 15, 18];
echo "Note 1 : " . $notes[0] . PHP_EOL;
echo "Note 2 : " . $notes[1] . "\n";

// Tableau associatif
$etudiant = [
    "nom" => "Alice",
    "age" => 25,
    "filiere" => "Informatique",
    12 => "Etudiant" // Mauvaise idée...
];

echo "Nom : " . $etudiant["nom"] . "\n";
echo "Filière : " . $etudiant["filiere"] . "\n";

// Ajouter un élément
$etudiant["ville"] = "Paris";
var_dump($etudiant);