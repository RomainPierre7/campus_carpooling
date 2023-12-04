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
    <a href="search.php"><button>Nouvelle recherche</button></a>
  </div>

  <?php
  if (isset($_POST['submit'])) {
    $selecteur = $_POST['selecteur'];
    $ville = $_POST['ville'];
    $date_depart = $_POST['date_depart'];
    if ($date_depart == null) {
      $nodate = true;
    } else {
      $nodate = false;
      $date = date("d/m/Y", strtotime($date_depart));
    }
  }
  include "connect_pg.php";
?>

<h2>Trajets correspondants à <span class="direction">votre recherche</span>:</h2>

<div class="container">
    <div class="trajets">
    <?php
      if ($nodate) {
        if ($selecteur == "depuis_campus") {
          $prequete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet FROM trajets t, points_arret p WHERE p.id_trajet = t.id_trajet AND t.ville_depart = 'Bordeaux' AND p.ville_arret = $1 AND t.date_depart >= CURRENT_DATE ORDER BY t.date_depart, t.heure_depart ASC";
        } else if ($selecteur == "vers_campus") {
          $prequete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet FROM trajets t, points_arret p WHERE p.id_trajet = t.id_trajet AND t.ville_depart != 'Bordeaux' AND (p.ville_arret = $1 OR t.ville_depart = $1) AND t.date_depart >= CURRENT_DATE ORDER BY t.date_depart, t.heure_depart ASC";
        }
      } else if (!$nodate){
        if ($selecteur == "depuis_campus") {
          $prequete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet FROM trajets t, points_arret p WHERE p.id_trajet = t.id_trajet AND t.ville_depart = 'Bordeaux' AND p.ville_arret = $1 AND t.date_depart = $2 ORDER BY t.date_depart, t.heure_depart ASC";
        } else if ($selecteur == "vers_campus") {
          $prequete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet FROM trajets t, points_arret p WHERE p.id_trajet = t.id_trajet AND t.ville_depart != 'Bordeaux' AND (p.ville_arret = $1 OR t.ville_depart = $1) AND t.date_depart = $2 ORDER BY t.date_depart, t.heure_depart ASC";
        }
      }
      $res = pg_prepare($connection, "req_chercher_trajets", $prequete);
      if ($res){
        if ($nodate){
          $res = pg_execute($connection, "req_chercher_trajets", array($ville));
        } else if (!$nodate) {
          $res = pg_execute($connection, "req_chercher_trajets", array($ville, $date));
        }
        if($res) {
          if (pg_num_rows($res) == 0) {
            echo 'Aucun trajet ne correspond à votre recherche.'."\n";
          }
          while ($trajet =  pg_fetch_assoc($res)) {
            $id_trajet = $trajet['id_trajet'];
            $date = date("d/m/Y", strtotime($trajet['date_depart']));
            $heure = date("H:i", strtotime($trajet['heure_depart']));
            echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
            echo '<div class="info">'."\n";
            if ($selecteur == "depuis_campus") {
              $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
              $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
              if($res_ville_arrivee) {
                $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
                pg_free_result($res_ville_arrivee);
              }
              echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
              } else if ($selecteur = "vers_campus") {
                echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
              }
            echo '</div>'."\n";
            $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
            $res_points = pg_query($connection, $requete_points);
            if($res_points) {
              if ($selecteur == "depuis_campus") {
                echo "\u{1F4CD}".'Arrêts : Bordeaux';
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
              } else if ($selecteur == "vers_campus") {
                echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
              }
              pg_free_result($res_points);
            }
            echo "</div></a>"."\n";
          }
          echo '</div>'."\n";
        }
      }
      pg_free_result($res);
    ?>

    </div>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>

</body>
</html>