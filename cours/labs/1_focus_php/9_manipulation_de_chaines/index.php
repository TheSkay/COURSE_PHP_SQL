<?php
// Exemple de manipulation de chaînes

$texte = "Bonjour le monde";

// Longueur de la chaîne
echo "Longueur : " . strlen($texte) . PHP_EOL;

// Mettre en majuscule ou minuscule
echo "Majuscule : " . strtoupper($texte) . PHP_EOL;
echo "Minuscule : " . strtolower($texte) . PHP_EOL;

// Substring
echo "Sous-chaîne : " . substr($texte, 8, 5) . PHP_EOL; // "le mo"

// Remplacer du texte
echo "Remplacement : " . str_replace("monde", "PHP", $texte) . PHP_EOL;
