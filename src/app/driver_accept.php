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
    if (isset($_GET['id_etu_answer'])) {
      $id_etu_answer = (int)$_GET['id_etu_answer'];
    } else {
      echo "Erreur : aucun étudiant sélectionné";
    }
    if (isset($_GET['id_trajet_answer'])) {
      $id_trajet_answer = (int)$_GET['id_trajet_answer'];
    } else {
      echo "Erreur : aucun trajet sélectionné";
    }
  ?>
<div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
  </div>
<?php
        $requete = "UPDATE inscriptions i SET statut_inscription = 'TRUE' 
        WHERE i.id_etudiant = $id_etu_answer  
        AND i.id_trajet = $id_trajet_answer"; 
        $res = pg_query($connection, $requete);
        if ($res){
            echo '<p> Validation de l\'acceptation de la demande.</p>';
        }
        else {
          echo '<p> Echec de la validation de la demande.</p>';
        }
        pg_free_result($res);    
?>


<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
