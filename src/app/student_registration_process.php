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
    $nom_etu = strtoupper($_POST["nom_etu"]);
    $prenom_etu = $_POST["prenom_etu"];
    $ddn_etu = $_POST["date_de_naissance_etu"];
    $ecole_etu = $_POST["selecteur_ecole"];
    $voiture = $_POST["voiture"];

    $req_id = "SELECT MAX(id_etudiant) FROM etudiants";
    $res_id = pg_query($connection, $req_id);
    if($res_id){
        $a = pg_fetch_assoc($res_id);
        $id_etu = $a["max"] + 1;
        echo "<p>id : ".$id_etu."</p>";
    }
    else {
        echo "<p> Erreur dans l'obtention de l'id de l'étudiant.".$id_etu."</p>";
    }
    $requete = "INSERT INTO Etudiants(ID_etudiant, prenom, nom, date_de_naissance, ecole)
    VALUES  ('$id_etu', '$prenom_etu', '$nom_etu', '$ddn_etu', '$ecole_etu')";
    $res = pg_query($connection, $requete);
    if(!$res)
    {
        echo "<p> Echec de l'inscription.</p>";
    }
    pg_free_result($res_id);
    pg_free_result($res);
    if ($voiture == 'non'){
        header("Location:confirm_register.php");
    }
    else{
        header("Location:register_car.php");
    }
?>

</body>
</html>