<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_groupe.inc.php");
require("php/db_depense.inc.php");
require("php/db_versement.inc.php");
require("php/db_participer.inc.php");
require("php/db_tag.inc.php");

use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Depense\DepenseRepository;
use Versement\VersementRepository;
use Participer\ParticiperRepository;
use Tag\TagRepository;

$user = new UtilisateurRepository();
$groupe = new GroupeRepository();
$depense = new DepenseRepository();
$versement = new VersementRepository();
$participer = new ParticiperRepository();
$tag = new TagRepository();
$m = "";

$id = $_GET['id'];

$util = $user->getUtilisateurById($_SESSION['uid'], $m);
$infoGroupe = $groupe->getGroupeById($id, $m);
$montant = $depense->sumDepenseByIdGroupe($id, $m);
$allVersement = $versement->getVersementByGId($id, $m);
$participant = $participer->getParticiperByGid($id, $m);

$tabDate = null;
$allDepense = null;
if(isset($_POST['send'])){
    $max = intval($_POST['max']);
    $min = intval($_POST['min']);
    if($max < $min){
        $permut = $max;
        $max = $min;
        $min = $permut;
    }elseif($max == $min){
        ++$max;
    }
    $allDepense = $depense->getDepenseByLibelle('%'.htmlentities($_POST['recherche']).'%', $id, $min, $max, $m);
}else{
    $allDepense = $depense->getDepenseById($id, $m);
}
foreach($allDepense as $date){
    //éviter date doublon
    if($tabDate == null){
        $tabDate[] = date('Y-m-d', $date->dateheure);
    }else{
        if($tabDate[count($tabDate)-1] !== date('Y-m-d', $date->dateheure)){
            $tabDate[] = date('Y-m-d', $date->dateheure);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Groupe</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<?php include("inc/header.inc.php"); ?>
<main>
    <section class="pageDepense">
        <h1>Groupe : <?php echo $infoGroupe->nom ?></h1>
        <article class="menu">
            <h2> Menu d'édition </h2>
            <ul class="listeLien">
                <li><a href="invitation.php<?php echo"?id=$id"?>"> inviter des participant </a></li>
                <li><a href="depenseInsert.php<?php echo"?id=$id"?>"> éditer une dépense </a></li>
                <li><a href="editScan.php"> ajouter un scan </a></li>
                <li><a href="groupeEdit.php<?php echo"?id=$id"?>"> éditer le groupe</a></li>
            </ul>
        </article>
        <article>
            <h2> depense </h2>

            <h3>Toutes les depenses</h3>
            <?php
                $nombreParticipant = 0;
                foreach($participant as $nombre){
                    ++$nombreParticipant;
                }
                $moyenne = $montant->montanttotal / $nombreParticipant;

            if($allDepense){
                foreach($tabDate as $date){
                    echo "<h4> Le $date :</h4>";
                    echo "<ul>";
                    foreach ($allDepense as $depense){
                        if(date('Y-m-d', $depense->dateheure) == $date){
                            $infoUser = $user ->getUtilisateurById($depense->uid, $m);
                            echo "<li> $infoUser->prenom a dépensé $depense->montant $infoGroupe->devise dans $depense->libelle </li>";
                        }
                    }
                    echo"</ul>";
                }
            }else{
                echo "aucune dépense trouvé";
            }

                echo "<h3>Montant total par Participant</h3>";
                echo "<ul>";
                foreach($participant as $infoUser){
                    $montantUser = 0;
                    foreach($allDepense as $depense){
                        if($infoUser->uid == $depense->uid){
                            $montantUser += $depense->montant;
                        }
                    }
                    $tabMontantByUser[$infoUser->uid] = $montantUser;
                    $prenomUser = $user->getUtilisateurById($infoUser->uid, $m);
                    echo "<li> $prenomUser->prenom a dépensé au total $montantUser $infoGroupe->devise </li>";
                }
                echo"</ul>";

            ?>

            <p>Total de toutes les dépenses: <?php echo $montant->montanttotal . $infoGroupe->devise ?></p>
            <p>Moyenne par participant: <?php echo $moyenne . $infoGroupe->devise ?></p>
        </article>
        <article>
            <h2> Info participant </h2>
            <h3>Participant </h3>
            <details>
                <summary> liste </summary>
                <ul>
                    <?php
                        foreach($participant as $participe){
                            $personne = $user->getUtilisateurById($participe->uid, $m);
                            if($participe->uid == $infoGroupe->uid){
                                echo " <li> Créateur: $personne->prenom</li>";
                            }elseif($participe->estconfirme == 1){
                                echo "<li> $personne->prenom </li>";
                            }
                        }
                    ?>
                </ul>
            </details>
            <h3>écart de la moyenne</h3>
            <ul>
                <?php

                function matchEcart($tab){
                    foreach($tab as $uid=>$montant){

                    }
                }

                    foreach($tabMontantByUser as $uid=>$montant){
                        $personne = $user->getUtilisateurById($uid, $m);
                        $ecart = $moyenne - $montant;
                        $tabEcartByUser[$uid] = $ecart;
                        echo "<li> $personne->prenom a un écart de la moyenne de $ecart $infoGroupe->devise</li>";
                    }
                    var_dump($tabEcartByUser);
                    foreach($tabEcartByUser as $uid=>$montant){
                        if(matchEcart($tabEcartByUser)){

                        }
                    }


                ?>
            </ul>
            <h3> Versement pour équilibrer les comptes </h3>
            <a href="confirmerVersement.php<?php echo"?id=$id"?>"> confirmer un versement</a>
            <ul>
                <?php
                $nombreVersement = 0;
                    foreach($allVersement as $versements){
                        $donneur = $user->getUtilisateurById($versements->uid, $m);
                        $receveur = $user->getUtilisateurById($versements->uid_1, $m);
                        if($versements->estconfirmer == 0){
                            $confirmation = "non confirmer";
                        }else{
                            $confirmation = "confirmé";
                        }
                        echo "<li>$donneur->prenom verse $versements->montant $infoGroupe->devise à $receveur->prenom ($confirmation)</li>";
                    }
                    if($nombreVersement == 0){
                        echo "<p> aucune versement n'a été créer tout le monde a dépensé le même montant</p>";
                    }
                ?>
            </ul>
            <form action="groupePost.php<?php echo"?id=$id"?>" method="POST" enctype ="application/x-www-form-urlencoded">
                <label for="recherche">recherche dépense</label><input id="recherche" name="recherche" type="text">
                <details>
                    <summary>recherche avancé</summary>
                    <label for="libelle"> libellé </label><input id="libelle" type="text" name="libelle">
                    <label for="min"> Montant min </label><input id="min" type="text" name="min">
                    <label for="max"> Montant max </label><input id="max" type="text" name="max">
                    <label for="tag"> tag </label><input id="tag" type="text" name="tag">
                    <label for="début"> date début  </label><input id="début" type="date" name="début">
                    <label for="fin"> date fin </label><input id="fin" type="date" name="fin">
                </details>
                <input type="submit" name="send">
            </form>
        </article>
    </section>
</main>
<?php include("inc/footer.inc.php"); ?>
</body>
</html>
