<?php
namespace Depense;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Depense{
    public $did;
    public $dateHeure;
    public $montant;
    public $libelle;
    public $gid;
    public $uid;

}

class DepenseRepository {
    const TABLE_NAME = 'Depense';

    public function getAllDepense(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Depense\Depense");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getDepenseById($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE gid = :gid order by dateHeure");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Depense\Depense");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getDepenseByLibelle($libelle, $gid, $min, $max, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE libelle like :libelle and gid = :gid and
                                    montant between :min and :max order by dateHeure");
            $stmt->bindValue(":libelle", $libelle);
            $stmt->bindValue(":gid", $gid);
            $stmt->bindValue(":min", $min);
            $stmt->bindValue(":max", $max);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Depense\Depense");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function sumDepenseByIdGroupe($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT sum(montant) as montantTotal FROM ".self::TABLE_NAME." where gid = :gid");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Depense\Depense");
            }else{
                $message .= "erreur survenue";
            }
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getThreeLastDepense($gid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM ".self::TABLE_NAME." where gid = :gid order by dateHeure desc limit 3 ");
            $stmt->bindValue(":gid", $gid);
            if($stmt-> execute()){
                $result = $stmt-> fetchAll(PDO::FETCH_CLASS,"Depense\Depense");
            }else{
                $message .= "erreur survenue";
            }
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeDepense($depense, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (dateHeure, montant, libelle, gid, uid) VALUES (:dateHeure, :montant, :libelle, :gid, :uid)");
            $stmt->bindValue(':dateHeure', $depense->dateHeure);
            $stmt->bindValue(':montant', $depense->montant);
            $stmt->bindValue(':libelle', $depense->libelle);
            $stmt->bindValue(':gid', $depense->gid);
            $stmt->bindValue(':uid', $depense->uid);
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

    public function updateDepense($depense, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                "SET dateHeure=:dateHeure, montant=:montant, libelle=:libelle, gid=:gid, uid=:uid WHERE did = :did");
            $stmt->bindValue(':dateHeure', $depense->dateHeure);
            $stmt->bindValue(':montant', $depense->montant);
            $stmt->bindValue(':libelle', $depense->libelle);
            $stmt->bindValue(':gid', $depense->gid);
            $stmt->bindValue(':uid', $depense->uid);
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

    public function deleteDepense($did, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE did = :did");
            $stmt->bindValue(":did", $did);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Depense\Depense");
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