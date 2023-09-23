<?php
REQUIRE("php/db_utilisateur.inc.php");
use Utilisateur\UtilisateurRepository;
use Utilisateur\Utilisateur;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$m = '';
$user = new UtilisateurRepository();

function creerMDP(){
    $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $mdp = '';
    for ($i = 0; $i < 8; $i++) {
        $mdp .= $comb[rand(0, strlen($comb)-1)];
    }
    return $mdp;
}

function envoyerConfirmation($mdp, &$m){
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('admin.nodebt@gmail.com');
        $mail->addAddress(htmlentities($_POST['courriel']));
        $mail->isHTML(false);
        $mail->Subject = 'mot passe oublié';
        $mail->Body = 'voici votre nouveau mot passe : '.$mdp;
        $mail->send();
        $m.= "mail envoyé correctement, votre mot de passe a été modifié";
    } catch (Exception $e) {
        return 'Erreur survenue lors de l\'envoi de l\'email<br>' . $mail->ErrorInfo;
    }
}

if(isset($_POST['send'])) {

    if($util = $user->getUtilisateurByCourriel(htmlentities($_POST['courriel']), $m)){
        $newMDP = creerMDP();
        $update = new Utilisateur();
        $update->uid = $util->uid;
        $update->courriel = htmlentities($_POST['courriel']);
        $update->nom = $util->nom;
        $update->prenom = $util->prenom;
        $update->motPasse = $newMDP;
        $m="";
        $validation = false;
        if ($user->updateUtilisateur($update, $m)){
            $validation = true;
        }
    }else{
        $m .= 'le courriel n\'existe pas';
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
    <form action="motPasseOublie.php" method="POST" enctype ="application/x-www-form-urlencoded">
        <?php
        if(isset($_POST['send']) && $validation){
            envoyerConfirmation($newMDP, $m);
            echo $m;
        }else{
            echo $m;
        }
        ?>
        <label for="courriel">adresse mail</label><input id="courriel" name="courriel" type="email" required placeholder="email">
        <input type="submit" name="send">
    </form>
</main>
<?php include("inc/footer.inc.php"); ?>
</body>
</html>