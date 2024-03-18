<?php

require_once "config/pdo.php";


$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT *
  FROM oeuvres
  JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
  JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
  JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
  WHERE  oeuvres.Id_oeuvre = $id";
  $request = $db->query($sql);
  $arts = $request->fetchAll(PDO::FETCH_ASSOC);
}

$sqlSelectLangue = "SELECT *
FROM langue";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);



if (isset($_POST['submit'])) {
  $langue = $_POST['langue'];
} else {
  $langue = "Français";
}

$sqlLangue = "SELECT * 
FROM langue
WHERE libelle_Langue = :libelle_Langue";
$queryLangue = $db->prepare($sqlLangue);
$queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
$queryLangue->execute();
$row = $queryLangue->fetch(PDO::FETCH_ASSOC);
$id_L = $row['Id_Langue'];


$sqlDescription = "SELECT *
  FROM contenu
  JOIN langue ON contenu.Id_Langue = langue.Id_Langue
  JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
  WHERE  langue.Id_Langue = $id_L And oeuvres.Id_oeuvre = $id";
$request1 = $db->query($sqlDescription);
$descriptions = $request1->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="language">
  <form method="POST" class="formlanguage">
    <select name="langue" id="langue" class="langues">
      <?php foreach ($langues as $langue) : ?>
        <div class="item">
          <option value="<?php echo $langue['libelle_Langue']; ?>" class="">
            <?php echo $langue['libelle_Langue']; ?></option>
        </div>
      <?php endforeach; ?>
    </select>

    <input type="submit" name="submit" value="traduire">
  </form>
</div>



<section>



    <div id="content1" class="hidden">


    <div class="artwork">
      <i class="fa-solid fa-chart-bar"></i>
      <h3>Fiche de l'oeuvre</h3>
      <?php foreach ($arts as $art) : ?>
        <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

    </div>


    <div class="description">
      <i class="fa-solid fa-palette"></i>
      <h3>Description de l'oeuvre</h3>
      <p>
        <?php foreach ($descriptions as $description) {
          echo $description['description_Contenu'];
        } ?>

      </p>
    <?php endforeach ?>

    </div>




    </div>




    <div id="content2" class="hidden">


      <div class="artwork">
        <i class="fa-solid fa-chart-bar"></i>
        <h3>درباره اثر</h3>
        <?php foreach ($arts as $art) : ?>
          <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

      </div>


      <div class="description">
        <i class="fa-solid fa-palette"></i>
        <h3>Description de l'oeuvre</h3>
        <p>
          <?php foreach ($descriptions as $description) {
            echo $description['description_Contenu'];
          } ?>
        </p>
      <?php endforeach ?>

      </div>




    </div>





    <div class="additional-info">
      <i class="fa-solid fa-file-circle-plus"></i>
      <h3 class="title_additional_info">Informations supplémentaires</h3>
      <ul>

        <li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
        <li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
        <li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
        <li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
        <li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>

      </ul>
    </div>


    <div class="exhibition-video">
      <i class="fa-regular fa-circle-play"></i>
      <h3>Vidéo de l'exposition</h3>

      <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="audio-file">
      <i class="fa-solid fa-file-audio"></i>
      <h3>Fichier audio</h3>
      <audio controls>
        <source src="audio.mp3" type="audio/mpeg">
      </audio>
    </div>

</section>


<script>
    document.getElementById('langue').addEventListener('change', function() {
        let selectedOption = this.value;
 
        // Hide all content divs
        let allContentDivs = document.querySelectorAll('.content');
        allContentDivs.forEach(function(div) {
            div.classList.add('hidden');
        });
 
        // Show content div corresponding to the selected option
        var selectedContentDiv = document.getElementById('content' + selectedOption.charAt(selectedOption.length - 1));
        selectedContentDiv.classList.remove('hidden');
    });
</script>

<?php


include_once "includes/pages/footer.php";

?>




/**/ 


