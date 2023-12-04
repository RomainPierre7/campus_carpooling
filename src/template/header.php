<div class="header">
  <div class="logo">
    <?php
    $imagePaths = [
      "../media/logo.png",
      "media/logo.png"
    ];

    $linkPaths = [
      "../index.php",
      "index.php"
    ];
    function fileExists($path) {
      return file_exists($path);
    }

    $existingImagePath = null;
    foreach ($imagePaths as $path) {
      if (fileExists($path)) {
        $existingImagePath = $path;
        break;
      }
    }

    if ($existingImagePath == "../media/logo.png"){
      echo '<a href="../index.php"><img src="' . $existingImagePath . '" alt="Logo"></a>';
    } else if ($existingImagePath == "media/logo.png") {
      echo '<a href="index.php"><img src="' . $existingImagePath . '" alt="Logo"></a>';
    } else {
      echo '<p>Aucune image trouvée</p>';
    }
    ?>
  </div>
  <div class="header-text">
    <h1>Bienvenue sur <span class="app_name">Covoiturage du Campus</span></h1>
    <p>Trouvez facilement des covoiturages sur votre campus de <span class="campus">Talence, Pessac, Gradignan !</span></p>
  </div>
</div>
