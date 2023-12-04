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
  </div>
  <div class="form">
    <form action="register_car_process.php" method="post">

      <label for="immatriculation">Plaque d'immatriculation :</label>
      <input type="text" name="immatriculation" id="immatriculation" required><br><br>

      <label for="type">Type de voiture : (20 caractères maximum)</label>
      <input type="text" name="type" id="type" required><br><br>

      <label for="nb_place">Nombre de place :</label>
      <input type="number" name="nb_place" id="nb_place" required><br><br>

      <label for="couleur">Couleur de la voiture :</label>
      <input type="text" name="couleur" id="couleur" required><br><br>

      <label for="etat">Etat de la voiture :</label>
      <input type="text" name="etat" id="etat" required><br><br>

      <label for="divers">Informations diverses à propos de la voiture : (optionnel, 200 caractères maximum)</label>
      <input type="text" name="divers" id="divers" ><br><br>

      <p></p>
      <input type="submit" name="submit" value="Soumettre">
    </form>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
