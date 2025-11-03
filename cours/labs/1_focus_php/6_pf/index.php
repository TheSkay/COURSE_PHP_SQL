<?php
$notes = [12, 8, 15, 7, 18];

// -------------------------
// Exemple "some" (au moins un élément satisfait la condition)
// -------------------------
$on_au_moins_la_moyenne = array_filter($notes, fn($note) => $note >= 10);
$au_moins_un_reussi = count($on_au_moins_la_moyenne) > 0;
var_dump($on_au_moins_la_moyenne); 
var_dump($au_moins_un_reussi); // true

// -------------------------
// Exemple "every" / "all" (tous les éléments satisfont la condition)
// -------------------------
$en_dessous_de_la_moyenne = array_filter($notes, fn($note) => $note < 10);
$toutes_reussites = count($en_dessous_de_la_moyenne) === 0;
var_dump($en_dessous_de_la_moyenne); 
var_dump($toutes_reussites); // false

// -------------------------
// Exemple "map" (transformer chaque élément)
// -------------------------
$notes_doublees = array_map(fn($note) => $note * 2, $notes);
print_r($notes_doublees); // [24, 16, 30, 14, 36]

// -------------------------
// Exemple "reduce" (réduire à une valeur unique)
// -------------------------
$somme_notes = array_reduce($notes, fn($acc, $note) => $acc + $note, 0);
echo "Somme des notes : $somme_notes\n"; // 60

// -------------------------
// Exemple "filter" (filtrer les éléments)
// -------------------------
$notes_reussites = array_filter($notes, fn($note) => $note >= 10);
print_r($notes_reussites); // [12, 15, 18]
