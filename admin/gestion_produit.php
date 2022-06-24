<?php
require_once('../inc/init.php');




// préparer a isérer les données dans la table produit

if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
    }

    if (empty($_POST['dateArrivee']) || validateDate($_POST['dateArrivee']) != true) {
        $errorMessages .= 'veuillez insérer une date d\'arivée valide' . '<br>';
    }
    if (empty($_POST['dateDepart']) || validateDate($_POST['dateDepart']) != true || $_POST['dateDepart'] < $_POST['dateArrivee']) {
        $errorMessages .= 'veuillez insérer une date de depart valide' . '<br>';
    }
    if (empty($_POST['prix']) || is_numeric($_POST['prix']) && strlen(trim($_POST['prix'])) > 4) {
        $errorMessages .= "veuillez insérer une prix valide !" . '<br>';
    }
    $array = array('libre', 'reservation');
    if (empty($_POST['etat']) || !in_array($_POST['etat'], $array)) {
        $errorMessages .= "veuillez insérer un etat valide !" . '<br>';
    }
    if (empty($_POST['salle']) || is_numeric($_POST['salle']) && strlen(trim($_POST['salle'])) > 3) {
        $errorMessages .= "veuillez insérer une salle valide !" . '<br>';
    }

    if (empty($errorMessages)) {

        $req = $bdd->prepare('INSERT INTO produit (date_arrivee,date_depart,prix,etat,id_salle) VALUES ( 
        :date_arrivee,:date_depart,:prix,:etat,:id_salle)');
        $res = $req->execute([
            'id_salle' => $_POST['salle'],
            'date_arrivee' => $_POST['dateArrivee'],
            'date_depart' => $_POST['dateDepart'],
            'prix' => $_POST['prix'],
            'etat' => $_POST['etat']
        ]);

        if ($res) {
            $successMessages = 'vous venez d\'ajouter le produit' . '<br>';
        } else {
            $errorMessages .= 'Erreur lors de l\'insertion' . '<br>';
        }
    }
}


// afficher les produits sur un table

$req = $bdd->query('SELECT * FROM produit AS p INNER JOIN salle AS s ON p.id_salle=s.id_salle');
$produits = $req->fetchAll(PDO::FETCH_ASSOC);

// debug($produits);

// supprimer les produits

if (isset($_GET['action']) && $_GET['action'] == 'supprimer') {

    $req = $bdd->prepare('DELETE FROM produit WHERE id_produit =:id_produit');
    $res = $req->execute([
        'id_produit' => $_GET['id_produit']
    ]);
    if ($res) {
        $successMessages = 'le produit à bien été supprimer';
    } else {
        $errorMessages .= 'Erreur lors de la suppression';
    }
}

// Modification des produits

