<?php

/*
 * ORM.php
 *
 * ORM (Object Relational Mapping)
 * https://en.wikipedia.org/wiki/Object-relational_mapping
 *
 * I will use this for load and create my class.
 * All class (prestation, mission, helper, etc...) will be
 * created using this ORM
 *
 * The database will be created in the ORM, so if you ever
 * need the db, you can get it from here.
 *
 */

/* --------------------------------------------
 * ---  Load automaticaly the class needed  ---
 * --- The file need to be like 'Class.php' ---
 * --------------------------------------------
 */
function classAutoLoad($class) {
    require($class.'.php');
}

spl_autoload_register('classAutoLoad');

/* ---- End autoloading class function ---- */

require_once('log.php');
require_once('exception.php');

/*
 * class ORM
 *
 * This class is used for loading for communicate to the database.
 * /!\ WARNING /!\
 * I use Mission and Prestation
 * this is not the same.
 * a Mission is the act where a helper go to the Client need it.
 * a Prestation is the thing that the Client need the helper for.
 *     (windows 10 blue screen, install printer, etc...)
 *
 */
class ORM {
    private $db;
    private $zoho;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
        $this->zoho = new Zoho($this->db);
    }

    public function __destruct()
    {
        $this->db = null;
        $this->zoho = null;
    }

    public function getDatabase()
    {
        return ($this->db);
    }

    public function rollBack()
    {
        $this->db->rollBack();
        return ($this->zoho->rollBack());
    }

    /*************************************/
    /* ----- Get from the database ----- */
    /*************************************/

    /* ---- Helper related function ---- */
    public function getHelperById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM HELPER WHERE id_h=:id_h');
        $stmt->bindParam(':id_h', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Helper($this->db, $stmt->fetch());
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Helper id not unique. /!\\', true);
            $result = new Helper($this->db, $stmt->fetch());
        } else
            printLog(__METHOD__, 'Helper id: '.$id.' not found');
        printLog(__METHOD__, 'Helper id '.$id.'. found ');
        return ($result);
    }

    /* ---- Client related function ---- */
    public function getClientById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM CLIENT WHERE id_c=:id_c');
        $stmt->bindParam(':id_c', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Client($this->db, $stmt->fetch());
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Client id not unique. /!\\', true);
            $result = new Client($this->db, $stmt->fetch());
        } else {
            printLog(__METHOD__, 'Client id: '.$id.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Client id '.$id.'. found ');
        return ($result);
    }

    public function getClientByIdZoho($id)
    {

    }

    /* ---- Mission related function ---- */
    public function getMissionById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE id_m=:id_m');
        $stmt->bindParam(':id_m', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Mission($this->db, $stmt->fetch());
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission id not unique. /!\\', true);
            $result = new Mission($this->db, $stmt->fetch());
        } else {
            printLog(__METHOD__, 'Mission id: '.$id.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Mission id '.$id.'. found ');
        return ($result);
    }

    public function getMissionByIdZoho($id)
    {

    }

    public function getMissionByRef($ref)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE ref_m=:ref_m');
        $stmt->bindParam(':ref_m', $ref);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Mission($this->db, $stmt->fetch());
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission ref not unique. /!\\', true);
            $result = new Mission($this->db, $stmt->fetch());
        } else {
            printLog(__METHOD__, 'Mission ref: '.$ref.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Mission ref '.$ref.'. found ');
        return ($result);
    }

    /* ---- Prestation related function ---- */
    public function getPrestationById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM PRESTATION WHERE id_presta=:id_presta');
        $stmt->bindParam(':id_presta', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Prestation($this->db, $stmt->fetch());
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Prestation id not unique. /!\\', true);
            $result = new Prestation($this->db, $stmt->fetch());
        } else {
            printLog(__METHOD__, 'Prestation id: '.$id.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Prestation id '.$id.'. found ');
        return ($result);
    }

    /**************************************/
    /* ----- Create to the database ----- */
    /**************************************/

    /* ---- Mission related function ----- */
    public function createMissionRef(array $data)
    {
        if (!isset($data['prenom_cl']) || !isset($data['nom_cl'])) {
            if (!isset($data['id_cl']))
                return (false);
            $req = $this->db->prepare('select prenom_cl, nom_cl from CLIENT where id_cl=:id_cl;');
            $req->bindParam(':id_cl', $data['id_cl']);
            $req->execute();
            if ($req->rowCount() == 1)
                $data = $req->fetch();
            else if ($req->rowCount() > 1) {
                printLog(__METHOD__, 'request id result to more that one result: '.$data['id_cl'], true);
                throw new ORMException('Request ID to the databse return more than one result: '.$data['id_cl']);
            } else
                return (false);
        }

        $ref = date('ymdH').'-'.strtoupper(substr($data['prenom_cl'], 0, 2)).strtoupper(substr($data['nom_cl'], 0, 2)).'-';

        $req = $this->db->prepare('SELECT MAX(CONVERT(SUBSTRING(ref_m, 15), UNSIGNED INTEGER)) as maximum from MISSION where ref_m like ":ref%"');
        $req->bindParam(':ref', $ref);
        $req->execute();
        $data = $req->fetch();

        if (!isset($data['maximum']))
            return ($ref.'1');
        return ($ref.($data['maximum'] + 1));
    }

    private function createMissionId(bool $auto=false)
    {
        if ($auto)
            $sql = 'select MAX(CONVERT(SUBSTRING(id_m, 2), UNSIGNED INTEGER)) as maximum from MISSION where id_m LIKE "s%";';
        else
            $sql = 'select MAX(CONVERT(id_m, UNSIGNED INTEGER)) as maximum from MISSION where id_m NOT LIKE "s%";';

        $req = $this->db->query($sql);
        $data = $req->fetch();

        if (isset($data['maximum']))
            $newid = $data['maximum'] + 1;
        else
            $newid = 1;

        $req = $this->db->query('SELECT MAX(CONVERT(SUBSTRING(ref_m, 2), UNSIGNED INTEGER)) AS ref FROM MISSION WHERE ref_m LIKE "T%";');
        $data = $req->fetch();

        if (isset($data['ref']))
            $ref = $data['ref'] + 1;
        else
            $ref = 1;

        $newid = ($auto ? 's' : '').$newid;
        $ref = 'T'.$ref;

        $req = $this->db->prepare('INSERT INTO `MISSION` (`id_m`, `ref_m`) VALUE (:id_m, :ref_m);');
        $req->bindParam(':id_m', $newid);
        $req->bindParam(':ref_m', $ref);
        $req->execute();

        return ($newid);
    }

    public function createMission(array $data, bool $auto=false)
    {
        
        return ([$this->createMissionId(), $this->createMissionId(true)]);
    }

    /**************************************/
    /* ----- Update to the database ----- */
    /**************************************/

    public function updateMission(Mission $data)
    {
        
    }
}
