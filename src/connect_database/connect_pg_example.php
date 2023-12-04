<?php
  $login = 'login'   /*A compléter*/;
  $db_pwd = 'password'  /*A compléter*/;
  /* Creation de l'objet qui gere la connexion: */
  $connection_string = "host=localhost port=5432 dbname=covoiturage_du_campus"." user=".$login." password=".$db_pwd;
  $connection = pg_connect($connection_string);
  if(!$connection) {
     echo 'ERROR: Unable to connect to the database';
  }
?>
