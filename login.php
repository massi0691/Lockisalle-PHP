<?php


require_once('./inc/init.php');


// Traitement PHP

if (!empty($_POST)) {

    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value, ENT_QUOTES);
    }

    if (empty($_POST['pseudo']) || empty($_POST['mdp'])) {
        $errorMessages .= "les identifants sont obligatoire";
    }

    if (empty($errorMessages)) {

        $requette = $bdd->prepare('SELECT * FROM membre WHERE pseudo = :pseudo AND mdp =:mdp');
        $requette->execute([
            'pseudo' => $_POST['pseudo'],
            'mdp' => md5($_POST['mdp'])
        ]);
      
         $membre = $requette->fetch(PDO::FETCH_ASSOC);

        if (!empty($membre) && md5($_POST['mdp']) == $membre['mdp'] ) {
                
                /* if  The password is correct. */
                $_SESSION['user'] = $membre;
                $_SESSION['message'] = "Bienvenue, $membre[pseudo]";
                header('location:compte.php');
                exit;
    
        } else {
            $errorMessages .= "Attention les identifiants sont incorrects ! <br>";
        }
    }
}


$title = 'Connexion';
require_once('./inc/header.php');
require_once('./inc/nav.php');


?>



<!-- Affichage PHP-->
<h1 class="text-center"> Connexion du membre</h1>
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

    ?>



    <label for="pseudo" class="form-label">Pseudo</label>
    <input type="text" name="pseudo" id="pseudo" class="form-control" value="">

    <label for="mdp" class="form-label">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" class="form-control" value="">


    <div class="d-flex justify-content-center">
        <button class="btn btn-dark mt-3">Connexion</button>
    </div>

</form>









<?php

require_once('./inc/footer.php');
