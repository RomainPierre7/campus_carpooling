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
  if (isset($_GET['id'])) {
    $trajet_id = (int)$_GET['id']; // Cast to integer to avoid SQL injection
  } else {
    echo "Erreur : aucun trajet sélectionné";
    exit;
  }
  ?>
   <div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="search.php"><button>Rechercher un trajet</button></a>
  </div>

<?php
$req_place_vehicule = "SELECT nombre_de_places FROM voitures v, trajets t WHERE t.immatriculation = v.immatriculation AND t.id_trajet = $trajet_id";
$res_place_vehicule = pg_query($connection, $req_place_vehicule);
if ($res_place_vehicule) {
  $nb_place_vehicule = pg_fetch_assoc($res_place_vehicule);
  $nb_place_vehicule = $nb_place_vehicule['nombre_de_places'];
  pg_free_result($res_place_vehicule);
} else {
  echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
}

$req_nombre_inscrit = "SELECT COUNT(*) AS nombre_inscrit FROM inscriptions WHERE id_trajet = $trajet_id AND statut_inscription = TRUE";
$res_nombre_inscrit = pg_query($connection, $req_nombre_inscrit);
if ($res_nombre_inscrit) {
  $nb_inscrit = pg_fetch_assoc($res_nombre_inscrit);
  $nb_inscrit = $nb_inscrit['nombre_inscrit'];
  pg_free_result($res_nombre_inscrit);
} else {
  echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
}

$nb_place_dispo = $nb_place_vehicule - $nb_inscrit;

  echo '<div class="stats">';
  echo '<div class="stat">';
  echo '<div class="number">'.$nb_place_dispo.'</div>'.'<div class="text">Places restantes</div>';
  echo '</div>';
  echo '</div>';

?>

  <div class="container">
  <h2>Information sur le <span class="direction">conducteur</span></h2>
  <?php
    $requete_conducteur = "SELECT 
    e.nom, 
    e.prenom, 
    v.immatriculation, 
    v.couleur, 
    v.nombre_de_places,
    COALESCE(
      (
        SELECT ROUND(AVG(a.note), 2) 
        FROM Reception_avis r
        INNER JOIN Avis a ON r.ID_avis = a.ID_avis 
        WHERE r.etudiant_note = e.ID_etudiant
      ),
      0
    ) AS note_moyenne
  FROM 
    Voitures v 
  INNER JOIN 
    Etudiants e ON v.ID_etudiant = e.ID_etudiant
  INNER JOIN 
    Trajets t ON t.immatriculation = v.immatriculation
  WHERE 
    t.Id_trajet = $trajet_id 
  GROUP BY 
    e.nom, e.prenom, v.immatriculation, v.couleur, e.ID_etudiant;
  ";
  $res_conducteur = pg_query($connection, $requete_conducteur);
    if ($res_conducteur) {
      while ($conducteur = pg_fetch_assoc($res_conducteur)) {
        echo '<div class="conductor-info">';
        echo '<div class="info-item"><span class="info-label">Nom :</span> ' . htmlspecialchars($conducteur['nom']) . '</div>';
        echo '<div class="info-item"><span class="info-label">Prénom :</span> ' . htmlspecialchars($conducteur['prenom']) . '</div>';
        echo '<div class="info-item"><span class="info-label">Immatriculation :</span> ' . htmlspecialchars($conducteur['immatriculation']) . '</div>';
        echo '<div class="info-item"><span class="info-label">Couleur du véhicule :</span> ' . htmlspecialchars($conducteur['couleur']) . '</div>';
        echo '<div class="info-item"><span class="info-label">Nombre de places :</span> ' . htmlspecialchars($conducteur['nombre_de_places']) . '</div>';
        echo '<div class="info-item"><span class="info-label">Note moyenne du conducteur:</span> ' . htmlspecialchars($conducteur['note_moyenne']) . '</div>';
        echo '</div>';
        echo "</div>";
      }
      pg_free_result($res_conducteur);
    } else {
      echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connection);
    }
  ?>
</div>

