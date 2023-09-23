<?php
session_start();
if(!isset($_SESSION['uid'])){
    header('Location: connexion.php');
}
require("php/db_utilisateur.inc.php");
require("php/db_groupe.inc.php");
require("php/db_depense.inc.php");
require("php/db_participer.inc.php");

use Utilisateur\UtilisateurRepository;
use Groupe\GroupeRepository;
use Depense\DepenseRepository;
use Participer\ParticiperRepository;

$user = new UtilisateurRepository();
$groupe = new GroupeRepository();
$depense = new DepenseRepository();
$participer = new ParticiperRepository();
$m = "";

$util = $user->getUtilisateurById($_SESSION['uid'], $m);
$participation = $participer->getParticiperByUid($_SESSION['uid'], $m);
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Groupe</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include("inc/header.inc.php"); ?>
		<main>
			<h1>Vos groupes</h1>
			<h2> liste de groupe </h2>
			<a href ="groupeInsert.php"> creer un groupe </a>
			<section>
                <?php
                    $nombreGroupe =0;
                            foreach ($participation as $gid){
                                $info = $groupe->getGroupeById($gid->gid, $m);
                                $montant = $depense->sumDepenseByIdGroupe($info->gid, $m);
                                $lastDepense = $depense->getThreeLastDepense($info->gid, $m);
                                echo "<article class=\"groupeList\">
                                    <h2> <a href=\"groupePost.php?id=$info->gid\"> $info->nom  </a></h2>
                                    <!-- c'est un commentaire -->
                                        <ul>
                                            <li> Créateur : $util->prenom </li>
                                            <li> montant total: $montant->montanttotal $info->devise</li>
                                        </ul>
                                        <h3> les derniers dépenses:</h3>
                                        <ol>";
                                foreach ($lastDepense as $depenses) {
                                    $nom = $user->getUtilisateurById($depenses->uid, $m);
                                    echo "<li> $nom->prenom à dépenser $depenses->montant $info->devise le " . date('Y-m-d', $depenses->dateheure) . "</li>";
                                }

                                echo "</ol>
                                    </article>";
                                $nombreGroupe++;

                            }
                        if($nombreGroupe ==0){
                            echo "vous ne fait partie d'aucun groupe";
                        }
                    ?>
                    <article class="groupeList">
                        <h2> vos invitations </h2>
                        <details>
                            <summary> liste des invitations</summary>
                            <ul>
                            <?php
                                $nombreInvitation = 0;
                                $invitation = $participer->getParticiperByGid($_SESSION['uid'], $m);
                                foreach($invitation as $confirmer){
                                    if($confirmer->estconfirme == 0){
                                        $nomGroupe = $groupe->getGroupeById($confirmer->gid, $m);
                                        echo "<li> $nomGroupe->nom </li>";
                                        ++$nombreInvitation;
                                    }
                                }
                                if($nombreInvitation == 0){
                                    echo "<li> vous n'avez aucune invitation </li>";
                                }
                            ?>
                            </ul>
                        </details>
                    </article>
                </section>
            </main>
            <?php include("inc/footer.inc.php"); ?>
        </body>
    </html>