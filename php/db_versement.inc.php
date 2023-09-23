<?php
namespace Versement;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Versement{
    public $uid;
    public $uid_1;
    public $gid;
    public $dateHeure;
    public $montant;
    public $estConfirmer;

}

class VersementRepository {
    const TABLE_NAME = 'Versement';

    public function getAllVersement(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Versement\Versement");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getVersementByGid($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE gid = :gid");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Versement\Versement");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeVersement($versement, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (uid, uid_1, gid, dateHeure, montant, estConfirmer) VALUES (:uid, :uid_1, :gid, :dateHeure, :montant, :estConfirmer)");
            $stmt->bindValue(':uid', $versement->uid);
            $stmt->bindValue(':uid_1', $versement->uid_1);
            $stmt->bindValue(':gid', $versement->gid);
            $stmt->bindValue(':dateHeure', $versement->dateHeure);
            $stmt->bindValue(':montant', $versement->montant);
            $stmt->bindValue(':estConfirmer', $versement->estConfirmer);

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

    public function updateVersement($versement, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                " SET estConfirmer =:estConfirmer, dateHeure = :dateHeure WHERE uid=:uid and uid_1=:uid_1 and gid=:gid");
            $stmt->bindValue(':estConfirmer', 1);
            $stmt->bindValue(':dateHeure', time());
            $stmt->bindValue(':uid', $versement->uid);
            $stmt->bindValue(':uid_1', $versement->uid_1);
            $stmt->bindValue(':gid', $versement->gid);

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

    public function deleteVersement($versement, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE uid=:uid and uid_1=:uid_1 and gid=:gid");
            $stmt->bindValue(':uid', $versement->uid);
            $stmt->bindValue(':uid_1', $versement->uid_1);
            $stmt->bindValue(':gid', $versement->gid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Versement\Versement");
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