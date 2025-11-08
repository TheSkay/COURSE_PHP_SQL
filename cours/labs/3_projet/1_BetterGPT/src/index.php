<?php
$response = file_get_contents('http://host.docker.internal:3000/api/models');
$models = json_decode($response, true);
var_dump($models);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Interface API PHP ↔ Node</title>
</head>
<body>
  <h1>Modèles disponibles</h1>
  <ul>
    <?php foreach ($models as $m): ?>
      <li><?= htmlspecialchars($m['name'] ?? 'inconnu') ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
