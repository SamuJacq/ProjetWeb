<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_groupe.inc.php");
require("php/db_participer.inc.php");

use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Participer\ParticiperRepository;
use Participer\Participer;

$id = $_GET['id'];
$user = new UtilisateurRepository();
$groupe = new GroupeRepository();
$participe = new ParticiperRepository();
$m = "";

$infoGroupe = $groupe->getGroupeById($id, $m);
$util = $user->getUtilisateurById($_SESSION['uid'], $m);
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function envoyerConfirmation($courriel, $infoGroupe, &$m){

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($courriel);
        $mail->addAddress(htmlentities($_POST['courriel']));
        $mail->isHTML(false);
        $mail->Subject = 'invitation groupe';
        $mail->Body = "vous avez invité à participer dans le groupe de " . $infoGroupe->nom;
        $mail->send();
        $m = "invitation envoyé";
    } catch (Exception $e) {
        return 'Erreur survenue lors de l\'envoi de l\'email<br>' . $mail->ErrorInfo;
    }
}

if(isset($_POST['send'])){
    $userInvite = $user->getUtilisateurByCourriel(htmlentities($_POST['courriel']), $m);
    $participeDeja = $participe->getParticiperByUid($userInvite->uid, $m);
    $validation = false;
    foreach($participeDeja as $verif){
        if($verif->gid == $id){
            $validation = true;
        }
    }
    if(!isset($userInvite)){
        $m .= "l'adresse mail n'existe pas ";
    }elseif(empty(trim(htmlentities($_POST['courriel']), " "))){
        $m .= "l'adresse courriel est vide";
    }elseif($validation){
        $m .= "cette utilisateur est déjà dans le groupe";
    }else{
        $insert = new Participer();
        $insert->uid = $userInvite->uid;
        $insert->gid = $id;
        $insert->estConfirme = 0;
        if($participe->storeParticiper($insert, $m)){
            envoyerConfirmation($util->courriel, $infoGroupe, $m);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Invitation</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>	
		<h1>Invitation</h1>
		<main class="formulaireCentre">
			<form action="invitation.php<?php echo"?id=$id"?>" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php if(isset($m)) {echo $m;} ?>
				<label for="courriel"> adresse courriel de la personne*</label><input id="courriel" type="email" name="courriel" required placeholder="courriel">
                <input type="submit" name="send">
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>