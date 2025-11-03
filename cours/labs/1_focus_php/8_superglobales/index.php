<?php
// Exemple d'utilisation des superglobales $_GET

// Simuler une requête GET : php superglobales.php nom=Alice
$nom = $_GET['nom'] ?? 'Invité';  // Si aucune valeur, 'Invité'
echo "Bonjour, $nom\n";