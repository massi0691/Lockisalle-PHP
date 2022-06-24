<?php





require_once('./inc/init.php');









$titre ='monCompte';
require_once('./inc/header.php');
require_once('./inc/nav.php');

if (!empty($_SESSION['message'])) { ?>

    <div class="alert alert-success d-flex justify-content-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
            <use xlink:href="#check-circle-fill" />
        </svg>
        </svg>
        <div>
            <?= $_SESSION['message']; ?>
        </div>
    </div>
<?php
  unset($_SESSION['message']);
}



require_once('./inc/footer.php');

