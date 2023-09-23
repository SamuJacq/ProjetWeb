<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_depense.inc.php");
require("php/db_participer.inc.php");

use Utilisateur\UtilisateurRepository;
use Depense\DepenseRepository;
use Depense\Depense;
use Participer\ParticiperRepository;

$user = new UtilisateurRepository();
$depense = new DepenseRepository();
$participer = new ParticiperRepository();

$m = "";
$idGroupe = $_GET['id'];
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
$participant = $participer->getParticiperById($idGroupe, $m);

function convertirDate($date){
    $jour = substr($date, 0, 2);
    $mois = substr($date, 3, 2);
    $annee = substr($date, 6, 4);
    return $annee . '/' . $mois . '/' . $jour;
}

if(isset($_POST['send'])){
    $nameUser = $user->getUtilisateurByName(htmlentities($_POST['participant']), $m);
    if(!$nameUser){
        $m .= "le nom du participant n'existe pas";
    }elseif(intval($_POST['montant']) <= 0) {
        $m .= "Le montant ne peut pas être négatif ou égal à 0";
    }elseif(empty(trim(htmlentities($_POST['libelle']), " ")) || empty(trim(htmlentities($_POST['tags']), " "))){
        $m .= "libelle et tags ne peuvant être vide";
    }else{
        $nameOk = true;
        foreach($participant as $participe){
            if($nameUser->uid == $participe->uid && $participe->estconfirme == 1){
                $nameOk= false;
            }
        }
        if($nameOk){
            $m .= "le participant sélectionné ne fait pas partie du groupe";
        }else{
            $insert = new Depense();
            $insert->dateHeure = isset($_POST['date']) ? convertirDate($_POST['date']): time();
            $insert->montant = intval($_POST['montant']);
            $insert->libelle = htmlentities($_POST['libelle']);
            $insert->gid = $idGroupe;
            $insert->uid = $nameUser->uid;
            if($depense->storeDepense($insert, $m)){
                $m .= "dépense correctement inséré";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Edition Depense</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<h1>Editer une dépense : Insertion </h1>
        <h2> Menu d'édition </h2>
        <ul class="listeLien">
            <li><a href="depenseInsert.php<?php echo"?id=$idGroupe"?>"> insérer une dépense </a></li>
            <li><a href="depenseUpdate.php<?php echo"?id=$idGroupe"?>"> modifier une dépense </a></li>
            <li><a href="depenseDelete.php<?php echo"?id=$idGroupe"?>"> supprimer une dépense</a></li>
        </ul>
		<main class="formulaireCentre">
            <form action="depenseInsert.php<?php echo"?id=$idGroupe"?>" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php if(isset($m)) {echo $m;} ?>
                <label for="participant"> Nom du participant* </label><input id="participant" type="text" name="participant" required placeholder="nom">
                <label for="montant"> Montant* </label><input id="montant" type="text" name="montant" required placeholder="montant">
                <label for="date"> date* </label><input id="date" type="date" name="date" required value="<?php echo date('dd/mm/yyyy', time()) ?>">
                <label for="libelle"> libellé* </label><input id="libelle" type="text" name="libelle" required placeholder="libellé">
                <label for="tags"> tags* </label><input id="tags" type="text" name="tags" required placeholder="tags">
                <input type="submit" name="send">
            </form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>