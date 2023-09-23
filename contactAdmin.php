<?php
session_start();
REQUIRE("php/db_utilisateur.inc.php");
use Utilisateur\UtilisateurRepository;
$user = new UtilisateurRepository();

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function envoyerConfirmation($email, &$m){
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom(htmlentities($_POST['courriel']));
        $mail->addAddress(htmlentities($email));
        $mail->isHTML(false);
        $mail->Subject = htmlentities($_POST['sujet']);
        $mail->Body = htmlentities($_POST['message']);
        $mail->send();
        $m.= "mail envoyé correctement, un admin va vous répondre dans les plus bref délais";
    } catch (Exception $e) {
        return 'Erreur survenue lors de l\'envoi de l\'email<br>' . $mail->ErrorInfo;
    }
}
$m = "";
$emailAdmin = 'sam.jacquemin01@gmail.com';
if(isset($_SESSION['uid'])){
    $util = $user->getUtilisateurById($_SESSION['uid'], $m);
    $emailUser = $util->courriel;
    $prenomUser = $util->prenom;
}


if(isset($_POST['send'])) {
    $validation = true;
    if(empty(trim(htmlentities($_POST['courriel']), " ")) || empty(trim(htmlentities($_POST['sujet']), " ")) ||
        empty(trim(htmlentities($_POST['message']), " ")) || empty(trim(htmlentities($_POST['nom']), " ")) ||
        !isset($_POST['securiter'])){
        $validation = false;
        $m = "vous n'avez pas rempli tout le formulaire";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>contacter un Admin</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<h1>Contacter un Admin</h1>
		<main class="formulaireCentre">
			<form action="contactAdmin.php" method="POST" enctype ="application/x-www-form-urlencoded">
                <?php
                    if(isset($_POST['send']) && $validation){
                        envoyerConfirmation(htmlentities($_POST['courriel']), $m);
                        envoyerConfirmation(htmlentities($emailAdmin), $m);
                        echo $m;
                    }else{
                        echo $m;
                    }
                ?>
				<label for="nom"> Nom </label><input id="nom" type="text" name="nom" value="<?php if(isset($prenomUser)){echo $prenomUser;}?>">
				<label for="sujet"> Sujet </label><input id="sujet" type="text" name="sujet">
				<label for="courriel"> adresse courriel* </label><input id="courriel" type="email" name="courriel" value="<?php if(isset($emailUser)){echo $emailUser;}?>" required placeholder="courriel">
                <label for="message"> message* </label><textarea id="message" name="message" cols="73" rows="15" required placeholder="message"></textarea>
                <input id="securiter" type="text" name="securiter" class="cacher">
                <input type="submit" name="send">
			</form>
		</main>
		<?php include("inc/footer.inc.php"); ?>
	</body>
</html>