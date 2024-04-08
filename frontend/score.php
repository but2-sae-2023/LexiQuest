<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/endGame.css">
    <title>Document</title>
</head>
<body>
      <a href="../backend/lobby.php">
        <div class="back"></div>
      </a>
        <?php
            $objectif = $_GET['objectif'];
            $current = $_GET['current'];

            if($objectif === $current){
                echo '<div class="victory"></div>
                <div class="imposter">
                  <div class="spacesuit">
                    <div class="chest-and-head"></div>
                    <div class="legs"></div>
                    <div class="arm"></div>
                    <div class="helmet-glass"></div>
                  </div>
                </div>
                <div class="background"></div>
                <div class="name"></div>';
            }else{
                echo '<div class="defaite"></div>
                <div class="imposter">
                  <div class="spacesuit">
                    <div class="chest-and-head"></div>
                    <div class="legs"></div>
                    <div class="arm"></div>
                    <div class="helmet-glass"></div>
                  </div>
                </div>
                <div class="background-defaite"></div>
                <div class="name"></div>';
            }

            echo '<div id="objectif"> Objectif: '.$objectif.'</div>';
            echo '<div id="current"> Actuel: '.$current.'</div>';
        ?>
    
</body>
</html>