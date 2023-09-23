<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_versement.inc.php");
require("php/db_groupe.inc.php");
require("php/db_participer.inc.php");

use Utilisateur\UtilisateurRepository;
use Versement\VersementRepository;
use Versement\Versement;
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;

$user = new UtilisateurRepository();
$versement = new VersementRepository();
$groupe = new GroupeRepository();
$participer = new ParticiperRepository();

$m = "";
$id = $_GET['id'];

$util = $user->getUtilisateurById($_SESSION['uid'], $m);
$infoGroupe = $groupe->getGroupeById($id, $m);
$allVersement = $versement->getVersementById($id, $m);
$participant = $participer->getParticiperById($id, $m);

if(isset($_POST['send'])){
    $donneur = $user->getUtilisateurByName(htmlentities($_POST['donneur']), $m);
    $receveur = $user->getUtilisateurByName(htmlentities($_POST['receveur']), $m);
    $donneurOk = true;
    $receveurOk = true;
    if(!$donneur || !$receveur){
        $m .= "le donneur ou le receveur n'existe pas";
    }else{
        foreach($participant as $participe){
            if($donneur->uid == $participe->uid && $participe->estconfirme == 1){
                $donneurOk = false;
            }
            if($receveur->uid == $participe->uid && $participe->estconfirme == 1){
                $receveurOk = false;
            }
        }
        if($donneurOk || $receveurOk){
            $m .= "l'une des personnes sélectionné ne fait pas partie du groupe";
        }else{
            $update = new Versement();
            $update->uid = $donneur->uid;
            $update->uid_1 = $receveur->uid;
            $update->gid = $id;
            if($versement->updateVersement($update, $m)){
                $m .= "versement confirmé";
            }
        }
    }
    var_dump($donneur->uid);
    var_dump($receveur->uid);
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <title>Confirmer versement</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <?php include("inc/header.inc.php"); ?>
        <h1>Confirmer versement</h1>
        <main class="formulaireCentre">
            <form action="confirmerVersement.php<?php echo"?id=$id"?>" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php
                    if(isset($m)) {
                        echo "<p> $m </p>" ;
                    }
                ?>
                <label for="donneur"> Nom du donneur* </label><input id="donneur" type="text" name="donneur" required placeholder="donneur">
                <label for="receveur"> Nom du receveur* </label><input id="receveur" type="text" name="receveur" required placeholder="receveur">
                <input type="submit" name="send">
            </form>
            <ul>
                <?php
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
                ?>
            </ul>
        </main>
        <?php include("inc/footer.inc.php"); ?>
    </body>
</html>