<div class="expo-wrapper">

  <?php foreach ($Expos as $Expo) : ?>
    <div class="card-keeper-expo">
      <div class="card-expo">
        <div class="image-content-expo">
          <div class="card-image-expo">
          <img src="assets/images/exposition/<?= $Expo["chemin_Affiche"]; ?>" class="card-img-expo" alt="Images de l'exposition">
          </div>
        </div>
        <div class="card-content-expo">
        <h4 class="libelle-expo"><?= $Expo["libelle_Exposition"]; ?></h4>
        <a href="#"><button class="voir-plus-expo">Voir plus</button></a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

</div>

/**/

.expo-wrapper {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  margin-right: 10px;
  gap: 10px;

}

.card-keeper-expo{
  width: 100%;
  display:flex;
  padding: 5px 0;
  gap: 10px;
}
.card-expo{
  width: 600px;
  border-radius: 25px;
  flex-grow: 0;
  flex-shrink: 0;
  background-color: #000;
}
.image-content-expo, .card-content-expo {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding:10px 14px;
}
.image-content-expo{
  position: relative;
  row-gap: 5px;
  padding: 25px 0;
}
.card-image-expo{
  position: relative;
  height:250px;
  width :300px;
  border-radius: 5px;
  background-color: #F3f3f3;
}
.card-image-expo .card-img-expo{
  position: absolute;
  height: 100%;
  width: 100%;


  border: 4px solid var(--BGCOLORPR);
}
.libelle-expo{
  font-size: 1.2rem;
  font-weight: 500;
}
.voir-plus-expo{
  border:none;
  background-color: var(--BGCOLORPU);
  color: var(--FONTCOLOR);
  border-radius: 20px;
  padding: 5px 10px;
  margin:5px 0 ;
  cursor: pointer;
  width: 90px;
  font-weight: 500;
  font-family: var(--FONTTITLE);
  transition: all 0.3s ease;
  }
   
  .voir-plus-expo:hover{
    background-color: var(--ACCENT1);
    color: var(--FONTCOLOR2);
  }


  /**/
   

   <?php
if (isset($_GET['id'])) :
    $id2 = $_GET['id'];
    $currentDateTime = date("Y-m-d H:i:s"); // Current date and time
    $sql2 = "SELECT *
    FROM oeuvres
    JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
    JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
    JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
    WHERE  oeuvres.Id_Exposition = $id2 AND (exposition.Date_Fin <= '$currentDateTime' OR 'exposition.Date_Debut' >= '$currentDateTime')";
    $request2 = $db->query($sql2);
    $cardOeuvres = $request2->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="bottom" id="oeuvreExpo">
        <div class="wrapper">
            <i id="left" class="fa-solid fa-angle-left"></i>
            <ul class="carousel">
                <?php foreach ($cardOeuvres as $cardOeuvre) : ?>
                    <li class="card">
                        <div class="img"><img src="assets/images/artwork/<?= $cardOeuvre['chemin_Image']; ?>" alt="<?= $cardOeuvre['chemin_Image']; ?>"></div>
                        <div class="btn-voir-plus">
                            <button class="btn-afficher">
                                <a href="descriptionOeuvre.php?id=<?= $cardOeuvre["Id_oeuvre"]; ?>">Afficher plus</a>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <i id="right" class="fa-solid fa-angle-right"></i>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($_GET['id'])) :
    $id3 = $_GET['id'];
    $currentDateTime = date("Y-m-d H:i:s"); // Date et heure actuelles
    $sql3 = "SELECT *
    FROM oeuvres
    JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
    JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
    JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
    WHERE  oeuvres.Id_Exposition = $id3 AND exposition.Date_Debut < '$currentDateTime'";
    $request3 = $db->query($sql3);
    $infos = $request3->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h3><?php echo "Désolé, l'exposition n'a pas encore commencée."; ?></h3>
<?php endif; ?>


/**/
 */

 <div class="image-content-expo">
          <div class="card-image-expo">
            <img src="assets/images/artiste/<?= $Art['chemin_Imgart']; ?>" class="card-img-expo artiste-image" alt="Images de l'artiste">
          </div>


          /*back up */ */
          <?php

