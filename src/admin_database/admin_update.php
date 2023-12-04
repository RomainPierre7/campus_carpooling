<html>
<head>
  <title>Covoiturage du Campus</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="icon" href="../media/logo.png" type="image/x-icon">
</head>
<body>
  <?php
  include "../template/header.php";
  include "../connect_database/connect_pg.php";

  echo "<h2>Mise à jour de la base de données de test</h2>";
  echo "<div class='container'>";

  $sqlScript = file_get_contents('../sql/drop.sql');
  $result = pg_query($connection, $sqlScript);
  if ($result) {
    echo "Script drop.sql exécuté avec succès";
  } else {
    echo "Erreur lors de l'exécution du script drop.sql : " . pg_last_error($connection);
  }
  echo "<br>";

  $sqlScript = file_get_contents('../sql/create.sql');
  $result = pg_query($connection, $sqlScript);
  if ($result) {
    echo "Script create.sql exécuté avec succès";
  } else {
    echo "Erreur lors de l'exécution du script create.sql : " . pg_last_error($connection);
  }
  echo "<br>";

  $sqlScript = file_get_contents('../sql/insert.sql');
  $result = pg_query($connection, $sqlScript);
  if ($result) {
    echo "Script insert.sql exécuté avec succès";
  } else {
    echo "Erreur lors de l'exécution du script insert.sql : " . pg_last_error($connection);
  }
  echo "<br>";

  $sqlScript = file_get_contents('../sql/update.sql');
  $result = pg_query($connection, $sqlScript);
  if ($result) {
    echo "Script update.sql exécuté avec succès";
  } else {
    echo "Erreur lors de l'exécution du script update.sql : " . pg_last_error($connection);
  }

  pg_close($connection);

  echo "</div>";  
?>

<div class="menu-dev">
  <a href="../index.php"><button>Retour à l'accueil</button></a>
</div>

</body>
</html>