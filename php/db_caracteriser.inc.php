<?php
namespace Caracteriser;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Caracteriser{
    public $did;
    public $tid;

}

class CaracteriserRepository {
    const TABLE_NAME = 'Caracteriser';

    public function storeCaracteriser($caracteriser, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (did, tid) VALUES (:did, :tid)");
            $stmt->bindValue(':did', $caracteriser->did);
            $stmt->bindValue(':tid', $caracteriser->tid);
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

    public function deleteCaracteriser($caracteriser, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE did = :did or tid = :tid");
            $stmt->bindValue(":did", $caracteriser->did);
            $stmt->bindValue(":tid", $caracteriser->tid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Caracteriser\Caracteriser");
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