require_once "config/pdo.php";


$titre = "listeCollection";
$nav = "listeCollection";
include_once "includes/pages/nav.php";


$sqlCollec = "SELECT *
FROM oeuvres
JOIN type_oeuvre ON oeuvres.Id_Type = type_oeuvre.Id_Type
JOIN image ON image.Id_oeuvre = oeuvres.Id_oeuvre 
GROUP BY type_oeuvre.Id_Type";
$requeteCollec = $db->query($sqlCollec);
$Collecs = $requeteCollec->fetchAll(PDO::FETCH_ASSOC);

$sqlCollec1 = "SELECT *
FROM type_oeuvre";
$requeteCollec1= $db->query($sqlCollec1);
$Collecs1 = $requeteCollec1->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="container-infos-expo">
  <div class="expo-infos">
    <div class="expo-header">
      <h2 class="">Collections Disponibles</h2>
      <p class="date-time"><?php include_once "date.php" ?></p>
    </div>
    <div class="expo-lineinfos">
      <div class="expo-status">
        <div class="item-status">
          <span class="status-number"><?php echo count($Collecs);?></span>
          <span class="type-status">Nombre total oeuvres existantes</span>
        </div>  
        <div class="item-status">
          <span class="status-number"><?php echo count($Collecs1);?></span>
          <span class="type-status">Type(s) de collections</span>
        
        </div>
      </div>
    </div>
  </div>
</div>


<div class="expo-wrapper">

  <?php foreach ($Collecs as $Collec) : ?>
    <h2><?php $Collec['libelle_Type']; ?></h2>
    <div class="card-keeper-expo">
      <div class="card-expo" id="card-artiste">
      <div class="card-row2">
            <div class="image-artiste-ongoing" id="art-img">
                <img src="assets/images/artwork/<?php echo $Collec['chemin_Image']; ?>" alt="image collection">
            </div>

    </div>
        <div class="card-content-expo">
          <h4 class="libelle-expo"><?= $Collec["libelle_Oeuvre"]; ?></h4>

          <div class="keep-expo-btn">
          <button class="voir-plus-expo">
            <a href="descriptionOeuvre.php?id=<?= $Collec["Id_oeuvre"];?>">Voir plus</a>
          </button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>

<script src="./assets/javascript/script2.js"></script>



<?php
include_once "includes/pages/footer.php";
?>


/*description oeuvre  */

<?php

require_once "config/pdo.php";


$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT *
  FROM oeuvres
  JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
  JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
  JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
  WHERE  oeuvres.Id_oeuvre = $id";
  $request = $db->query($sql);
  $arts = $request->fetchAll(PDO::FETCH_ASSOC);
}

/* $sqlSelectLangue = "SELECT *
FROM langue
WHERE ";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);
 */

/* 
$sqlLangue = "SELECT * 
FROM langue
WHERE libelle_Langue = :libelle_Langue";
$queryLangue = $db->prepare($sqlLangue);
$queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
$queryLangue->execute();
$row = $queryLangue->fetch(PDO::FETCH_ASSOC);
$id_L = $row['Id_Langue'];
 */

/* $sqlDescription = "SELECT *
  FROM contenu
  JOIN langue ON contenu.Id_Langue = langue.Id_Langue
  JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
  WHERE  langue.Id_Langue = $id_L AND oeuvres.Id_oeuvre = $id";
$request1 = $db->query($sqlDescription);
$descriptions = $request1->fetchAll(PDO::FETCH_ASSOC);
 */

 if(isset($_SESSION['lang']) && in_array($_SESSION['lang'], $lang_array)) {
  $lang_code = $_SESSION['lang'];
 
  // Assuming $id_L and $id are the variables holding the respective IDs
  // Use prepared statements to prevent SQL injection
  $sqlDescription = "SELECT *
      FROM contenu
      JOIN langue ON contenu.Id_Langue = langue.Id_Langue
      JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
      WHERE langue.value_Langue = :value_Langue AND oeuvres.Id_oeuvre = :id";
 
  // Prepare the statement
  $stmt = $db->prepare($sqlDescription);
 
  // Bind parameters
  $stmt->bindParam(':value_Langue', $lang_code, PDO::PARAM_STR);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
 
  // Execute the query
  $stmt->execute();
 
  // Fetch the results
  $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
  // Handle the case where $_SESSION['lang'] is not set or not valid
  // You might want to set a default language or display an error message
}


