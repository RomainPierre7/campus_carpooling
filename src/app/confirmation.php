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
<p id="confirmation">
    Votre trajet à été proposé aux autres étudiants.
</p>
<div class="menu">
    <a href="add_stop_point.php"><div class="connect_button"><button>Ajouter un point d'arrêt</button></div></a>
    <a href="../index.php"><button>Retour à l'accueil</button></a>
  </div>
</body>
</html>