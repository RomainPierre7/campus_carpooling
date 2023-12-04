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
      $requete = "SELECT prenom, nom, id_etudiant FROM etudiants WHERE id_etudiant = ".$_SESSION["selected_user"];
      $res = pg_query($connection, $requete);
      if($res) {
        $etudiant = pg_fetch_assoc($res);
        $prenom = $etudiant['prenom'];
        $nom = $etudiant['nom'];
        $id = $etudiant['id_etudiant'];
        echo "<p>connecté en tant que $prenom $nom</p>";
      }
    }
?>

  <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>
  <div class="form">
    <form action="information_trip_process.php" method="post">

      <label for="duree">Durée du trajet (min) :</label>
      <input type="number" name="duree" id="duree" required><br><br>

      <label for="distance_trajet">Distance du trajet :</label>
      <input type="number" name="distance_trajet" id="distance_trajet" required><br><br>

      <label for="prix">Prix intial du trajet :</label>
      <input type="number" name="prix" id="prix" required><br><br>

      <p></p>

      <input type="submit" name="submit" value="Soumettre" required>
    </form>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
