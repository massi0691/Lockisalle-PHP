<?php
require_once('../inc/init.php');


// insertion des données de la salle 

if (!empty($_POST)) {
   foreach ($_POST as $key => $value) {
      $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
   }

   if (empty($_POST['titre']) || strlen(trim($_POST['titre'])) < 4 || strlen(trim($_POST['titre'])) > 200) {
      $errorMessages .= "veuillez insérer un titre situé entre 4 et 200 caractéres !" . '<br>';
   }
   if (empty($_POST['description']) || strlen(trim($_POST['description'])) < 5) {
      $errorMessages .= "veuillez insérer une description !" . '<br>';
   }

   if (empty($_POST['pays']) || strlen(trim($_POST['pays'])) < 2 || strlen(trim($_POST['pays'])) > 20) {
      $errorMessages .= "veuillez insérer un pays valide !" . '<br>';
   }
   if (empty($_POST['ville']) || strlen(trim($_POST['ville'])) < 2 || strlen(trim($_POST['ville'])) > 20) {
      $errorMessages .= "veuillez insérer une ville valide !" . '<br>';
   }
   if (empty($_POST['adresse']) || strlen(trim($_POST['adresse'])) < 8 || strlen(trim($_POST['adresse'])) > 50) {
      $errorMessages .= "veuillez insérer une adresse valide !" . '<br>';
   }
   if (empty($_POST['cp']) || is_numeric($_POST['cp']) && strlen(trim($_POST['cp'])) !== 5) {
      $errorMessages .= "veuillez insérer un cp valide !" . '<br>';
   }
   if (empty($_POST['capacite']) || is_numeric($_POST['capacite']) && strlen(trim($_POST['capacite'])) > 3) {
      $errorMessages .= "veuillez insérer une capacite valide !" . '<br>';
   }
   $array = array('reunion', 'bureau', 'formation');

   if (!isset($_POST['categorie']) || !in_array($_POST['categorie'], $array)) {
      $errorMessages .= "veuillez choisir une catégorie !" . '<br>';
   }

   $photo = '';

   if (!empty($_FILES['photo']['name'])) {
      $photo = '../assets/images/' . $_FILES['photo']['name'];

      copy($_FILES['photo']['tmp_name'], $photo);
   } else {
      $errorMessages .= "veuillez inserez une photo de la salle !" . '<br>';
   }


   if (empty($errorMessages)) {
      $req = $bdd->prepare('INSERT INTO salle VALUES (null, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)');
      $res = $req->execute([
         'titre' => $_POST['titre'],
         'description' => $_POST['description'],
         'pays' => $_POST['pays'],
         'ville' => $_POST['ville'],
         'adresse' => $_POST['adresse'],
         'cp' => $_POST['cp'],
         'capacite' => $_POST['capacite'],
         'categorie' => $_POST['categorie'],
         'photo' => $photo
      ]);
      if ($res) {
         $successMessages = "la salle a bien été ajoutée " . '<br>';
      } else {
         $errorMessages .= "Erreur lors de l'insertion " . '<br>';
      }
   }
}

// Affichages des salles dans un tableau

$req = $bdd->query('SELECT * FROM salle');
$salles = $req->fetchAll(PDO::FETCH_ASSOC);


// debug($salles);


// supprimer les salles///////////////////////

if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {
   $req = $bdd->prepare('DELETE FROM salle WHERE id_salle =:id_salle');
   $result = $req->execute([
      'id_salle' => $_GET['id_salle']
   ]);

   if ($result) {
      $successMessages = 'la salle à bien été supprimer';
   } else {
      $errorMessages .= 'la salle  n\'a pas été supprimer';
   }
}

// modification des salles////////////////////////////////////////

