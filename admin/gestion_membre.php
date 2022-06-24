<?php
require_once('../inc/init.php');


// selectionner tout les membres de la table memebre

$req = $bdd->query('SELECT id_membre, pseudo, nom, prenom, email, civilite,statut, date_enregistrement FROM membre');

$membres = $req->fetchAll(PDO::FETCH_ASSOC);

// debug($membres);

// gestion d'ajout d'un membre pour la gestion de la base de donnée


if (!empty($_POST)) {

    // on doit vérifier les champs inscrit dans le formulaire et on  affiche les messages d'erreurs si on pas respecter les condition
    //1- on converit les caractéres html en utilisant htmlchars
    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
    }


    if (empty($_POST['pseudo']) || strlen(trim($_POST['pseudo'])) < 4 || strlen(trim($_POST['pseudo'])) > 20) {
        $errorMessages .= "votre pseudo doit contenir entre 4 et 20 caractéres" . '<br>';
    }
    if (empty($_POST['mdp']) || strlen(trim($_POST['mdp'])) < 4 || strlen(trim($_POST['mdp'])) > 50) {
        $errorMessages .= "votre Mot de pass doit contenir entre 4 et 50 caractéres" . '<br>';
    }


    if (empty($_POST['nom']) || strlen(trim($_POST['nom'])) < 4 || strlen(trim($_POST['nom'])) > 50) {
        $errorMessages .= "votre nom doit contenir entre 4 et 50 caractéres" . '<br>';
    }
    if (empty($_POST['prenom']) || strlen(trim($_POST['prenom'])) < 4 || strlen(trim($_POST['prenom'])) > 50) {
        $errorMessages .= "votre prenom doit contenir entre 4 et 50 caractéres" . '<br>';
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMessages .= "entrer un email Valide" . '<br>';
    }
    $civilite = ['f', 'm'];
    if (!isset($_POST['civilite']) || !in_array($_POST['civilite'], $civilite)) {
        $errorMessages .= "veuillez choisir votre civilité" . '<br>';
    }
     
    // checker si le pseudo existe ou non dan la base de donnée
    if (isset($_POST['pseudo'])) {
        $pseudo = $_POST['pseudo'];

        
        $checkIfPseudoExist = $bdd->prepare("SELECT count(*) as cntMembre FROM membre WHERE pseudo=:pseudo");
        $checkIfPseudoExist->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $checkIfPseudoExist->execute();
        $count = $checkIfPseudoExist->fetchColumn();

        if ($count > 0) {
            $errorMessages .= "le pseudo existe déja";
        }
    }



    if (empty($errorMessages)) {
        $requette = $bdd->prepare('INSERT INTO membre (pseudo,mdp, nom, prenom,email,civilite,statut,date_enregistrement) VALUES ( :pseudo ,:mdp, :nom, :prenom,:email,:civilite, :statut, :date_enregistrement)');
        $result = $requette->execute([
            'pseudo' => $_POST['pseudo'],
            'mdp'=>md5($_POST['mdp']),
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'civilite' => $_POST['civilite'],
            'statut' => 1,
            'date_enregistrement' => $newDate


        ]);

        if ($result === true) {
            $successMessages .= " vous venez d'inscrire";
        } else {
            $errorMessages .= "Erreur lors de l'inscription";
        }
    }
}






// gestion de la suppression du membre

if (isset($_GET['action']) && $_GET['action'] == "supprimer") {
    $req = $bdd->prepare('DELETE FROM membre WHERE id_membre=:id_membre');
    $result = $req->execute([
        'id_membre' => $_GET['id_membre']
    ]);
    if ($result) {
        $successMessages .= 'le membre à bien été supprimer';
    } else {
        $errorMessages .= 'le membre n\'a pas été supprimer';
    }
}







// gestion de la modification des membres

if (isset($_GET['action']) && $_GET['action'] == "modifier") {

    $_GET['id_membre'] = htmlspecialchars($_GET['id_membre'], ENT_QUOTES);

    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
            $_POST[$key] = trim($value);
        }

        if (empty($_POST['pseudo']) || strlen(trim($_POST['pseudo'])) < 4 || strlen(trim($_POST['pseudo'])) > 20) {
            $errorMessages .= "votre pseudo doit contenir entre 4 et 20 caractéres" . '<br>';
        }
    
    
        if (empty($_POST['nom']) || strlen(trim($_POST['nom'])) < 4 || strlen(trim($_POST['nom'])) > 50) {
            $errorMessages .= "votre nom doit contenir entre 4 et 50 caractéres" . '<br>';
        }
        if (empty($_POST['prenom']) || strlen(trim($_POST['prenom'])) < 4 || strlen(trim($_POST['prenom'])) > 50) {
            $errorMessages .= "votre prenom doit contenir entre 4 et 50 caractéres" . '<br>';
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessages .= "entrer un email Valide" . '<br>';
        }
        $civilite = ['f', 'm'];
        if (!isset($_POST['civilite']) || !in_array($_POST['civilite'], $civilite)) {
            $errorMessages .= "veuillez choisir votre civilité" . '<br>';
        }
         
     

        if (empty($errorMessages)) {


            $req = $bdd->prepare('UPDATE membre SET pseudo =:pseudo, nom=:nom ,prenom=:prenom, email=:email, 
    civilite=:civilite,statut=:statut, date_enregistrement=:date_enregistrement WHERE id_membre=:id_membre');
            $result = $req->execute([
                'pseudo' => $_POST['pseudo'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'civilite' => $_POST['civilite'],
                'statut' => $_POST['statut'],
                'date_enregistrement' => $newDate,
                'id_membre' => $_GET['id_membre']
            ]);

            if ($result) {
                $successMessage = 'Le membre à bien été modifier dans la BDD';
            } else {
                $errorMessage = 'Erreur lors de la modiification en BDD';
            }
        }
    }

    $req = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $req->execute([
        'id_membre' => $_GET['id_membre']
    ]);

    $membre_modifie = $req->fetch(PDO::FETCH_ASSOC);
}




