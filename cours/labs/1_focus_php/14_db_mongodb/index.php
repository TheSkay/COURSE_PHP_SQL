<?php
// Exemple de connexion à MongoDB avec PHP

require 'vendor/autoload.php'; // Assurez-vous que l'extension MongoDB est installée

// use MongoDB\Client; // on importe la classe Client

$client = new MongoDB\Client("mongodb://univ_mongodb:27017");
$collection = $client->testdb->etudiants;

// Insérer un document
$insertResult = $collection->insertOne([
    'nom' => 'Alice',
    'age' => 25
]);

echo "Document inséré avec l'id : " . $insertResult->getInsertedId() . "\n";

// Lire les documents
$documents = $collection->find();
foreach ($documents as $doc) {
    print_r($doc);
}
