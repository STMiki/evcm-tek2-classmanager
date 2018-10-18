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
        $this->db = $Database()->getConnection();
//        $this->zoho = jsepfza;
    }

    public function __destruct()
    {
        //
    }

    public function getDatabase()
    {
        return ($this->db);
    }

    /*************************************/
    /* ----- Get from the database ----- */
    /*************************************/

    /* ---- Helper related function ---- */
    public function getHelperById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM HELPER WHERE id_h=:id_h');
        $stmt->bndParam(':id_h', $id);
        if ($stmt->rowCount() == 1)
            $result = new Helper($stmt->fetch();
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Helper id not unique. /!\\', true);
            $result = new Helper($stmt->fetch();
        } else
            printLog(__METHOD__, 'Helper id: '.$id.' not found');
        printLog(__METHOD__, 'Helper id '.$id'. found ');
        return ($result);
    }

    /* ---- Client related function ---- */
    public function getClientById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM CLIENT WHERE id_c=:id_c');
        $stmt->bndParam(':id_c', $id);
        if ($stmt->rowCount() == 1)
            $result = new Client($stmt->fetch();
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Client id not unique. /!\\', true);
            $result = new Client($stmt->fetch();
        } else
            printLog(__METHOD__, 'Client id: '.$id.' not found');
        printLog(__METHOD__, 'Client id '.$id'. found ');
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
        $stmt->bndParam(':id_m', $id);
        if ($stmt->rowCount() == 1)
            $result = new Mission($stmt->fetch();
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission id not unique. /!\\', true);
            $result = new Mission($stmt->fetch();
        } else
            printLog(__METHOD__, 'Mission id: '.$id.' not found');
        printLog(__METHOD__, 'Mission id '.$id'. found ');
        return ($result);
    }

    public function getMissionByIdZoho($id)
    {

    }

    public function getMissionByRef($ref)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE ref_m=:ref_m');
        $stmt->bndParam(':ref_m', $ref);
        if ($stmt->rowCount() == 1)
            $result = new Mission($stmt->fetch();
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission ref not unique. /!\\', true);
            $result = new Mission($stmt->fetch();
        } else
            printLog(__METHOD__, 'Mission ref: '.$ref.' not found');
        printLog(__METHOD__, 'Mission ref '.$ref'. found ');
        return ($result);
    }

    /* ---- Prestation related function ---- */
    private function getPrestationById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM PRESTATION WHERE id_presta=:id_presta');
        $stmt->bndParam(':id_presta', $id);
        if ($stmt->rowCount() == 1)
            $result = new Prestation($stmt->fetch();
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Prestation id not unique. /!\\', true);
            $result = new Prestation($stmt->fetch();
        } else
            printLog(__METHOD__, 'Prestation id: '.$id.' not found');
        printLog(__METHOD__, 'Prestation id '.$id'. found ');
        return ($result);
    }
}
