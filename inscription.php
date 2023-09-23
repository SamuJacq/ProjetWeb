<?php
require("php/db_utilisateur.inc.php");
use Utilisateur\UtilisateurRepository ;
use Utilisateur\Utilisateur;

$user = new UtilisateurRepository();

if(isset($_POST['send'])){
    $util = new Utilisateur();
    $util->courriel = htmlentities($_POST['courriel']);
    $util->nom = htmlentities($_POST['nom']);
    $util->prenom = htmlentities($_POST['prenom']);
    $util->motPasse = htmlentities($_POST['mdp']);
    $util->estActif = 0;
    $m="";
    if($user->getUtilisateurByCourriel(htmlentities($_POST['courriel']), $m)){
        $m .= "adresse mail déjà existant";
    }elseif(htmlentities($_POST['mdp'] != htmlentities($_POST['confirmer']))){
        $m .= "les mot de passe ne se correspondent pas";
    }else{
        if ($user->storeUtilisateur($util, $m)){
            $m .= "Compte correctement ajouté";
        }else{
            $m .= "erreur veuillez réessayer";
        }
    }

}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>inscription</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>	
		<h1>Inscription</h1>
		<main class="formulaireCentre">
			<form action="inscription.php" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php if(isset($m)){ echo "<p>$m</p>"; } ?>
				<label for="nom"> Nom* </label><input id="nom" type="text" name="nom" value="<?php if(isset($_POST['nom'])) { echo htmlentities($_POST['nom']); } ?>" required placeholder="nom">
				<label for="prenom"> prenom* </label><input id="prenom" type="text" name="prenom" value="<?php if(isset($_POST['prenom'])) { echo htmlentities($_POST['prenom']); } ?>" required placeholder="prenom">
				<label for="email"> adresse courriel* </label><input id="email" type="email" name="courriel" value="<?php if(isset($_POST['courriel'])) { echo htmlentities($_POST['courriel']); } ?>" required placeholder="courriel">
				<label for="password"> mot de passe* </label><input id="password" type="password" name="mdp" value="<?php if(isset($_POST['mdp'])) { echo htmlentities($_POST['mdp']); } ?>" required placeholder="mot de passe">
				<label for="confirmer"> confirmer mot de passe* </label><input id="confirmer" type="password" name="confirmer" value="<?php if(isset($_POST['confirmer'])) { echo htmlentities($_POST['confirmer']); } ?>" required placeholder=" Confirmer le mot de passe">
                <input type="submit" name="send">
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>