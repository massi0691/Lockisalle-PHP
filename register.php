<?php


require_once('./inc/init.php');

// session_destroy();

//  debug($newDate);

// Traitement PHP

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
        $requette = $bdd->prepare('INSERT INTO membre (pseudo, mdp, nom, prenom,email,civilite,statut,date_enregistrement) VALUES ( :pseudo, :mdp, :nom, :prenom,:email,:civilite, :statut, :date_enregistrement)');
        $result = $requette->execute([
            'pseudo' => $_POST['pseudo'],
            'mdp' => md5($_POST['mdp']),
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'civilite' => $_POST['civilite'],
            'statut' => 0,
            'date_enregistrement' => $newDate


        ]);

        if ($result === true) {
            $successMessages .= " vous venez d'inscrire";
        } else {
            $errorMessages .= "Erreur lors de l'inscription";
        }
    }
}





$title = 'Inscription du membre';
require_once('./inc/header.php');
require_once('./inc/nav.php');

?>



<!-- Affichage PHP-->
<h1 class="text-center"> Inscription du membre</h1>
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
        <button class="btn btn-dark mt-3">S'inscrire</button>
    </div>

</form>









<?php

require_once('./inc/footer.php');
