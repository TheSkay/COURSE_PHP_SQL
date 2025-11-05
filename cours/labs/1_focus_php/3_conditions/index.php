<?php
// Exemple d'utilisation des conditions if, else, elseif

$note = 16.5;

// Condition simple
if ($note >= 10) {
    echo "Vous avez réussi l'examen.\n";
} else {
    echo "Vous avez échoué.\n";
}

// Condition avec elseif
if ($note >= 16) {
    echo "Mention très bien\n";
} elseif ($note >= 14) {
    echo "Mention bien\n";
} elseif ($note >= 12) {
    echo "Mention assez bien\n";
} else {
    echo "Pas de mention\n";
}
