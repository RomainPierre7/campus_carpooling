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
  </div>
  <div class="form">
    <form action="student_registration_process.php" method="post">

      <label for="prenom_etu">Prenom :</label>
      <input type="text" name="prenom_etu" id="prenom_etu" required><br><br>

      <label for="nom_etu">Nom :</label>
      <input type="text" name="nom_etu" id="nom_etu" required><br><br>

      <label for="date_de_naissance_etu">Date de naissance :</label>
      <input type="date" name="date_de_naissance_etu" id="date_de_naissance_etu" required><br><br>

      <label for="selecteur_ecole">Ecole :</label>
      <select name="selecteur_ecole" id="selecteur_ecole">
        <option value="ENSEIRB-MATMECA">ENSEIRB-MATMECA</option>
        <option value="ENSTBB">ENSTBB</option>
        <option value="ENSPIMA">ENSPIMA</option>
        <option value="UNIVERSITE MONTAIGNE">Université Montaigne</option>
        <option value="KEDGE">KEDGE</option>
        <option value="SCIENCES POLITIQUES">Sciences Politiques </option>
        <option value="INSEEC">INSEEC</option>
        <option value="ENSAM">ENSAM</option>
        <option value="SCIENCES AGRO">Sciences Agro</option>


      </select><br><br>
      <p></p>

      <label for="voiture">As-tu une voiture :</label>
      <select name="voiture" id="voiture">
        <option value="oui">Oui</option>
        <option value="non">Non</option>
        </select><br><br>
      <p></p>
      <input type="submit" name="submit" value="Soumettre">
    </form>
  </div>
<footer>
  <p>&copy; <?php echo date("Y"); ?> Covoiturage du Campus. Tous droits réservés.</p>
</footer>
</body>
</html>
