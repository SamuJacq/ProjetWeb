<?php
namespace Groupe;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Groupe{
    public $gid;
    public $nom;
    public $devise;
    public $uid;

}

class GroupeRepository {
    const TABLE_NAME = 'Groupe';

    public function getAllGroupe(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Groupe\Groupe");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getGroupeById($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE gid = :gid");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Groupe\Groupe");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeGroupe($groupe, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (nom, devise, uid) VALUES (:nom, :devise, :uid)");
            $stmt->bindValue(':nom', $groupe->nom);
            $stmt->bindValue(':devise', $groupe->devise);
            $stmt->bindValue(':uid', $groupe->uid);
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

    public function updateGroupe($groupe, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                " SET nom=:nom, devise=:devise WHERE gid = :gid");
            $stmt->bindValue(':nom', $groupe->nom);
            $stmt->bindValue(':devise', $groupe->devise);
            $stmt->bindValue(':gid', $groupe->gid);
            if($stmt-> execute()){
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

    public function deleteGroupe($gid, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE gid = :gid");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
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

}