?>
<!-- 
<div class="language">
  <form method="POST" class="formlanguage">
    <select name="langue" id="langue" class="langues">
      <?php foreach ($langues as $langue) : ?>
        <div class="item">
          <option value="<?php echo $langue['libelle_Langue']; ?>" class="">
            <?php echo $langue['libelle_Langue']; ?></option>
        </div>
      <?php endforeach; ?>
    </select>

    <input type="submit" name="submit" value="traduire">
  </form>
</div> -->







    <div class="artwork">
      <i class="fa-solid fa-chart-bar"></i>
     <div><?php echo DESC_H3;  ?></div>
      <?php foreach ($arts as $art) : ?>
        <img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">

    </div>


    <div class="description">
      <i class="fa-solid fa-palette"></i>
      <div><?php echo DESC1_H3;  ?></div>
      <p>
        <?php foreach ($descriptions as $description) {
          echo $description['description_Contenu'];
        } ?>

      </p>
    <?php endforeach ?>

    </div>






    <div class="additional-info">
      <i class="fa-solid fa-file-circle-plus"></i>
      <h3 class="title_additional_info">Informations supplémentaires</h3>
      <ul>

        <li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
        <li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
        <li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
        <li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
        <li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>

      </ul>
    </div>


    <div class="exhibition-video">
      <i class="fa-regular fa-circle-play"></i>
      <h3>Vidéo de l'exposition</h3>

      <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
    </div>

    <div class="audio-file">
      <i class="fa-solid fa-file-audio"></i>
      <h3>Fichier audio</h3>
      <audio controls>
        <source src="audio.mp3" type="audio/mpeg">
      </audio>
    </div>




<?php


include_once "includes/pages/footer.php";

?>

/*description oeuvre v2 */
<?php
require_once "config/pdo.php";
 
$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
//include_once "includes/pages/nav.php";
 
//$arts = []; // Initialize $arts as an empty array
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    echo $id;
    $sql = "SELECT *
            FROM oeuvres
            JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
            JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
            JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
            WHERE oeuvres.Id_oeuvre = :id"; // Use a parameterized query
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $arts = $stmt->fetchAll(PDO::FETCH_ASSOC);

var_dump($arts);

/* if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $lang_array)) {
    $lang_code = $_SESSION['lang'];
 
    $sqlDescription = "SELECT *
                        FROM contenu
                        JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                        JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
                        WHERE langue.value_Langue = :value_Langue AND oeuvres.Id_oeuvre = :id";
 
    $stmt1 = $db->prepare($sqlDescription);
 
    $stmt1->bindParam(':value_Langue', $lang_code, PDO::PARAM_STR);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
 
    $stmt1->execute();
 
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the case where $_SESSION['lang'] is not set or not valid
} */
}
?>
 
<div class="artwork">
<i class="fa-solid fa-chart-bar"></i>
<!-- <div><?php echo DESC_H3; ?></div> -->
<?php if (!empty($arts)) : ?>
<?php foreach ($arts as $art) : ?>
<img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">
<?php endforeach ?>
<?php endif ?>
</div>
 
<div class="description">
<i class="fa-solid fa-palette"></i>
<!-- <div><?php echo DESC1_H3; ?></div>
 --><p>
<?php if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                echo $description['description_Contenu'];
            }
        } ?>
</p>
</div>
 
<div class="additional-info">
<i class="fa-solid fa-file-circle-plus"></i>
<h3 class="title_additional_info">Informations supplémentaires</h3>
<ul>
<?php if (!empty($arts)) : foreach($arts as $art){ ?>
<li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
<li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
<li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
<li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
<li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>
<?php } ?>
<?php endif; ?>
</ul>
</div>
 
