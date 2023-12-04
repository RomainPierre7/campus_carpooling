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

  <div class="container">
    <h2>Mes futurs trajets en attente d'acceptation :</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet
      FROM trajets t, inscriptions i 
      WHERE t.immatriculation NOT IN 
        (SELECT immatriculation 
        FROM voitures v 
        WHERE v.id_etudiant = $id_etu)
      AND t.date_depart >= DATE(NOW())
      AND i.id_etudiant = $id_etu
      AND i.statut_inscription IS NULL 
      AND t.id_trajet = i.id_trajet 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
            $id_trajet = $trajet['id_trajet'];
            $date = date("d/m/Y", strtotime($trajet['date_depart']));
            $heure = date("H:i", strtotime($trajet['heure_depart']));
            $ville_depart = $trajet['ville_depart'];
            echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet-waiting">'."\n";
            echo '<div class="info">'."\n";
            if ($ville_depart == 'Bordeaux'){
                $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
                $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
                if($res_ville_arrivee) {
                    $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
                    pg_free_result($res_ville_arrivee);
                }
                echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
                echo '</div>'."\n";
                $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
                $res_points = pg_query($connection, $requete_points);
                if($res_points) {
                echo "\u{1F4CD}".'Arrêts : Bordeaux';
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                }
                pg_free_result($res_points);
          }
          else {
            echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
            echo '</div>'."\n";
            $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet AND p.statut_arret = TRUE ORDER BY distance_trajet ASC";
            $res_points = pg_query($connection, $requete_points);
            if($res_points) {
                echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                pg_free_result($res_points);
              }
          }
          echo '</div>';
          echo "<br>";
        }
        echo '</div></a>'."\n";
      }
      pg_free_result($res);
    ?>

    </div>
</div>

  <div class="container">
    <h2>Mes futurs trajets acceptés :</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet
      FROM trajets t, inscriptions i 
      WHERE t.date_depart >= DATE(NOW()) 
      AND i.id_etudiant = $id_etu
      AND i.statut_inscription = TRUE    
      AND t.id_trajet = i.id_trajet 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
            $id_trajet = $trajet['id_trajet'];
            $date = date("d/m/Y", strtotime($trajet['date_depart']));
            $heure = date("H:i", strtotime($trajet['heure_depart']));
            $ville_depart = $trajet['ville_depart'];
            echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet-accepted">'."\n";
            echo '<div class="info">'."\n";
            if ($ville_depart == 'Bordeaux'){
                $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
                $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
                if($res_ville_arrivee) {
                    $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
                    pg_free_result($res_ville_arrivee);
                }
                echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
                echo '</div>'."\n";
                $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
                $res_points = pg_query($connection, $requete_points);
                if($res_points) {
                echo "\u{1F4CD}".'Arrêts : Bordeaux';
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                }
                pg_free_result($res_points);
          }
          else {
            echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
            echo '</div>'."\n";
            $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet AND p.statut_arret = TRUE ORDER BY distance_trajet ASC";
            $res_points = pg_query($connection, $requete_points);
            if($res_points) {
                echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                pg_free_result($res_points);
              }
          }
          echo '</div>';
          echo "<br>";
        }
        echo '</div></a>'."\n";
      }
      pg_free_result($res);
    ?>
    </div>
</div>

<div class="container">
    <h2>Mes futurs trajets refusés :</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet
      FROM trajets t, inscriptions i 
      WHERE t.date_depart >= DATE(NOW()) 
      AND i.id_etudiant = $id_etu
      AND i.statut_inscription = FALSE    
      AND t.id_trajet = i.id_trajet 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
            $id_trajet = $trajet['id_trajet'];
            $date = date("d/m/Y", strtotime($trajet['date_depart']));
            $heure = date("H:i", strtotime($trajet['heure_depart']));
            $ville_depart = $trajet['ville_depart'];
            echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet-refused">'."\n";
            echo '<div class="info">'."\n";
            if ($ville_depart == 'Bordeaux'){
                $requete_ville_arrivee = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet DESC LIMIT 1";
                $res_ville_arrivee = pg_query($connection, $requete_ville_arrivee);
                if($res_ville_arrivee) {
                    $ville_arrivee = pg_fetch_assoc($res_ville_arrivee)['ville_arret'];
                    pg_free_result($res_ville_arrivee);
                }
                echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
                echo '</div>'."\n";
                $requete_points = "SELECT ville_arret FROM points_arret WHERE id_trajet = $id_trajet ORDER BY distance_trajet ASC";
                $res_points = pg_query($connection, $requete_points);
                if($res_points) {
                echo "\u{1F4CD}".'Arrêts : Bordeaux';
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                }
                pg_free_result($res_points);
          }
          else {
            echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
            echo '</div>'."\n";
            $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet AND p.statut_arret = TRUE ORDER BY distance_trajet ASC";
            $res_points = pg_query($connection, $requete_points);
            if($res_points) {
                echo "\u{1F4CD}".'Arrêts : '.$trajet['ville_depart'];
                while ($point = pg_fetch_assoc($res_points)) {
                  echo ' - '.$point['ville_arret'];
                }
                pg_free_result($res_points);
              }
          }
          echo '</div>';
          echo "<br>";
        }
        echo '</div></a>'."\n";
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