<div class="container">
  <h2>Passagers déjà <span class="direction">inscrits</span></h2>
  <div class="passenger-container">
  <?php
    $req = "SELECT nom, prenom FROM etudiants e, inscriptions i WHERE e.id_etudiant = i.id_etudiant AND i.id_trajet = $trajet_id AND i.statut_inscription = TRUE";
    $res = pg_query($connection, $req);
    if ($res) {
      while ($passager = pg_fetch_assoc($res)){
        echo '<div class="passenger-info-container">';
        echo '<div class="passenger-lastname">'.$passager['nom'].'</div>'.' '.'<div class="passenger-name">'.$passager['prenom'].'</div>';
        echo '</div>';
      }
    }
  ?>
  </div>
</div>

<div class="container">
  <h2>Passagers en <span class="direction">attente de validation</span></h2>
  <div class="passenger-container">
  <?php
    $req = "SELECT nom, prenom FROM etudiants e, inscriptions i WHERE e.id_etudiant = i.id_etudiant AND i.id_trajet = $trajet_id AND i.statut_inscription IS NULL";
    $res = pg_query($connection, $req);
    if ($res) {
      while ($passager = pg_fetch_assoc($res)){
        echo '<div class="passenger-info-container">';
        echo '<div class="passenger-lastname">'.$passager['nom'].'</div>'.' '.'<div class="passenger-name">'.$passager['prenom'].'</div>';
        echo '</div>';
      }
    }
  ?>
  </div>
</div>

  <div class="container">
    <h2>Informations sur les <span class="direction">points d'arrêt</span></h2>

    <div class="trajets">
    <?php
      if (isset($_GET['id'])) {
        $trajet_id = $_GET['id'];
        $req = "SELECT t.ville_depart FROM trajets t WHERE t.id_trajet = $trajet_id";
        $res = pg_query($connection, $req);
        if($res){
          $res = pg_fetch_assoc($res);
          $ville_depart = $res['ville_depart'];
        }
        if ($ville_depart == "Bordeaux"){
          $requete_point_arret = "SELECT p.prix_par_passager, p.ID_point_arret, p.ville_arret AS ville_arret , t.heure_depart + (p.duree_trajet || ' minutes')::interval AS heure_arrivee,p.distance_trajet AS distance
            FROM points_arret p INNER JOIN trajets t 
            ON p.id_trajet = t.id_trajet 
            WHERE t.id_trajet = $trajet_id AND p.statut_arret = TRUE
            ORDER BY distance_trajet ASC";
        } else {
          $requete_point_arret = "SELECT p.prix_par_passager, p.ID_point_arret, p.ville_arret AS ville_arret , t.heure_depart + (p.duree_trajet || ' minutes')::interval AS heure_arrivee,p.distance_trajet AS distance
            FROM points_arret p INNER JOIN trajets t 
            ON p.id_trajet = t.id_trajet 
            WHERE t.id_trajet = $trajet_id AND p.statut_arret = TRUE
            ORDER BY distance_trajet DESC";
        }
      $res = pg_query($connection, $requete_point_arret);
      if($res) {
        $compteur = 1;
        while ($ligne =  pg_fetch_assoc($res)) {
          $ville_arret = $ligne['ville_arret'];
          $heure_arrivee= date("H:i",strtotime($ligne['heure_arrivee']));
          $distance = $ligne['distance'];
          $ID_point_arret = $ligne['id_point_arret'];
          $prix_par_passager = $ligne['prix_par_passager'];

          echo '<div class="trajet">';
          echo '<div class="info">';
          echo '<div class="info-item"><span class="info-label">Arrêt n°'.$compteur.' :</span> '.$ville_arret.'</div>';
          echo '<div class="info-item"><span class="info-label">Heure :</span> '.$heure_arrivee.'</div>';
          echo '<div class="info-item"><span class="info-label">Distance en km :</span> '.$distance.'</div>';
          echo '<div class="info-item"><span class="info-label">Prix par passager en euro :</span> '.$prix_par_passager.'</div>';
          if($nb_place_dispo > 0) {
            echo '<a href="trip_registration.php?id_trajet='.$trajet_id.'&id_point_arret='.$ID_point_arret.'"><button>S\'INSCRIRE</button></a>';
          } else {
            echo '<div class="connect_button"><button>COMPLET</button></div>';
          }
          echo '</div>';
          echo '</div>';
          
          $compteur++;
        }
        echo '</div>';
      }
      pg_free_result($res);
    }
    ?>
    </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>

</body>
</html>