<div class="exhibition-video">
<i class="fa-regular fa-circle-play"></i>
<h3>Vidéo de l'exposition</h3>
 
    <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
</div>
 
<div class="audio-file">
<i class="fa-solid fa-file-audio"></i>
<h3>Fichier audio</h3>
<audio controls>
<source src="audio.mp3" type="audio/mpeg">
</audio>
</div>
 
<?php
include_once "includes/pages/footer.php";
?>

/*descriptionoeuvre v3 */
<?php
 
require_once "config/pdo.php";
$titre = "descriptionOeuvre";
$nav = "descriptionOeuvre";
include_once "includes/pages/nav.php";
 
// Check if both id and lang parameters are present
if (isset($_GET['id']) && isset($_GET['lang'])) {
    $id = $_GET['id'];
    $lang = $_GET['lang'];
 
    // Fetch object details based on ID
    $sql = "SELECT *
            FROM oeuvres
            JOIN image ON oeuvres.Id_oeuvre = image.Id_oeuvre
            JOIN artiste ON artiste.Id_Artiste = oeuvres.Id_Artiste
            JOIN exposition ON oeuvres.Id_Exposition = exposition.Id_Exposition
            WHERE oeuvres.Id_oeuvre = $id";
    $request = $db->query($sql);
/*     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute(); */
    $arts = $request->fetchAll(PDO::FETCH_ASSOC);
    var_dump($arts);

    // Fetch content in the selected language
    $sqlDescription = "SELECT *
                       FROM contenu
                       JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                       JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
                       WHERE langue.libelle_Langue = :lang AND oeuvres.Id_oeuvre = :id";
    $stmt1 = $db->prepare($sqlDescription);
    $stmt1->bindParam(':lang', $lang, PDO::PARAM_STR);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif (isset($_GET['lang'])) {
    // Only language parameter is present, fetch content in the selected language
    $lang = $_GET['lang'];
 
    // Fetch content in the selected language without object details
    $sqlDescription = "SELECT *
                       FROM contenu
                       JOIN langue ON contenu.Id_Langue = langue.Id_Langue
                       JOIN oeuvres ON contenu.Id_oeuvre = oeuvres.Id_oeuvre
                       WHERE langue.libelle_Langue = :lang";
    $stmt = $db->prepare($sqlDescription);
    $stmt->bindParam(':lang', $lang, PDO::PARAM_STR);
    $stmt->execute();
    $descriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Handle the case where neither id nor lang parameter is present
    // You can redirect the user to a default page or display an error message
    echo "Error: Missing parameters";
}
 
// Rest of your code to display the page content based on fetched data
 
?>
 
<div class="artwork">
<i class="fa-solid fa-chart-bar"></i>
<!-- <div><?php echo DESC_H3; ?></div> -->
<?php if (!empty($arts)) : ?>
<?php foreach ($arts as $art) : ?>
<img src="assets/images/artwork/<?= $art['chemin_Image']; ?>" alt="<?= $art['libelle_Oeuvre']; ?>">
<?php endforeach ?>
<?php endif ?>
</div>
 
<div class="description">
<i class="fa-solid fa-palette"></i>
<!-- <div><?php echo DESC1_H3; ?></div>
 --><p>
<?php if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                echo $description['description_Contenu'];
            }
        } ?>
</p>
</div>
 
