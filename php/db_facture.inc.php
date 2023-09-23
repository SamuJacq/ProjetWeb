<?php
namespace Facture;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Facture{
    public $fid;
    public $scan;
    public $did;

}

class FactureRepository {
    const TABLE_NAME = 'Facture';

    public function getAllFacture(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Facture\Facture");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getFatcureById($fid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT* FROM " .self::TABLE_NAME. " WHERE fid = :fid");
            $stmt->bindValue(":fid", $fid);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Facture\Facture");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeFacture($facture, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (scan, did) VALUES (:scan, :did)");
            $stmt->bindValue(':scan', $facture->scan);
            $stmt->bindValue(':did', $facture->did);
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

    public function updateFacture($facture, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                "SET scan=:scan, did=:did WHERE fid = :fid");
            $stmt->bindValue(':scan', $facture->scan);
            $stmt->bindValue(':did', $facture->did);
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

    public function deleteUtilisateur($uid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE fid = :fid");
            $stmt->bindValue(":fid", $fid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Facture\Facture");
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