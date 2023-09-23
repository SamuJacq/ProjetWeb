<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_groupe.inc.php");
require("php/db_utilisateur.inc.php");
use Groupe\GroupeRepository;
use Groupe\Groupe;
use Utilisateur\UtilisateurRepository;

$groupe = new GroupeRepository();
$user = new UtilisateurRepository;
$m = "";

$util = $user->getUtilisateurById($_SESSION['uid'], $m);

if(isset($_POST['send'])){
    $insert = new Groupe();
    $insert->nom = htmlentities($_POST['nom']);
    $insert->devise = htmlspecialchars($_POST['devise']);
    $insert->uid = 1;
    $m="";
    if(empty(trim(htmlentities($_POST['nom']), " ")) || (htmlspecialchars($_POST['devise']) !== '€' &&
            htmlspecialchars($_POST['devise']) !== '$' && htmlspecialchars($_POST['devise']) !== '£')){
        $m .= "un des champs est incorrect";
    }else{
        if ($groupe->storeGroupe($insert, $m)){
            $m .= "groupe correctement ajouté";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Edition Groupe</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>	
		<h1>Edition Groupe</h1>
		<main class="formulaireCentre">
			<form action="groupeInsert.php" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php echo $m; ?>
				<label for="nom"> Nom du groupe* </label><input id="nom" type="text" name="nom" required placeholder="nom">
                <select name="devise">
                    <option value="€"<?php if (isset($devise)&&$devise == "€") {echo "selected";}?>>€</option>
                    <option value="$"<?php if (isset($devise)&&$devise == "$") {echo "selected";}?>>$</option>
                    <option value="£"<?php if (isset($devise)&&$devise == "£") {echo "selected";}?>>£</option>
                </select>
                <input type="submit" name="send">
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>