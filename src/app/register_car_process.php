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
    $imm = $_POST["immatriculation"];
    $type = $_POST["type"];
    $nb_place = $_POST["nb_place"];
    $couleur = $_POST["couleur"];
    $etat = $_POST["etat"];
    $divers = $_POST["divers"];
    
    $req_id = "SELECT MAX(id_etudiant) FROM etudiants";
    $res_id = pg_query($connection, $req_id);
    if($res_id){
        $a = pg_fetch_assoc($res_id);
        $id_etu = $a["max"];
    }

    $requete = "INSERT INTO Voitures(immatriculation, type_voiture, couleur, nombre_de_places, etat, divers, ID_etudiant)
    VALUES  ('$imm', '$type', '$couleur', '$nb_place', '$etat', '$divers', '$id_etu')";
    $res = pg_query($connection, $requete);
    if(!$res)
    {
        echo "<p> Echec de l'inscription de la voiture.</p>";
    }
    pg_free_result($res);
    
    header("Location:confirm_register.php");
?>

</body>
</html>