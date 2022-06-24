<?php

require_once('./inc/init.php');



$title = 'Accueil';
require_once('./inc/header.php');
require_once('./inc/nav.php');

?>


<div class="container-fluid">
    <div class="row">
        <div class="blocChoice col-sm-2 mt-5">
            <h4 class="text-center">Catégorie</h4>
            <table class=" table table-bordered">
                <tbody>
                    <tr>
                        <td>Réunion</td>
                    </tr>
                    <tr>
                        <td>bureau</td>
                    </tr>
                    <tr>
                        <td>Formation</td>
                    </tr>
                </tbody>
            </table>
            <h4 class="mt-2 text-center">ville</h4>
            <table class=" table table-bordered">
                <tbody>
                    <tr>
                        <td>Paris</td>
                    </tr>
                    <tr>
                        <td>Lyon</td>
                    </tr>
                    <tr>
                        <td>Marseille</td>
                    </tr>
                </tbody>
            </table>
            <h4 class="mt-2 text-center">capacite</h4>
            <input type="number" name="capacite" id="capacite" class="form-control">
            <h4 class="mt-2 text-center"> Prix</h4>
            <input type="number" name="prix" id="prix" class="form-control">


        <label for="dateArrivee" class="form-label">date d'arrivée</label>
        <div class="input-group">
        <div class="input-group-prepend">
            <button type="button" id="toggle5" style="width: 50px; height:50px" class="input-group-text"><i class="fa fa-calendar-alt fa-2x"></i></button>
        </div>
        <input type="text" name="dateArrivee" id="picker5" class="form-control">
         </div>


        </div>
        <div class=" blocProduit col-sm-10">col-sm-8

        
        </div>

    </div>

</div>



















<?php
require_once('./inc/footer.php');
?>
