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
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>
  
  <div class="container">
    <h2>Tous les trajets <span class="direction">depuis</span> le Campus !</h2>
    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet, v.immatriculation
      FROM trajets t, voitures v
      WHERE v.immatriculation = t.immatriculation 
      AND t.ville_depart = 'Bordeaux' 
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
          $imm = $trajet['immatriculation'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $ville_arrivee.' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet AND p.statut_arret = TRUE ORDER BY distance_trajet ASC";
          $res_points = pg_query($connection, $requete_points);
          
          $req_place_vehicule = "SELECT nombre_de_places FROM voitures v, trajets t WHERE t.immatriculation = v.immatriculation AND t.id_trajet = $id_trajet";
          $res_place_vehicule = pg_query($connection, $req_place_vehicule);
          if ($res_place_vehicule) {
            $nb_place_vehicule = pg_fetch_assoc($res_place_vehicule);
            $nb_place_vehicule = $nb_place_vehicule['nombre_de_places'];
            pg_free_result($res_place_vehicule);
          } else {
            echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
          }

          $req_nombre_inscrit = "SELECT COUNT(*) AS nombre_inscrit FROM inscriptions WHERE id_trajet = $id_trajet AND statut_inscription = TRUE";
          $res_nombre_inscrit = pg_query($connection, $req_nombre_inscrit);
          if ($res_nombre_inscrit) {
            $nb_inscrit = pg_fetch_assoc($res_nombre_inscrit);
            $nb_inscrit = $nb_inscrit['nombre_inscrit'];
            pg_free_result($res_nombre_inscrit);
          } else {
            echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
          }
          $place_dispo = $nb_place_vehicule - $nb_inscrit; 
          
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
    <h2>Tous les trajets <span class="direction">vers</span> le Campus !</h2>

    <div class="trajets">
    <?php
      $requete = "SELECT t.ville_depart, t.date_depart, t.heure_depart, t.id_trajet, v.immatriculation
      FROM trajets t, voitures v
      WHERE v.immatriculation = t.immatriculation 
      AND t.ville_depart != 'Bordeaux' 
      ORDER BY t.date_depart, t.heure_depart ASC";
      $res = pg_query($connection, $requete);
      if($res) {
        while ($trajet =  pg_fetch_assoc($res)) {
          $id_trajet = $trajet['id_trajet'];
          $date = date("d/m/Y", strtotime($trajet['date_depart']));
          $heure = date("H:i", strtotime($trajet['heure_depart']));
          $imm = $trajet['immatriculation'];
          echo '<a href="trip_details.php?id='.$id_trajet.'"><div class="trajet">'."\n";
          echo '<div class="info">'."\n";
          echo $trajet['ville_depart'].' - '.$date.' - '.$heure."\n";
          echo '</div>'."\n";
          $requete_points = "SELECT p.ville_arret FROM points_arret p WHERE p.id_trajet = $id_trajet AND p.statut_arret = TRUE ORDER BY distance_trajet DESC";
          $res_points = pg_query($connection, $requete_points);
          
          $req_place_vehicule = "SELECT nombre_de_places FROM voitures v, trajets t WHERE t.immatriculation = v.immatriculation AND t.id_trajet = $id_trajet";
          $res_place_vehicule = pg_query($connection, $req_place_vehicule);
          if ($res_place_vehicule) {
            $nb_place_vehicule = pg_fetch_assoc($res_place_vehicule);
            $nb_place_vehicule = $nb_place_vehicule['nombre_de_places'];
            pg_free_result($res_place_vehicule);
          } else {
            echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
          }

          $req_nombre_inscrit = "SELECT COUNT(*) AS nombre_inscrit FROM inscriptions WHERE id_trajet = $id_trajet AND statut_inscription = TRUE";
          $res_nombre_inscrit = pg_query($connection, $req_nombre_inscrit);
          if ($res_nombre_inscrit) {
            $nb_inscrit = pg_fetch_assoc($res_nombre_inscrit);
            $nb_inscrit = $nb_inscrit['nombre_inscrit'];
            pg_free_result($res_nombre_inscrit);
          } else {
            echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
          }
          $place_dispo = $nb_place_vehicule - $nb_inscrit; 

          if($res_points) {
            echo "\u{1F4CD}".'Arrêts : ';
            while ($point = pg_fetch_assoc($res_points)) {
              echo $point['ville_arret'].' - ';
            }
            echo "Bordeaux";
            pg_free_result($res_points);
          }
            echo "<br>";
            echo "\u{1F697} ". 'Places restantes : '.$place_dispo;

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