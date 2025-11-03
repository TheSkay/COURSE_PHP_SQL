<?php
// Exemple de boucles for, while et foreach

// Boucle for
for ($i = 1; $i <= 5; $i++) {
    echo "Boucle for : $i\n";
}

// Boucle while
$j = 1;
while ($j <= 5) {
    echo "Boucle while : $j\n";
    $j++;
}

// Boucle foreach sur un tableau
$fruits = ["pomme", "banane", "cerise"];
foreach ($fruits as $fruit) {
    echo "Fruit : $fruit\n";
}

// Boucle do/while
$num = 0;
$canBreak = false ;
do {
    echo "Un tour...";
    echo "num = " . ++$num . PHP_EOL ;
    if( $num == 5 ){
        $canBreak = true ;
        print "On intervertit l'indicateur..." . PHP_EOL;
    }

} while( !$canBreak );