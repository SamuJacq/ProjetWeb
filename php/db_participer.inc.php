<?php
namespace Participer;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Participer{
    public $uid;
    public $gid;
    public $estConfirme;

}

class ParticiperRepository {
    const TABLE_NAME = 'Participer';

    public function getParticiperByGid($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE gid = :gid");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Participer\Participer");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getParticiperByUid($uid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE uid = :uid");
            $stmt->bindValue(":uid", $uid);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Participer\Participer");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeParticiper($participer, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (uid, gid, estConfirme) VALUES (:uid, :gid, :estConfirme)");
            $stmt->bindValue(':uid', $participer->uid);
            $stmt->bindValue(':gid', $participer->gid);
            $stmt->bindValue(':estConfirme', $participer->estConfirme);
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

    public function updateParticiper($participer, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                "SET estConfirme = :estConfirme WHERE uid = :uid");
            $stmt->bindValue(':uid', $participer->uid);
            $stmt->bindValue(':estConfirme', $participer->estConfirme);
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

    public function deleteParticiper($participer, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE did = :did and gid = :gid");
            $stmt->bindValue(':uid', $participer->uid);
            $stmt->bindValue(':gid', $participer->gid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Participer\Participer");
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