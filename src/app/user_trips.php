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
    $selected_user = $_SESSION["selected_user"];
  ?>
   <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>
  <?php
      include "connect_pg.php";
  ?>
  <h2>Trajets en tant que <span class="role">passager</span> :</h2>
  <div class="container">
    <h2><span class="direction">Depuis</span> le Campus</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places - COUNT(i.id_etudiant) as pl_dispo 
      FROM trajets t, voitures v, inscriptions i 
      WHERE i.id_trajet IN (SELECT inscriptions.id_trajet FROM inscriptions WHERE inscriptions.statut_inscription = TRUE AND inscriptions.id_etudiant = $selected_user) 
      and v.immatriculation = t.immatriculation 
      AND t.id_trajet = i.id_trajet 
      AND t.ville_depart = 'Bordeaux'
      GROUP BY t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
          $id_trajet = $trajet['id_trajet'];
          $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
          $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
          if($res_ville_arrivee) {
            $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
            pg_free_result($res_ville_arrivee);
          }
          $date = date("d/m/Y", strtotime($trajet['date_depart']));
          $heure = date("H:i", strtotime($trajet['heure_depart']));
          $place_dispo = $trajet['pl_dispo'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
          $res_points = pg_query($connection, $requete_points);
          if($res_points) {
            echo "\u{1F4CD}".'Arrêts : Bordeaux';
            while ($point = pg_fetch_assoc($res_points)) {
              echo ' - '.$point['ville_arret'];
            }
            pg_free_result($res_points);
            echo "<br>";
            echo "\u{1F697} ". 'Places restantes : '.$place_dispo;
          }
          echo "</div></a>"."\n";
        }
        echo '</div>'."\n";
      }
      pg_free_result($res);
    ?>

    </div>
  </div>
  <div class="container">
    <h2><span class="direction">Vers</span> le Campus</h2>

    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places - COUNT(i.id_etudiant) as pl_dispo 
      FROM trajets t, voitures v, inscriptions i 
      WHERE i.id_trajet IN (SELECT inscriptions.id_trajet FROM inscriptions WHERE inscriptions.statut_inscription = TRUE AND inscriptions.id_etudiant = $selected_user) 
      AND t.id_trajet = i.id_trajet
      AND v.immatriculation = t.immatriculation 
      AND t.ville_depart != 'Bordeaux'
      GROUP BY t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places 
      ORDER BY t.date_depart, t.heure_depart ASC;";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
          $id_trajet = $trajet['id_trajet'];
          $date = date("d/m/Y", strtotime($trajet['date_depart']));
          $heure = date("H:i", strtotime($trajet['heure_depart']));
          $place_dispo = $trajet['pl_dispo'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet ORDER BY distance_trajet ASC";
          $res_points = pg_query($connection, $requete_points);
          if($res_points) {
            echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
            while ($point = pg_fetch_assoc($res_points)) {
              echo ' - '.$point['ville_arret'];
            }
            pg_free_result($res_points);
            echo "<br>";
            echo "\u{1F697} ". 'Places restantes : '.$place_dispo;
          }
          echo "</div></a>"."\n";
        }
        echo '</div>'."\n";
      }
      pg_free_result($res);
    ?>

    </div>
</div>

<h2>Trajets en tant que <span class="role">conducteur</span> :</h2>
  <div class="container">
    <h2><span class="direction">Depuis</span> le Campus</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places - COUNT(i.id_etudiant) as pl_dispo 
      FROM trajets t, voitures v, inscriptions i 
      WHERE i.id_trajet IN (SELECT inscriptions.id_trajet FROM inscriptions WHERE inscriptions.statut_inscription = TRUE) 
      AND v.immatriculation = t.immatriculation
      AND t.immatriculation IN (SELECT voitures.immatriculation FROM voitures WHERE voitures.id_etudiant = $selected_user) 
      AND t.id_trajet = i.id_trajet 
      AND t.ville_depart = 'Bordeaux'
      GROUP BY t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
          $id_trajet = $trajet['id_trajet'];
          $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
          $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
          if($res_ville_arrivee) {
            $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
            pg_free_result($res_ville_arrivee);
          }
          $date = date("d/m/Y", strtotime($trajet['date_depart']));
          $heure = date("H:i", strtotime($trajet['heure_depart']));
          $place_dispo = $trajet['pl_dispo'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
          $res_points = pg_query($connection, $requete_points);
          if($res_points) {
            echo "\u{1F4CD}".'Arrêts : Bordeaux';
            while ($point = pg_fetch_assoc($res_points)) {
              echo ' - '.$point['ville_arret'];
            }
            pg_free_result($res_points);
            echo "<br>";
            echo "\u{1F697} ". 'Places restantes : '.$place_dispo;
          }
          echo "</div></a>"."\n";
        }
        echo '</div>'."\n";
      }
      pg_free_result($res);
    ?>

    </div>
  </div>
  <div class="container">
    <h2><span class="direction">Vers</span> le Campus</h2>

    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places - COUNT(i.id_etudiant) as pl_dispo 
      FROM trajets t, voitures v, inscriptions i 
      WHERE i.id_trajet IN (SELECT inscriptions.id_trajet FROM inscriptions WHERE inscriptions.statut_inscription = TRUE) 
      AND t.id_trajet = i.id_trajet
      AND v.immatriculation = t.immatriculation 
        AND t.immatriculation IN (SELECT voitures.immatriculation FROM voitures WHERE voitures.id_etudiant = $selected_user)
      AND t.ville_depart != 'Bordeaux'
      GROUP BY t.date_depart, t.heure_depart, t.id_trajet, v.nombre_de_places 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
          $id_trajet = $trajet['id_trajet'];
          $date = date("d/m/Y", strtotime($trajet['date_depart']));
          $heure = date("H:i", strtotime($trajet['heure_depart']));
          $place_dispo = $trajet['pl_dispo'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet ORDER BY distance_trajet ASC";
          $res_points = pg_query($connection, $requete_points);
          if($res_points) {
            echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
            while ($point = pg_fetch_assoc($res_points)) {
              echo ' - '.$point['ville_arret'];
            }
            pg_free_result($res_points);
            echo "<br>";
            echo "\u{1F697} ". 'Places restantes : '.$place_dispo;
          }
          echo "</div></a>"."\n";
        }
        echo '</div>'."\n";
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