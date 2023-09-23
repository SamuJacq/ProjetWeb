<?php
namespace Utilisateur;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Utilisateur{
    public $uid;
    public $courriel;
    public $nom;
    public $prenom;
    public $motPasse;
    public $estActif;

}

class UtilisateurRepository {
    const TABLE_NAME = 'Utilisateur';

    public function getAllUtilisateur(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Utilisateur\Utilisateur");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getUtilisateurById($uid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE uid = :uid");
            $stmt->bindValue(":uid", $uid);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Utilisateur\Utilisateur");
            }else{
                $message .= "erreur survenu";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getUtilisateurByCourriel($courriel, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE courriel = :courriel");
            $stmt->bindValue(":courriel", $courriel);
            if($stmt-> execute()){
                $result = $stmt->fetchObject("Utilisateur\Utilisateur");
            }else{
                $message .= "erreur survenu";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getUtilisateurByName($prenom, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE prenom = :prenom");
            $stmt->bindValue(":prenom", $prenom);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Utilisateur\Utilisateur");
            }else{
                $message .= "erreur survenu";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeUtilisateur($utilisateur, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (courriel, nom, prenom, motPasse, estActif) VALUES (:courriel, :nom, :prenom, :motPasse, :estActif)");
            $stmt->bindValue(':courriel', $utilisateur->courriel);
            $stmt->bindValue(':nom', $utilisateur->nom);
            $stmt->bindValue(':prenom', $utilisateur->prenom);
            $stmt->bindValue(':motPasse', $utilisateur->motPasse);
            $stmt->bindValue(':estActif', $utilisateur->estActif);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $noError = true;
            }else{
                $message .= "erreur survenue";
            }
            $stmt = null;
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function updateUtilisateur($utilisateur, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                " SET courriel=:courriel, nom=:nom, prenom=:prenom, motPasse=:motpasse WHERE uid = :uid");
            $stmt->bindValue(':uid', $utilisateur->uid);
            $stmt->bindValue(':courriel', $utilisateur->courriel);
            $stmt->bindValue(':nom', $utilisateur->nom);
            $stmt->bindValue(':prenom', $utilisateur->prenom);
            $stmt->bindValue(':motpasse', $utilisateur->motPasse);
            if($stmt->execute()){
                $noError = true;
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $noError;
    }

    public function deleteUtilisateur($uid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE uid = :uid");
            $stmt->bindValue(":uid", $uid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Member\Member");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

}