<?php
// Connexion à PostgreSQL avec PDO
$host = 'univ_postgres';  // Nom du service PostgreSQL dans Docker
$db   = 'exemple_db';
$user = 'username';
$pass = 'password';
$port = "5432";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass);

    echo "Connexion réussie\n\n";

    // 1. Tous les étudiants
    $stmt = $pdo->query("SELECT * FROM etudiants");
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "--- Tous les étudiants ---\n";
    foreach ($etudiants as $e) {
        echo "ID: {$e['id']}, Nom: {$e['nom']}, Age: {$e['age']}, Ville: {$e['ville']}\n";
    }

    // 2. Age moyen
    $stmt = $pdo->query("SELECT AVG(age) AS age_moyen FROM etudiants");
    $age_moyen = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nAge moyen v1 : " . number_format($age_moyen['age_moyen'], 2) ;
    echo "\nAge moyen v2 : " . sprintf("%.2f", $age_moyen['age_moyen']) . PHP_EOL ;

    // 3. Age maximum
    $stmt = $pdo->query("SELECT MAX(age) AS age_max FROM etudiants");
    $age_max = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Age maximum : {$age_max['age_max']}\n";

    // 4. Age minimum
    $stmt = $pdo->query("SELECT MIN(age) AS age_min FROM etudiants");
    $age_min = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Age minimum : {$age_min['age_min']}\n";

    // 5. Étudiants mineurs (moins de 18 ans)
    $stmt = $pdo->query("SELECT * FROM etudiants WHERE age < 18");
    $mineurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\n--- Étudiants mineurs ---\n";
    foreach ($mineurs as $m) {
        echo "ID: {$m['id']}, Nom: {$m['nom']}, Age: {$m['age']}\n";
    }

    // 6. Compter le nombre d'étudiants par ville
    $stmt = $pdo->query("SELECT ville, COUNT(*) AS nb_etudiants FROM etudiants GROUP BY ville");
    $stats_ville = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\n--- Nombre d'étudiants par ville ---\n";
    foreach ($stats_ville as $s) {
        echo "Ville: {$s['ville']}, Nombre: {$s['nb_etudiants']}\n";
    }

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage() . "\n";
}