if (isset($_GET['action']) && $_GET['action'] == "modifier") {

   // recupération des données et maittres dans le formulaire

   $req = $bdd->prepare('SELECT * FROM salle WHERE id_salle =:id_salle ');
   $req->execute([
      'id_salle' => $_GET['id_salle']
   ]);

   $salle_modifie = $req->fetch(PDO::FETCH_ASSOC);

   // debug($salle_modifie);

   if (!empty($_POST)) {
      foreach ($_POST as $key => $value) {
         $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
      }
   
      if (empty($_POST['titre']) || strlen(trim($_POST['titre'])) < 4 || strlen(trim($_POST['titre'])) > 200) {
         $errorMessages .= "veuillez insérer un titre situé entre 4 et 200 caractéres !" . '<br>';
      }
      if (empty($_POST['description']) || strlen(trim($_POST['description'])) < 5) {
         $errorMessages .= "veuillez insérer une description !" . '<br>';
      }
   
      if (empty($_POST['pays']) || strlen(trim($_POST['pays'])) < 2 || strlen(trim($_POST['pays'])) > 20) {
         $errorMessages .= "veuillez insérer un pays valide !" . '<br>';
      }
      if (empty($_POST['ville']) || strlen(trim($_POST['ville'])) < 2 || strlen(trim($_POST['ville'])) > 20) {
         $errorMessages .= "veuillez insérer une ville valide !" . '<br>';
      }
      if (empty($_POST['adresse']) || strlen(trim($_POST['adresse'])) < 8 || strlen(trim($_POST['adresse'])) > 50) {
         $errorMessages .= "veuillez insérer une adresse valide !" . '<br>';
      }
      if (empty($_POST['cp']) || is_numeric($_POST['cp']) && strlen(trim($_POST['cp'])) !== 5) {
         $errorMessages .= "veuillez insérer un cp valide !" . '<br>';
      }
      if (empty($_POST['capacite']) || is_numeric($_POST['capacite']) && strlen(trim($_POST['capacite'])) > 3) {
         $errorMessages .= "veuillez insérer une capacite valide !" . '<br>';
      }
      $array = array('reunion', 'bureau', 'formation');
   
      if (!in_array($_POST['categorie'], $array)) {
         $errorMessages .= "veuillez choisir une catégorie !" . '<br>';
      }
   
      $photo = '';
   
      if (!empty($_FILES['photo']['name'])) {
         $photo = '../assets/images/' . $_FILES['photo']['name'];
   
         copy($_FILES['photo']['tmp_name'], $photo);
      } else {
         $errorMessages .= "veuillez inserez une photo de la salle !" . '<br>';
      }
   
      if (empty($errorMessages)) {
         
         $req=$bdd->prepare('UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville= :ville, adresse= :adresse, 
         cp= :cp, capacite= :capacite, categorie= :categorie WHERE id_salle=:id_salle');
          $res=$req->execute([
             'titre'=>$_POST['titre'],
             'description'=>$_POST['description'],
             'pays'=>$_POST['pays'],
             'ville'=>$_POST['ville'],
             'adresse'=>$_POST['adresse'],
             'cp'=>$_POST['cp'],
             'capacite'=>$_POST['capacite'],
             'categorie'=>$_POST['categorie'],
             'photo'=>$photo,
             'id_salle'=> $salle_modifie['id_salle']
          ]);

          if ($res) {
             $successMessages = 'votre salle a bien été modifier !'.'<br>';
          }else{
             $errorMessages .='Erreur lors de la modification de la salle'.'<br>';
          }

      }
   } 

   
}









//Affichage

$title = 'Gestion des salles';
require_once('../inc/header.php');
require_once('../inc/nav.php');



?>
<h1 class="text-center"> Gestion des salles</h1>
<div class="container mx-auto col-6">


   <!-- Affichage des messages d'erreurs et de succés lors de l'insertion-->

   <?php
   if (!empty($successMessages)) { ?>
      <div class="alert alert-success col-6 mx-auto text-center">
         <?= $successMessages ?>
      </div>
   <?php
   }
   ?>
   <?php if (!empty($errorMessages)) { ?>
      <div class="alert alert-danger col-6 mx-auto text-center">
         <?= $errorMessages ?>
      </div>
   <?php
   }
   ?>







   <form method="POST" action="" enctype="multipart/form-data">
      <h4 class="text-center mt-5">Ajout de la salle</h4>

      <label for="titre" class="form-label">titre</label>
      <input type="text" class="form-control" id="titre" name="titre">
      <label for="description" class="form-label">description</label>
      <textarea rows="3" cols="" name="description" class="form-control"></textarea>
      <label for="photo" class="form-label">photo</label>
      <input type="file" name="photo" id="photo" class="form-control">
      <label for="pays" class="form-label">pays</label>
      <input type="text" class="form-control" id="pays" name="pays">
      <label for="ville" class="form-label">ville</label>
      <input type="text" class="form-control" id="ville" name="ville">
      <label for="adresse" class="form-label">adresse</label>
      <input type="text" class="form-control" id="adresse" name="adresse">
      <label for="cp" class="form-label">cp</label>
      <input type="number" class="form-control" id="cp" name="cp">
      <label for="capacite" class="form-label">capacite</label>
      <input type="number" class="form-control" id="capacite" name="capacite">
      <label for="categorie" class="form-label">categorie</label>
      <select name="categorie" id="categorie" class="form-control">
         <option value="" selected>Choisir une catégorie</option>
         <option value="reunion">reunion</option>
         <option value="bureau">bureau</option>
         <option value="formation">formation</option>
      </select>

      <div class="d-flex justify-content-center m-3">
         <button class="btn btn-primary mt-3" type="submit">Ajouter la salle</button>
      </div>

   </form>
</div>

<div class="table-responsive text-center">
   <table class="table table-condensed">

      <thead class="bg-secondary text-white">
         <th>id_salle</th>
         <th>titre</th>
         <th>description</th>
         <th>photo</th>
         <th>pays</th>
         <th>ville</th>
         <th>adresse</th>
         <th>cp</th>
         <th>capacite</th>
         <th>catégorie</th>
         <th>actions</th>
      </thead>

      <?php foreach ($salles as $salle) { ?>
         <tr>
            <td><?= $salle['id_salle'] ?></td>
            <td><?= $salle['titre'] ?></td>
            <td><?= $salle['description'] ?></td>
            <td> <img src="<?= $salle['photo'] ?>" width="200px" alt="<?= $salle['photo'] ?>"></td>
            <td><?= $salle['pays'] ?></td>
            <td><?= $salle['ville'] ?></td>
            <td><?= $salle['adresse'] ?></td>
            <td><?= $salle['cp'] ?></td>
            <td><?= $salle['capacite'] ?></td>
            <td><?= $salle['categorie'] ?></td>
            <td><a href="gestion_salle.php?action=supprimer&id_salle=<?= $salle['id_salle'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');"><img src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/30/000000/external-delete-multimedia-kiranshastry-solid-kiranshastry.png" /></a></td>
            <td><a href="gestion_salle.php?action=modifier&id_salle=<?= $salle['id_salle'] ?>"><img src="https://img.icons8.com/ios-filled/30/000000/edit-pie-chart-report.png" /></a></td>


         </tr>
      <?php
      }
      ?>


   </table>

</div>

<?php
if (isset($_GET['action']) && $_GET['action'] == "modifier") { ?>

   <form method="POST" action="" enctype="multipart/form-data" class="container mx-auto col-6">
      <h4 class="text-center mt-5">Modifier la salle</h4>

      <label for="titre" class="form-label">titre</label>
      <input type="text" class="form-control" id="titre" name="titre" value="<?= $salle_modifie['titre']; ?>">
      <label for="description" class="form-label">description</label>
      <textarea rows="3" cols="" name="description" class="form-control"><?= $salle_modifie['description']; ?> </textarea>
      <label for="photo" class="form-label">photo</label>
      <input type="file" name="photo" id="photo" class="form-control">
      <label for="pays" class="form-label">pays</label>
      <input type="text" class="form-control" id="pays" name="pays" value="<?= $salle_modifie['pays']; ?>">
      <label for="ville" class="form-label">ville</label>
      <input type="text" class="form-control" id="ville" name="ville" value="<?= $salle_modifie['ville']; ?>">
      <label for="adresse" class="form-label">adresse</label>
      <input type="text" class="form-control" id="adresse" name="adresse" value="<?= $salle_modifie['adresse']; ?>">
      <label for="cp" class="form-label">cp</label>
      <input type="number" class="form-control" id="cp" name="cp" value="<?= $salle_modifie['cp']; ?>">
      <label for="capacite" class="form-label">capacite</label>
      <input type="number" class="form-control" id="capacite" name="capacite" value="<?= $salle_modifie['capacite']; ?>">
      <label for="categorie" class="form-label">categorie</label>
      <select name="categorie" id="categorie" class="form-control">
         <option value="reunion" <?php if ($salle_modifie['categorie'] === 'reunion') echo 'selected'; ?>>reunion</option>
         <option value="bureau" <?php if ($salle_modifie['categorie'] === 'bureau') echo 'selected'; ?>>bureau</option>
         <option value="formation" <?php if ($salle_modifie['categorie'] === 'formation') echo 'selected'; ?>>formation</option>
      </select>

      <div class="d-flex justify-content-center m-3">
         <button class="btn btn-primary mt-3" type="submit">modifier la salle</button>
      </div>

   </form>



<?php
}
?>