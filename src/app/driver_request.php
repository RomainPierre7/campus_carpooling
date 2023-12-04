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
        $id_etu = $etudiant['id_etudiant'];
        echo "<p>connecté en tant que $prenom $nom </p>";
      }
    }
    echo '<div class="menu">';
    echo '<a href="../index.php"><button>Retour à l\'accueil</button></a>';
    echo '</div>';

    $requete = "SELECT immatriculation FROM Voitures v WHERE id_etudiant = $id_etu";
    $res = pg_query($connection, $requete);
    if($res){
        $res_imm = pg_fetch_assoc($res);
        $imm = $res_imm['immatriculation'];
        //echo '<p> Vous n\'avez aucune demande d\'inscription pour le moment.</p>';
    }
    else {
        echo '<p> Erreur dans la récupération de la plaque d\'immatriculation.</p>';
    }
    if (pg_num_rows($res) < 1) echo '<p> Vous n\'avez pas de voiture. 
    La fonctionnalité pour ajouter sa voiture après s\'être inscrit arrive prochainement.</p>';
    $requete = "SELECT e.prenom, e.nom, pa.ville_arret, e.id_etudiant, pa.id_point_arret, t.id_trajet FROM inscriptions i 
    JOIN etudiants e ON i.id_etudiant = e.id_etudiant 
    JOIN points_arret pa ON pa.id_point_arret = i.id_point_arret
    JOIN trajets t ON t.id_trajet = pa.id_trajet
    JOIN voitures v ON v.immatriculation = t.immatriculation
    WHERE i.statut_inscription IS NULL
    AND t.date_depart >= DATE(NOW())
    AND v.id_etudiant = $id_etu";
    $res = pg_query($connection, $requete);
    if ($res)
    {
        $res_info = pg_fetch_assoc($res);
        if ($res_info){                
          while (isset($res_info['nom'])){
            $nom_ins = $res_info['nom'];
            $prenom_ins = $res_info['prenom'];
            $pt_arret = $res_info['ville_arret'];
            $id_etu = $res_info['id_etudiant'];
            $id_trajet = $res_info['id_trajet'];
            echo '<div class="etudiant_info">';
            echo '<div class="info">';
            echo '<div class="info-item"><span class="info-label">Prénom:</span> '.$prenom_ins.'</div>';
            echo '<div class="info-item"><span class="info-label">Nom:</span> '.$nom_ins.'</div>';
            echo '<div class="info-item"><span class="info-label">Ville d\'arrêt :</span> '.$pt_arret.'</div>';
            echo '<a href="driver_accept.php?id_etu_answer='.$id_etu.'&id_trajet_answer='.$id_trajet.'"><button>Accepter</button></a>';
            echo '<a href="driver_refuse.php?id_etu_answer='.$id_etu.'&id_trajet_answer='.$id_trajet.'"><button>Refuser</button></a>';
            echo '</div>';
            echo '</div>';
            $res_info = pg_fetch_assoc($res);       
        }
    }
    else {
        echo'<p> Vous n\'avez actuellement aucune demande d\'inscription.</p>.';
    }
    }
    pg_free_result($res);

?>

<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
