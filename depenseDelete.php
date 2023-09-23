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
use Participer\ParticiperRepository;

$user = new UtilisateurRepository();
$depense = new DepenseRepository();
$participer = new ParticiperRepository();

$m = "";
$id = $_GET['id'];
$participant = $participer->getParticiperById($id, $m);
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
if(isset($_POST['annuler'])){
    $_POST['delete'] = null;
    $allDepense = $depense->getDepenseById($id, $m);
}elseif(isset($_POST['confirmer'])){
    if($depense->deleteDepense($id, $m)){
        $m .= "groupe correctement modifier";
    }
    header("Location: groupePost.php?id=$id");
}else{
    $allDepense = $depense->getDepenseById($id, $m);
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
    <li><a href="depenseInsert.php<?php echo"?id=$id"?>"> insérer une dépense </a></li>
    <li><a href="depenseUpdate.php<?php echo"?id=$id"?>"> modifier une dépense </a></li>
    <li><a href="depenseDelete.php<?php echo"?id=$id"?>"> supprimer une dépense</a></li>
</ul>
<main class="formulaireCentre">
    <form action="depenseDelete.php<?php echo"?id=$id"?>" method="POST" enctype ="application/x-www-form-urlencoded">
        <?php
        if(isset($_POST['delete'])){
            echo '<p> êtes-vous sur de vouloir supprimer cette dépense </p>
                      <input type="submit" name="confirmer" value="confirmer">
                      <input type="submit" name="annuler" value="annuler">';
        }else {
            foreach($allDepense as $infoDepense){
                echo "<p>persone à dépenser $infoDepense->montant € le ". date('Y-m-d', $infoDepense->dateheure)."</p>
                     <input type=\"submit\" name=\"delete\" value=\"supprimer\">";
            }
        }
        ?>
    </form>
</main>
<?php include("inc/footer.inc.php"); ?>
</body>
</html>