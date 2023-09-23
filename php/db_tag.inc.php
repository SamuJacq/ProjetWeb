<?php
namespace Tag;
require_once"db_link.inc.php";

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8','fra');

class Tag{
    public $tid;
    public $tag;
    public $gid;

}

class TagRepository {
    const TABLE_NAME = 'Tag';

    public function getAllTag(&$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $result = $db->query("SELECT * FROM " .self::TABLE_NAME , PDO::FETCH_CLASS,"Tag\Tag");
        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function getTagByTag($tag, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("SELECT * FROM " .self::TABLE_NAME. " WHERE tag = :tag");
            $stmt->bindValue(":tag", $tag);
            if($stmt-> execute()){
                $result = $stmt-> fetchObject("Tag\Tag");
            }else{
                $message .= "erreur survenue";
            }

        }catch(Exception $e){
            $message .= $e-> getMessage()."<br>";
        }
        DBLink::disconnect($db);
        return $result;
    }

    public function storeTag($tag, &$message){
        $noError = false;
        $bdd   = null;
        try {
            $bdd  = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME.
                " (uid, tag, gid) VALUES (:uid, :tag, :gid)");
            $stmt->bindValue(':tid', $tag->tid);
            $stmt->bindValue(':dateHeure', $tag->tag);
            $stmt->bindValue(':gid', $tag->gid);
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

    public function updateTag($tag, &$message){
        $noError = false;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("UPDATE ".self::TABLE_NAME.
                "SET tag=:tag WHERE tid = :tid");
            $stmt->bindValue(':tid', $tag->tid);
            $stmt->bindValue(':dateHeure', $tag->tag);
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

    public function deleteTag($tid, &$message){
        $result = null;
        $db = null;

        try{
            $db = DBLink::connect2db(MYDB, $message);
            $stmt = $db->prepare("DELETE FROM " .self::TABLE_NAME. " WHERE tid = :tid");
            $stmt->bindValue(":tid", $tid);
            if($stmt-> execute() && $stmt->rowCount() == 1){
                $result = $stmt-> fetchObject("Tag\Tag");
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