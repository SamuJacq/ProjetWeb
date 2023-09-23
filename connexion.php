<?php
session_start();
require('php/db_utilisateur.inc.php');
use Utilisateur\UtilisateurRepository;

$user = new UtilisateurRepository();

if(isset($_POST['send'])) {
    $m = "";
    if($util = $user->getUtilisateurByCourriel(htmlentities($_POST['courriel']), $m)){
        if ($util && $util->motpasse == htmlentities($_POST['mdp'])) {
            $_SESSION['uid'] = $util->uid;
            header('Location: groupe.php');
        }
    }else{
        $m .= "erreur dans l'email ou dans le mot de passe";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>connexion</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>	
		<h1> Connexion</h1>
		<main class="formulaireCentre">
			<form action="connexion.php" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php
                    if(isset($m)) {
                        echo "<p> $m </p>" ;
                    }
                ?>
				<label for="courriel">adresse mail</label><input id="courriel" name="courriel" type="email" value="<?php if(isset($_POST['courriel'])) { echo htmlentities($_POST['courriel']); } ?>"  required placeholder="email">
				<label for="mdp">mot de passe</label><input id="mdp" name="mdp" type="password"  required placeholder="mot de passe">
				<input type="submit" name="send">
				<a href="motPasseOublie.php"> mot de passe oublié? </a>
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>