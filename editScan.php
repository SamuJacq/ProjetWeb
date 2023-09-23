<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_participer.inc.php");
use Utilisateur\UtilisateurRepository;
use Utilisateur\Utilisateur;
use Participer\ParticiperRepository;

$user = new UtilisateurRepository();
$participe = new ParticiperRepository();
$m = "";
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Creer facture</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<h1>Editer Scan</h1>
		<main class="formulaireCentre">
			<form action="editScan.php" method="POST" enctype ="application/x-www-form-urlencoded">
				<label for="depense"> Nom de la dépense* </label><input id="depense" type="text" name="depense">
				<label for="groupe"> Nom du groupe </label><input id="groupe" type="text" name="groupe">
				<label for="scan"> fichier scan </label><input id="scan" type="file" name="scan" accept=".pdf">
                <input type="submit" name="send">
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>