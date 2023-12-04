<html>
<head>
  <meta charset="UTF-8">
  <title>Covoiturage du Campus</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="icon" href="../media/logo.png" type="image/x-icon">
</head>
<body>
<?php
  include "../template/header.php";
  include "../connect_database/connect_pg.php";
  session_start();
  if (isset($_SESSION["selected_user"])) {
      $requete = "SELECT prenom, nom FROM etudiants WHERE id_etudiant = ".$_SESSION["selected_user"];
      $res = pg_query($connection, $requete);
      if($res) {
        $etudiant = pg_fetch_assoc($res);
        $prenom = $etudiant['prenom'];
        $nom = $etudiant['nom'];
        echo "<p>connecté en tant que $prenom $nom</p>";
      }
    }
  ?>
  <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>
  <?php
if (isset($_SESSION["selected_user"])) {
  echo "<h2>Ajouter un point d'arrêt</h2>";
  echo '<div class="form">';
  echo '<form action="add_stop_point_process.php" method="post">';

  echo '<label for="ville">Ville :</label>';
  echo '<input type="text" name="ville" id="ville" required><br><br>';

  echo '<label for="duree">Durée du trajet (min): </label>';
  echo '<input type="number" name="duree" id="duree" required><br><br>';
  echo '<label for="distance_trajet">Distance du trajet : </label>';
  echo '<input type="number" name="distance_trajet" id="distance_trajet" required><br><br>';
  echo '<label for="prix">Prix intial du trajet : </label>';
  echo '<input type="number" name="prix" id="prix" required><br><br>';


  echo '<p></p>';

  echo '<input type="submit" name="submit" value="Proposer" required>';
  echo '</form>';
  echo '</div>';
} else {
  echo '<h2>Vous devez être connecté pour proposer un trajet.</h2>';
  echo '<a href="../user/log.php"><div class="connect_button"><button>Se connecter</button></div></a>';
}
?>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>