if (isset($_GET['action']) && $_GET['action'] == 'modifier') {

    // on cherche les informations de la table produit
    $req = $bdd->prepare('SELECT * FROM produit AS p INNER JOIN salle AS s ON p.id_salle=s.id_salle AND id_produit=:id_produit');
    $req->execute([
        'id_produit' => $_GET['id_produit']
    ]);
    $produit_modif = $req->fetch(PDO::FETCH_ASSOC);

    // debug($produit_modif);
    if (!empty($_POST)) {

        foreach ($_POST as $key => $value) {
            $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        if (empty($_POST['dateArrivee']) || validateDate($_POST['dateArrivee']) != true) {
            $errorMessages .= 'veuillez insérer une date d\'arivée valide' . '<br>';
        }
        if (empty($_POST['dateDepart']) || validateDate($_POST['dateDepart']) != true || $_POST['dateDepart'] < $_POST['dateArrivee']) {
            $errorMessages .= 'veuillez insérer une date de depart valide' . '<br>';
        }
        if (empty($_POST['prix']) || is_numeric($_POST['prix']) && strlen(trim($_POST['prix'])) > 4) {
            $errorMessages .= "veuillez insérer une prix valide !" . '<br>';
        }
        $array = array('libre', 'reservation');
        if (empty($_POST['etat']) || !in_array($_POST['etat'], $array)) {
            $errorMessages .= "veuillez insérer un etat valide !" . '<br>';
        }
        if (empty($_POST['salle']) || is_numeric($_POST['salle']) && strlen(trim($_POST['salle'])) > 3) {
            $errorMessages .= "veuillez insérer une salle valide !" . '<br>';
        }

        if (empty($errorMessages)) {
            $req=$bdd->prepare('UPDATE produit  SET date_arrivee=:date_arrivee, 
            date_depart=:date_depart,prix=:prix,etat=:etat, id_salle=:id_salle');
            $res=$req->execute([
                'date_arrivee'=>$_POST['dateArrivee'],
                'date_depart'=>$_POST['dateDepart'],
                'prix'=>$_POST['prix'],
                'etat'=>$_POST['etat'],
                'id_salle'=>$_POST['salle']
            ]);
         if ($res) {
             $successMessages='votre produit a bien été mise a jour'.'<br>';
         }else {
             $errorMessages .='Erreur lors de la modification'.'<br>';
         }  

             
        
        }

    }
}







$title = 'Gestion des produits';
require_once('../inc/header.php');
require_once('../inc/nav.php');
?>

<!-- Affichage PHP-->
<h1 class="text-center"> Gestion des produits</h1>




<h4 class="text-center mt-5">ajout d'un produit</h4>


<!-- "Affichage des Messages d'erreur et de success" -->


<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>
<?php
if (!empty($errorMessages)) { ?>

    <div class="alert alert-warning d-flex justify-content-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
            <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        <div>
            <?= $errorMessages; ?>
        </div>
    </div>
<?php
}
if (!empty($successMessages)) { ?>

    <div class="alert alert-success d-flex justify-content-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        </svg>
        <div>
            <?= $successMessages; ?>
        </div>
    </div>
<?php
}
?>


<form action="" class="container col-3 mx-auto mt-3" method="post">


    <label for="dateArrivee" class="form-label">date d'arrivée</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <button type="button" id="toggle1" style="width: 50px; height:50px" class="input-group-text"><i class="fa fa-calendar-alt fa-2x"></i></button>
        </div>
        <input type="text" name="dateArrivee" id="picker1" class="form-control">

    </div>


    <label for="dateDepart" class="form-label">date de départ</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <button type="button" id="toggle2" style="width: 50px; height:50px" class="input-group-text"><i class="fa fa-calendar-alt fa-2x"></i></button>
        </div>
        <input type="text" name="dateDepart" class="form-control" id="picker2">
    </div>

    <label for="prix" class="form-label">prix</label>
    <input type="number" class="form-control" id="prix" name="prix" value="">

    <label for="etat" class="form-label">état</label>
    <select name="etat" id="etat" class="form-control">
        <option selected>Choisir un état</option>
        <option>libre</option>
        <option>reservation</option>

    </select>

    <label for="salle" class="form-label">salle</label>
    <input type="number" class="form-control" id="salle" name="salle" value="">

    <div class="d-flex justify-content-center">
        <button class="btn btn-dark mt-3">Ajouter un produit</button>
    </div>

</form>

<div class="table-responsive text-center mt-5">
    <table class="table table-condensed">

        <thead class="bg-secondary text-white">
            <th>id_produit</th>
            <th>date d'arrivee</th>
            <th>date de depart</th>
            <th>id_salle</th>
            <th>prix</th>
            <th>etat</th>
            <th>actions</th>
            <th><span></span></th>
        </thead>

        <?php foreach ($produits as $produit) { ?>
            <tr>
                <td><?= $produit['id_produit'] ?></td>
                <td><?= $produit['date_arrivee'] ?></td>
                <td><?= $produit['date_depart'] ?></td>
                <td>
                    <h6><?= $produit['id_salle'] . '-' . $produit['titre'] ?></h6>
                    <img src="<?= $produit['photo'] ?>" width="100px" alt="<?= $produit['photo'] ?>">
                </td>
                <td><?= $produit['prix'] . ' €' ?></td>
                <td><?= $produit['etat'] ?></td>

                <td><a href="gestion_produit.php?action=supprimer&id_produit=<?= $produit['id_produit'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');"><img src="https://img.icons8.com/external-kiranshastry-solid-kiranshastry/30/000000/external-delete-multimedia-kiranshastry-solid-kiranshastry.png" /></a></td>
                <td><a href="gestion_produit.php?action=modifier&id_produit=<?= $produit['id_produit'] ?>"><img src="https://img.icons8.com/ios-filled/30/000000/edit-pie-chart-report.png" /></a></td>


            </tr>
        <?php
        }
        ?>


    </table>

</div>

<!-- Affichage de produit a modifié-->
<?php
if (isset($_GET['action']) && $_GET['action'] == 'modifier') { ?>

    <h4 class="text-center mt-5">Modifier un produit</h4>

    <form action="" class="container col-3 mx-auto mt-3" method="post">


        <label for="dateArrivee" class="form-label">date d'arrivée</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="button" id="toggle3" style="width: 50px; height:50px" class="input-group-text"><i class="fa fa-calendar-alt fa-2x"></i></button>
            </div>
            <input type="text" name="dateArrivee" id="picker3" class="form-control" value="<?= $produit_modif['date_arrivee'] ?>">

        </div>


        <label for="dateDepart" class="form-label">date de départ</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="button" id="toggle4" style="width: 50px; height:50px" class="input-group-text"><i class="fa fa-calendar-alt fa-2x"></i></button>
            </div>
            <input type="text" name="dateDepart" class="form-control" id="picker4" value="<?= $produit_modif['date_depart'] ?>"">  
     </div>
    
      <label for=" prix" class="form-label">prix</label>
            <input type="number" class="form-control" id="prix" name="prix" value="<?= $produit_modif['prix'] ?>"">

     <label for=" etat" class="form-label">état</label>
            <select name="etat" id="etat" class="form-control">
                <option <?= ($produit_modif['etat']) == 'libre' ? 'selected' : '' ?>>libre</option>
                <option <?= ($produit_modif['etat']) == 'reservation' ? 'selected' : '' ?>>reservation</option>

            </select>

            <label for="salle" class="form-label">salle</label>
            <input type="number" class="form-control" id="salle" name="salle" value="<?= $produit_modif['id_salle'] ?>"">

     <div class=" d-flex justify-content-center">
            <button class="btn btn-dark mt-3">modifier</button>
        </div>

    </form>



<?php
}
?>


<?php
require_once('../inc/footer.php');