<div class="additional-info">
<i class="fa-solid fa-file-circle-plus"></i>
<!-- <div><?php echo ADDINFOS_H3; ?></div>
<ul>
<?php if (!empty($arts)) : foreach($arts as $art){ ?>
<li><strong>Artiste : </strong><?= $art['Prenom_Artiste'] . " "; ?><?= $art['Nom_Artiste']; ?></li>
<li><strong>Libellé de l'oeuvre : </strong><?= $art['libelle_Oeuvre']; ?></li>
<li><strong>Prix : </strong><?= $art['prix']; ?>€</li>
<li><strong>Dimensions : </strong><?= " " . $art['hauteur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['largeur_Oeuvre'] . " "; ?><i class="fa-solid fa-xmark"></i><?= " " . $art['profondeur_Oeuvre'] . " "; ?></li>
<li><strong>Date de l'exposition : </strong><?= date('d-m-y', strtotime($art['Date_Debut'])) . " "; ?>-<?= " " . date('d-m-y', strtotime($art['Date_Fin'])); ?></li>
<?php } ?>
<?php endif; ?>
</ul>
</div>
 
<div class="exhibition-video">
<i class="fa-regular fa-circle-play"></i>
<h3>Vidéo de l'exposition</h3>
 
    <iframe class="video" width="560" height="315" src="https://www.youtube.com/embed/VIDEO_ID" frameborder="0" allowfullscreen></iframe>
</div>
 
<div class="audio-file">
<i class="fa-solid fa-file-audio"></i>
<h3>Fichier audio</h3>
<audio controls>
<source src="audio.mp3" type="audio/mpeg">
</audio>
</div>
 
<?php
include_once "includes/pages/footer.php";
?>




<?php
require_once "config/pdo.php";

$titre = "listeCollection";
$nav = "listeCollection";
include_once "includes/pages/nav.php";

$sqlCollec = "SELECT *
              FROM oeuvres
              JOIN type_oeuvre ON oeuvres.Id_Type = type_oeuvre.Id_Type
              JOIN image ON image.Id_oeuvre = oeuvres.Id_oeuvre";
$requeteCollec = $db->query($sqlCollec);
$Collecs = $requeteCollec->fetchAll(PDO::FETCH_ASSOC);

?>





$sqlSelectLangue = "SELECT *
FROM langue";
$requestLangue = $db->query($sqlSelectLangue);
$langues = $requestLangue->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT DISTINCT *
    FROM artiste
    JOIN bio_artist ON artiste.Id_Artiste = bio_artist.Id_Artiste
    WHERE  artiste.Id_Artiste = $id
    GROUP BY artiste.Id_Artiste";
    $request = $db->query($sql);
    $artistes = $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Cet artiste n'existe pas.";
}

if (isset($_GET['id'])) {
    $id2 = $_GET['id'];
        if (isset($_POST['submit'])) {
        $langue = $_POST['langue'];
      } else {
        $langue = "Français";
      }
      $sqlLangue = "SELECT * 
      FROM langue
      WHERE libelle_Langue = :libelle_Langue";
      $queryLangue = $db->prepare($sqlLangue);
      $queryLangue->bindParam(":libelle_Langue", $langue, PDO::PARAM_STR);
      $queryLangue->execute();
      $row = $queryLangue->fetch(PDO::FETCH_ASSOC);
      $id_L2 = $row['Id_Langue'];

    $sqlBio = "SELECT *
    FROM bio_artist
    JOIN langue ON bio_artist.Id_Langue = langue.Id_Langue
    JOIN artiste ON bio_artist.Id_Artiste = artiste.Id_Artiste
    WHERE  bio_artist.Id_Langue = $id_L2 AND bio_artist.Id_Artiste = $id2";
    $requestbio = $db->query($sqlBio);
    $Bios = $requestbio->fetchAll(PDO::FETCH_ASSOC);
}






if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT *
    FROM exposition
    WHERE  exposition.Id_Exposition = $id";
    $request = $db->query($sql);
    $expos = $request->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Il n'y a plus d'exposition";
}



if (isset($_GET['id'])) {
    $id1 = $_GET['id'];
    $sql1 = "SELECT *
    FROM artiste 
    JOIN exposition ON artiste.Id_Artiste= exposition.Id_Artiste
    JOIN bio_artist ON artiste.Id_Artiste= bio_artist.Id_Artiste
    WHERE  exposition.Id_Exposition = $id1 AND bio_artist.Id_Langue=1";
    $request1 = $db->query($sql1);
    $cardExpos = $request1->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Il n'y a plus d'exposition";
}
