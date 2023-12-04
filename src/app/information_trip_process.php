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
  </div>
<?php
    //Création du point d'arrêt associé
    $duree = $_POST["duree"];
    $dist = $_POST["distance_trajet"];
    $prix = $_POST["prix"];


    $requete_id_pt_arret = "SELECT MAX(id_point_arret) FROM Points_arret";
    $res = pg_query($connection, $requete_id_pt_arret);
    if($res){
      $b = pg_fetch_assoc($res);
      $id_pt_arret = $b["max"] + 1;

    echo "idpointarret".$id_pt_arret."\n";
    }
else {
  echo "<p> Erreur lors de la récupération de l'id du point d'arrêt</p>";
}


    $requete_trj = "SELECT id_trajet, ville_depart FROM Trajets ORDER BY id_trajet DESC LIMIT 1";
    $res_trj = pg_query($connection, $requete_trj);
    if($res_trj){
      $c = pg_fetch_assoc($res_trj);
      $id_trj = $c["id_trajet"];
      $ville_depart = $c["ville_depart"];
    }
    else {
      echo "<p> Erreur lors de la récupération de l'id du trajet et de la ville de départ.</p>";
    }
    pg_free_result($res_trj);

    $depuis_vers = $_SESSION["depuis_vers"];
    if ($depuis_vers == 'depuis_campus'){
      $ville = $_SESSION["ville"];
    }
    else {
      $ville = $ville_depart;
    }

    $requete = "INSERT INTO Points_arret(ID_point_arret, ville_arret, duree_trajet, distance_trajet, prix_par_passager, statut_arret, id_trajet) 
    VALUES ('$id_pt_arret', '$ville', '$duree', '$dist', '$prix', 'TRUE', '$id_trj')";
    $res = pg_query($connection, $requete);
    if (!$res){
        echo "<p>Erreur lors de la création du point d'arrêt</p>"."\n";
    }
    pg_free_result($res);
    header("Location:confirmation.php");

?>

</body>
</html>