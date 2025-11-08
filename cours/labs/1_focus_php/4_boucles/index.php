<?php
// Exemple de boucles for, while, foreach et do/whime

// Boucle for
for ($i = 0; $i <= 5; $i += 2) {
    echo "Boucle for : $i\n";
}

// Boucle while
$j = 0;
while ($j <= 5) {
    echo "Boucle while : $j\n";
    $j += 2;
}

// Boucle foreach sur un tableau
$fruits = ["pomme", "banane", "cerise", "bmw x5"];
foreach ($fruits as $element) {
    echo "Fruit : $element\n";
}

// // Boucle do/while
$num = 0;
$canBreak = false ;
do {
    echo "Un tour...";
    echo "num = " . ++$num . PHP_EOL ;
    if( $num == 5 ){
        print "On intervertit l'indicateur..." . PHP_EOL;
        break ;
    }
} while( true );

for( $x = 0; $x <= 10; $x++ ){

    if( $x == 0 || ($x % 2) == 1 ){
        continue ;
    }

    echo "Tour n°$x" ;

    if( $x != 4 ){
        echo " - Bingo !" . PHP_EOL;
    } else {
        echo PHP_EOL ;
    }

    if( $x == 6 ){
        break ;
    }
}