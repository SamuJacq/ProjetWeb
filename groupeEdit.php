<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_groupe.inc.php");
require("php/db_versement.inc.php");
require("php/db_utilisateur.inc.php");
use Groupe\GroupeRepository;
use Groupe\Groupe;
use Versement\VersementRepository;
use Utilisateur\UtilisateurRepository;

$groupe = new GroupeRepository();
$versement= new VersementRepository();
$user = new UtilisateurRepository;
$m = "";
$id = $_GET['id'];
$infoGroupe = $groupe->getGroupeById($id, $m);
$allVersement = $versement->getVersementById($id, $m);
$devise = "€";
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
if(isset($_POST['annuler'])){
    $_POST['delete'] = null;
}
if(isset($_POST['send'])){
    $update = new Groupe();
    $update->nom = htmlentities($_POST['nom']);
    $update->devise = htmlspecialchars($_POST['devise']);
    $update->gid = $id;
    $m="";
    if(empty(trim(htmlentities($_POST['nom']), " ")) || (htmlspecialchars($_POST['devise']) !== '€' &&
            htmlspecialchars($_POST['devise']) !== '$' && htmlspecialchars($_POST['devise']) !== '£')){
        $m .= "un des champs est incorrect";
    }else{
        if ($groupe->updateGroupe($update, $m)){
            $m .= "groupe correctement modifier";
        }else{
            $m .= "erreur veuillez réessayer";
        }
    }
}
if(isset($_POST['confirmer'])){
    $allConfirme = false;
    foreach($allVersement as $confirme){
        if($confirme->estconfirmer == 0){
            $allConfirme = true;
        }
    }
    if($allConfirme){
        $m .= "nous ne pouvons supprimer le groupe car des versement n'ont pas été confirmé";
        $_POST['delete'] = null;
    }else{
        if($groupe->deleteGroupe($id, $m)){
            $m .= "groupe correctement modifier";
        }
        header('Location: groupe.php');
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
    <form action="groupeEdit.php<?php echo"?id=$id"?>" method="POST" enctype ="application/x-www-form-urlencoded">
        <?php echo $m; ?>
        <?php
            if(isset($_POST['delete'])){
                echo '<p> êtes-vous sur de vouloir supprimer le groupe </p>
                      <input type="submit" name="confirmer" value="confirmer">
                      <input type="submit" name="annuler" value="annuler">';
            }else{
                echo "<label for=\"nom\"> Nom du groupe* </label><input id=\"nom\" type=\"text\" name=\"nom\" value=\" $infoGroupe->nom \" required placeholder=\"nom\">
                      <select name=\"devise\">
                          <option value=\"€\" ". ((isset($devise)&&$devise == "€") ? "selected" : "") .">€</option>
                          <option value=\"$\" ". ((isset($devise)&&$devise == "$") ? "selected" : "") .">$</option>
                          <option value=\"£\" ". ((isset($devise)&&$devise == "£") ? "selected" : "") .">£</option>
                      </select>
                      <input type=\"submit\" name=\"send\">
                      <input type=\"submit\" name=\"delete\" value=\"supprimer\">";
            }
        ?>

    </form>
</main>
<?php include("inc/footer.inc.php"); ?>
</body>
</html>