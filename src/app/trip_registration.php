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
      if ($res) {
        $etudiant = pg_fetch_assoc($res);
        $prenom = $etudiant['prenom'];
        $nom = $etudiant['nom'];
        echo "<p>connecté en tant que $prenom $nom</p>";
      }
  }
  ?>
   <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>

  <?php
  // Début de la logique spécifique à trip_registration.php
  if (!isset($_SESSION["selected_user"])) {
      echo '<h2>Vous devez être connecté pour vous inscrire à ce point d\'arrêt.</h2>';
      echo '<a href="../user/log.php"><div class="connect_button"><button>Se connecter</button></div></a>';
  }

  if (isset($_GET['id_trajet']) && isset($_GET['id_point_arret'])) {
      $ID_trajet = $_GET['id_trajet'];
      $ID_point_arret = $_GET['id_point_arret'];
      $ID_etudiant = $_SESSION["selected_user"];

    // Vérifier si le couple ID_trajet et ID_etudiant existe déjà
      $requete_verif = "SELECT * FROM Inscriptions WHERE ID_etudiant = $ID_etudiant AND ID_trajet = $ID_trajet";
      $resultat_verif = pg_query($connection, $requete_verif);

      // Vérifier si l'étudiant n'est pas le conducteur du trajet
      $requete_conducteur = "SELECT ID_etudiant FROM Voitures v INNER JOIN Trajets t ON v.immatriculation = t.immatriculation WHERE ID_trajet = $ID_trajet";
      $resultat_conducteur = pg_query($connection, $requete_conducteur);
      $conducteur = pg_fetch_assoc($resultat_conducteur);
      $requete_date = "SELECT date_depart FROM Trajets t WHERE t.id_trajet = $ID_trajet AND t.date_depart > DATE(NOW())";
      $resultat_date = pg_query($connection, $requete_date);
      if($conducteur['id_etudiant'] == $ID_etudiant) {
        echo "<p>Un conducteur ne peut pas s'inscrire à son propre trajet.</p>";
       } else if(pg_num_rows($resultat_verif) > 0) {
        echo "<p>Vous êtes déjà inscrit à ce trajet ou vous avez déjà été refusé par le conducteur.</p>";
       } else if( (pg_num_rows($resultat_date) < 1) ){
        echo "<p> Le trajet est déjà terminé, vous ne pouvez pas vous inscrire.</p>";
       } else {
        $requete_insert = "INSERT INTO Inscriptions (ID_etudiant, ID_trajet, ID_point_arret) 
        VALUES ($ID_etudiant, $ID_trajet, $ID_point_arret)";

        $resultat_insert = pg_query($connection, $requete_insert);
        if ($resultat_insert) {
          echo "<p>Votre demande d'inscription à ce trajet a été envoyée.</p>";
        } else {
          echo "<p>Erreur lors de l'inscription : ".pg_last_error($connection) . "</p>";
        }
      }
      } else {
      echo "<p>Informations nécessaires non spécifiées.</p>";
      }
  ?>
</body>
</html>