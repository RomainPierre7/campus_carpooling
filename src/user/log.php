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
  ?>
   <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="../app/search.php"><button>Rechercher un trajet</button></a>
  </div>

  <h2>Choisissez un utilisateur pour vous connecter :</h2>
  <div class="container">
    <div class="form">
  <form action="login.php" method="POST">
  <?php
    $requete = "SELECT id_etudiant, prenom, nom FROM etudiants;";
    $res = pg_query($connection, $requete);
    if($res) {
      echo '<select name="selected_user">';
      while ($etudiant = pg_fetch_assoc($res)) {
        echo '<option value='.$etudiant["id_etudiant"].'>'.$etudiant["prenom"]." ".$etudiant["nom"].'</option>';
      }
    }
    echo '</select>';
    echo '<br>';
    echo '<input type="submit" value="Sélectionner">';
  ?>
    </form>
    </div>
    </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>