$title = 'Gestion des membres';
require_once('../inc/header.php');
require_once('../inc/nav.php');

?>

<!-- Affichage PHP-->
<h1 class="text-center"> Gestion des membres</h1>


<!-- formulaire d'ajout d'un membre dans la bdd-->

<h4 class="text-center mt-5">ajout d'un membre</h4>


<form action="" class="col-3 mx-auto" method="post">

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



    <label for="pseudo" class="form-label">Pseudo</label>
    <input type="text" name="pseudo" id="pseudo" class="form-control" value="">
    <label for="mdp" class="form-label">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" class="form-control" value="">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" name="nom" id="nom" class="form-control" value="">
    <label for="prenom" class="form-label">Prenom</label>
    <input type="text" name="prenom" id="prenom" class="form-control" value="">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="">
    <label for='civilite' class="form-label">Civilite</label>
    <select name="civilite" class="form-select">
        <option>m</option>
        <option>f</option>
    </select>
  

    <div class="d-flex justify-content-center">
        <button class="btn btn-dark mt-3">Ajouter un membre</button>
    </div>

</form>
<!-- affichage du messages de succés ou d'echec lors de la suppression  -->

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

<div class="table-responsive">
    <table class="table">
        <thead>
            <th>id_membre</th>
            <th>pseudo</th>
            <th>nom</th>
            <th>prenom</th>
            <th>email</th>
            <th>civilite</th>
            <th>statut</th>
            <th>date_enregistrement</th>

        </thead>

        <tbody>
            <?php
            foreach ($membres as $membre) { ?>
                <tr>
                    <td><?= $membre['id_membre'] ?></td>
                    <td><?= $membre['pseudo'] ?></td>
                    <td><?= $membre['nom'] ?></td>
                    <td><?= $membre['prenom'] ?></td>
                    <td><?= $membre['email'] ?></td>
                    <td><?= $membre['civilite'] ?></td>
                    <td><?= $membre['statut'] ?></td>
                    <td><?= $membre['date_enregistrement'] ?></td>
                    <td><a href="gestion_membre.php?action=supprimer&id_membre=<?= $membre['id_membre'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');"><img src="https://img.icons8.com/ios-filled/30/000000/remove-user-male.png" /></a></td>
                    <td><a href="gestion_membre.php?action=modifier&id_membre=<?= $membre['id_membre'] ?>"><img src="https://img.icons8.com/ios-glyphs/30/000000/change-user-male.png" /></a></td>
                </tr>

            <?php
            }
            ?>
        </tbody>


    </table>
</div>

<?php if (isset($_GET['action']) && $_GET['action'] == "modifier") {?> 

    <h4 class="text-center"> modification des membres</h4>
    
    <form action="" class="col-3 mx-auto" method="post">

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



    <label for="pseudo" class="form-label">Pseudo</label>
    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $membre_modifie['pseudo']?>">

    <label for="nom" class="form-label">Nom</label>
    <input type="text" name="nom" id="nom" class="form-control" value="<?= $membre_modifie['nom']?>">
    <label for="prenom" class="form-label">Prenom</label>
    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $membre_modifie['prenom']?>">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="<?= $membre_modifie['email']?>">
    <label for='civilite' class="form-label">Civilite</label>
    <select name="civilite" class="form-select">
        <option value="m" <?=($membre_modifie['civilite'] === 'm') ?  'selected' : ''?> >m</option>
        <option value="f" <?=($membre_modifie['civilite'] === 'f') ?  'selected' : ''?>>f</option>
    </select>
    <label for='statut' class="form-label">Statut</label>
    <select name="statut" id="statut" class="form-select">
            <option value="0">Membre</option>
            <option value="1" <?= ($membre_modifie['statut'] == 1) ?  'selected' : '' ?>>Administrateur</option>
    </select>
    <div class="d-flex justify-content-center">
        <button class="btn btn-dark mt-3">Valider les modifications</button>
    </div>

</form>

     
 
<?php
}


require_once('../inc/footer.php');
