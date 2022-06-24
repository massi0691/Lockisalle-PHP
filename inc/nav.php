<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $base_url . 'index.php' ?>" style="font-size: 3rem; background-color:darkolivegreen;">Lokisalle</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <?php

                if (isset($_SESSION['user'])) {

                    echo  '<a href="' . $base_url . 'logout.php" class="nav-link" >Deconnexion</a>';
                    if ($_SESSION['user']['statut'] == 1) {
                        echo  '<a href="' . $base_url . 'admin/index.php" class="nav-link" >Admin</a>';
                    }
                } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Qui sommes nous ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Contact</a>
                    </li>

                    <li class="nav-item dropdown  mx-5 px-5">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Espace membre
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li> <a href="<?= $base_url . 'register.php' ?>" class="dropdown-item">Inscription</a></li>
                            <li> <a href="<?= $base_url . 'login.php' ?>" class="dropdown-item">Connexion</a></li>

                        </ul>
                    </li>




                <?php
                }
                ?>


            </ul>

        </div>
    </div>
</nav>