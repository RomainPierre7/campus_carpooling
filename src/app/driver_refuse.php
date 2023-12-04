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
        $id_etu = $_SESSION["selected_user"];
        echo "<p>connecté en tant que $prenom $nom</p>";
      }
    }
  ?>
<div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
  </div>
  <?php
    if (isset($_SESSION["etu_id"]) && isset($_SESSION["id_point_arret"]) && isset($_SESSION["id_trj"])){
        $etu_id = $_SESSION["etu_id"];
        $id_pa = $_SESSION["id_point_arret"];
        $id_trj = $_SESSION["id_trj"];
        $requete = "UPDATE inscriptions i SET statut_inscription = 'FALSE' 
        WHERE i.id_etudiant = $etu_id 
        AND i.id_point_arret = $id_pa 
        AND i.id_trajet = $id_trj"; 
        $res = pg_query($connection, $requete);
        if ($res){
            echo '<p> le refus d\'inscription de l\'étudiant a été validée.</p>';
        }
        else {
          echo '<p> Echec de la validation de la demande.</p>';
        }
        pg_free_result($res);    
}
    else{
        echo '<p>Erreur lors de la validation de la demande. </p>';
    }
?>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
