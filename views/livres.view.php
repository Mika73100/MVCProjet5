<?php 

ob_start(); 

?>


<table class="table text-center">

    <tr class="table-dark">
        <th>Image</th>
        <th>Titre</th>
        <th>Nombre de pages</th>
        <th colspan="2">Actions</th>
    </tr>

    <?php
    // ici je crée une boucle qui parcourt le tableau et qui me permet d'afficher,
    // ma liste de livre a travers ma variable livres.
    //ma variable livres sera donc utiliser dans mon controleur par 
    //la fonction afficher ce qui permet d'afficher l'ensemble des livres.
    for($i=0; $i < count($livres);$i++) : 
    ?>

    <tr>
        <td class="align-middle"><img src="public/images/<?= $livres[$i]->getImage(); ?>" width="60px;"></td>
        <td class="align-middle"><a href="<?= URL ?>livres/l/<?= $livres[$i]->getId(); ?>"><?= $livres[$i]->getTitre(); ?></a></td>
        <td class="align-middle"><?= $livres[$i]->getNbPages(); ?></td>
        <td class="align-middle"><a href="<?= URL ?>livres/m/<?= $livres[$i]->getId(); ?>" class="btn btn-warning">Modifier</a></td>
        <td class="align-middle">
            <form method="POST" action="<?= URL ?>livres/s/<?= $livres[$i]->getId(); ?>" onSubmit="return confirm('Voulez-vous vraiment supprimer le livre ?');">

                <button class="btn btn-danger" type="submit">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endfor; ?>
    <!--ici je reprend la lettre de mon index dans ajouter pour crée le chemin vers ajouter !-->
</table>

<a href="<?= URL ?>livres/a" class="btn btn-success d-block">Ajouter</a>



<?php

$content = ob_get_clean();
$titre = "Les livres de la bibliothèque";
require "template.php";

?>