<?php
// Exemple de lecture et écriture de fichiers

$nomFichier = "exemple.txt";

// Écriture dans le fichier
file_put_contents($nomFichier, "Bonjour depuis PHP !\n", FILE_APPEND);

// Lecture du fichier
$contenu = file_get_contents($nomFichier);
echo "Contenu du fichier :\n$contenu";
