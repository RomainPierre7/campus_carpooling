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
    <a href="offer.php"><button>Proposer un trajet</button></a>
  </div>
  <p>En laissant la date non renseignée, la recherche se fera sur tous les trajets disponibles à compter d'aujourd'hui.</p>
  <div class="form">
    <form action="search_process.php" method="post">
      <label for="selecteur">Direction :</label>
      <select name="selecteur" id="selecteur">
        <option value="depuis_campus">Depuis Campus</option>
        <option value="vers_campus">Vers Campus</option>
      </select><br><br>

      <label for="ville">Ville :</label>
      <input type="text" name="ville" id="ville" required><br><br>

      <label for="date_depart">Date de départ :</label>
      <input type="date" name="date_depart" id="date_depart" placeholder="Sélectionnez une date"><br><br>

      <input type="submit" name="submit" value="Rechercher">
    </form>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>