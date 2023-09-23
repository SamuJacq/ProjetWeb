<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_participer.inc.php");
require("php/db_versement.inc.php");
use Utilisateur\UtilisateurRepository;
use Participer\ParticiperRepository;
use Versement\VersementRepository;

$user = new UtilisateurRepository();
$participe = new ParticiperRepository();
$versement = new VersementRepository();
$m = "";
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
if(isset($_POST['annuler'])){
    $_POST['delete'] = null;
}
if(isset($_POST['send'])){
    $infoCourriel = $user->getUtilisateurByCourriel(htmlentities($_POST['courriel']), $m);
    $m="";
    if($infoCourriel && $_SESSION['uid'] !== $infoCourriel->uid){
        $m .= "adresse mail déjà existant";
    }elseif(htmlentities($_POST['password'] != htmlentities($_POST['confirmer']))){
        $m .= "les mot de passe ne se correspondent pas";
    }else{
        $util = new Utilisateur();
        $util->uid = $_SESSION['uid'];
        $util->courriel = htmlentities($_POST['courriel']);
        $util->nom = htmlentities($_POST['nom']);
        $util->prenom = htmlentities($_POST['prenom']);
        $util->motPasse = htmlentities($_POST['password']);
        $util->estActif = 0;
        if ($user->updateUtilisateur($util, $m)){
            $m .= "profil correctement modifier";
        }
    }
}
if(isset($_POST['supprimer'])){
    $idGroupe = $participe->getParticiperByUid($_SESSION['uid'], $m);
    if($idGroupe !== null){
        foreach($idGroupe as $id){
            $versementSolde = $versement->getVersementByGid($id, $m);
            foreach($versementSolde as $confirmer)
            if($confirmer->estConfirmer == 0){
                $m = "vous faites partie d'un groupe non solder";
                $_POST['delete'] = null;
            }
        }
        //$m .= "nous ne pouvons supprimer votre profil car participé dans au moins un groupe";

    }else{
        /*if($user->deleteUtilisateur($_SESSION['uid'], $m)){
            $m .= "profil correctement modifier";
        }
        header('Location: index.php');*/
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Edition Profil</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<h1>Edition du profil</h1>
		<main class="formulaireCentre">
			<form action="editProfil.php" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php
                    if(isset($m)) {
                        echo "<p> $m </p>" ;
                    }
                    if(isset($_POST['delete'])){
                        echo '<p> êtes-vous sur de vouloir supprimer votre compte </p>
                              <input type="submit" name="supprimer" value="supprimer">
                              <input type="submit" name="annuler" value="annuler">';
                    }else{
                        echo "<label for=\"nom\"> Nom </label><input id=\"nom\" type=\"text\" name=\"nom\" value=\" $util->nom \">
                        <label for=\"prenom\"> prenom </label><input id=\"prenom\" type=\"text\" name=\"prenom\" value=\"$util->prenom\">
                        <label for=\"email\"> adresse courriel* </label><input id=\"email\" type=\"email\" name=\"courriel\" value=\"$util->courriel\" required placeholder=\"courriel\">
                        <label for=\"password\"> mot de passe* </label><input id=\"password\" type=\"password\" name=\"password\" placeholder=\"mot de passe\">
                        <label for=\"confirmer\"> confirmer mot de passe* </label><input id=\"confirmer\" type=\"password\" name=\"confirmer\" placeholder=\"Confirmer le mot de passe\">
                        <input type=\"submit\" name=\"send\">
                        <input type=\"submit\" name=\"delete\" value=\"supprimer\">";
                    }
                ?>
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>