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
      $selected_user = $_SESSION["selected_user"];
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
    $depuis_vers = $_POST["selecteur"];
    $_SESSION["depuis_vers"] = $depuis_vers;
    $_SESSION["ville"] = $_POST["ville"];
    $date_depart = $_POST["date_depart"];
    $heure_depart = $_POST["heure_depart"];
    $requete_imm = "SELECT v.immatriculation FROM Voitures v WHERE $id = v.id_etudiant";
    $res_imm = pg_query($connection, $requete_imm);
    if($res_imm){
      $immatriculation = pg_fetch_assoc($res_imm)["immatriculation"];
    }
    else {
      echo "Erreur lors de la récupération de la plaque d'immatriculation du véhicule.";
    }

    if (!isset($immatriculation)) {
      echo "<p> Vous n'avez pas de voiture, vous ne pouvez pas proposer de trajet.";
    }

    $requete_id_trajet = "SELECT MAX(id_trajet) FROM Trajets";
    $res = pg_query($connection, $requete_id_trajet);
    if ($res){
      $a = pg_fetch_assoc($res);
      $id_trj = $a["max"] + 1;
      $_SESSION["trip_id"] = $id_trj;
    }
    else {
      echo "<p> Erreur lors de la récupération de l'id du trajet</p>";
    }
    pg_free_result($res_imm);


//Création du trajet

    if ($depuis_vers == 'depuis_campus')
    {
      $ville_depart = 'Bordeaux';
    }
    else {
      $ville_depart = $_SESSION["ville"];
    }
    $requete = "INSERT INTO Trajets(id_trajet, ville_depart, date_depart, heure_depart, immatriculation) 
    VALUES ('$id_trj','$ville_depart', '$date_depart', '$heure_depart', '$immatriculation')";
    $res = pg_query($connection, $requete);
    if ($res){
    }
    else {
      echo "<p>Erreur lors de l'ajout des données</p>";
      if (!isset($_SESSION["selected_user"])){
        echo "<p> Vous devez vous connecter à un utilisateur<p>";
        echo '<a href="../user/log.php"><div class="connect_button"><button>Se connecter</button></div></a>';
      }
    }
    pg_free_result($res);

  header("Location:information_trip.php");
  ?>
</body>
</html>