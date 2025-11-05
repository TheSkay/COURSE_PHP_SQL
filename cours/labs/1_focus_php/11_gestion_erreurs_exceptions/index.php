<?php
// Exemple de gestion des erreurs et exceptions

function division($a, $b) {
    if ($b == 0) {
        throw new Exception("Division par zéro impossible !");
    }
    return $a / $b;
}

try {
    echo division(10, 0) . "\n"; // déclenche une exception
    echo division(10, 2) . "\n"; // 5
}
catch( DivisionByZeroError $e ){
    echo "Erreur native" . PHP_EOL;
}
catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
