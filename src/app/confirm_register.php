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

?>
<p id="confirmation">
    Votre inscription est terminée.
</p>
<div class="menu">
    <a href="../index.php"><button>Retour à l'accueil</button></a>
    <a href="../user/log.php"><div class="connect_button"><button>Se connecter</button></div></a>
  </div>
</body>
</html>