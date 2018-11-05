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
    require_once($class.'.php');
}

spl_autoload_register('classAutoLoad');

/* ---- End autoloading class function ---- */

require_once('log.php');
require_once('exception.php');

/*
 *
 */
abstract class DatabaseObject {
    protected $last_id;
    protected $historic = Array();

    abstract public function __construct(array $data);
    abstract public function __destruct();
    abstract public function toKey(string $key);
    abstract public function transformForZoho();

    final public function getLastId() {return ($this->last_id);}
    final public function getHistoric() {return ($this->historic);}
    final public function resetHistoric() {$this->historic = Array();}

    final protected function hydrate(array $data)
    {
        foreach($data as $key => $value) {
            $method = 'set'.$this->toKey($key);
            if (!$this->$method($value)) {
                printLog(__METHOD__, 'A set value from database failled: '.$method.'('.$value.')', 2);
                $this->$key = $value;
            }
        }
        $this->resetHistoric();
    }
}

/*
 * class ORM
 *
 * This class is used for loading for communicate to the database.
 * It can generate a new object or load one from different sources.
 * /!\ WARNING /!\
 * I use Mission and Prestation
 * this is not the same.
 * a Mission is the act where a helper go to the Client.
 * a Prestation is the thing that the Client need the helper for.
 *     (windows 10 blue screen, install printer, etc...)
 *
 */
final class ORM {
    private $db;
    private $zoho;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        // $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
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

    public function rollBack() // WARNING: Beta, do not use it.
    {
        $this->db->rollBack();
        return ($this->zoho->rollBack());
    }

    private function filtre(array $data)
    {
         foreach ($data as $key => $value) {
             if (!is_string($key))
                 unset($data[$key]);
         }
         return ($data);
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
            $result = new Helper($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Helper id not unique. /!\\', true);
            $result = new Helper($this->filtre($stmt->fetch()));
        } else
            printLog(__METHOD__, 'Helper id: '.$id.' not found');
        printLog(__METHOD__, 'Helper id '.$id.'. found ');
        return ($result);
    }

    /* ---- Client related function ---- */
    public function getClientById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM CLIENT WHERE id_cl=:id_cl');
        $stmt->bindParam(':id_cl', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Client($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Client id not unique. /!\\', true);
            $result = new Client($this->filtre($stmt->fetch()));
        } else {
            printLog(__METHOD__, 'Client id: '.$id.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Client id '.$id.'. found ');
        return ($result);
    }

    public function getClientByIdZoho($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM CLIENT WHERE idclientzoho=:idclientzoho');
        $stmt->bindParam(':idclientzoho', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Client($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Client idzoho not unique. /!\\', true);
            $result = new Client($this->filtre($stmt->fetch()));
        } else {
            printLog(__METHOD__, 'Client idzoho: '.$id.'. not found');
            return (false);
        }
        printLog(__METHOD__, 'Client idzoho: '.$id.'. found ');
        return ($result);
    }

    /* ---- Mission related function ---- */
    public function getMissionById($id)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE id_m=:id_m;');
        $stmt->bindParam(':id_m', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Mission($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission id not unique. /!\\', true);
            $result = new Mission($this->filtre($stmt->fetch()));
        } else {
            printLog(__METHOD__, 'Mission id: '.$id.' not found');
            return (false);
        }
        printLog(__METHOD__, 'Mission id '.$id.'. found ');
        return ($result);
    }

    public function getMissionByRef($ref)
    {
        $result = null;

        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE ref_m=:ref_m');
        $stmt->bindParam(':ref_m', $ref);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Mission($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Mission ref not unique. /!\\', true);
            $result = new Mission($this->filtre($stmt->fetch()));
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
        $stmt->bindParam(':id_presta', $id, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 1)
            $result = new Prestation($this->filtre($stmt->fetch()));
        else if ($stmt->rowCount() > 1) {
            printLog(__METHOD__, 'Prestation id not unique. /!\\', true);
            $result = new Prestation($this->filtre($stmt->fetch()));
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
    public function createMissionFromIdClient($id, bool $auto=false, $ref=NULL)
    {
        $client = $this->getClientById($id);
        if ($client === false) {
            printLog(__METHOD__, 'Trying to get a client with a invalid id.', 2);
            throw new ORMException('Trying to get a client with a invalid id');
        }
        return ($this->createMissionFromClient($client, $auto, $ref));
    }

    public function createMissionFromClient(Client $client, bool $auto=false, $ref=NULL)
    {
        if (!isset($param['auto']) || !isset($param['ref'])) {
            printLog(__METHOD__, 'Parameter without needed value.', 2);
            return (NULL);
        }
        $ref = $param['ref'];
        $auto = $param['auto'];
        if ($ref == NULL) {
            $ref = date('ymdH').'-'.Helper::createQuadri($client->getPrenom(), $client->getNom()).'-';

            $req = $this->db->prepare('SELECT MAX(CONVERT(SUBSTRING(ref_m, 15), UNSIGNED INTEGER)) as maximum from MISSION where ref_m like ":ref%"');
            $req->bindParam(':ref', $ref);
            $req->execute();
            $maxArr = $req->fetch();
            $ref .= (string) (isset($maxArr['maximum']) ? $maxArr['maximum'] + 1 : 1);
        }
        $data = ['ref_m' => $ref, 'id_cl' => $client->getId()];
        $data['id_m'] = $this->createMissionId($auto);
        try {
            $mission = new Mission($this->filtre($data));
        } catch (MissionException $e) {
            printLog(__METHOD__, 'Cannot create a missoin from invalid data:\n'.print_r($data, true), true);
            throw new ORMException('Cannot create a mission from invalid data', $e->getCode(), $e);
        }

        $req = $this->db->prepare('INSERT INTO MISSION (id_m, ref_m, id_cl) VALUE (:id_m, :ref_m, :id_cl);');
        $req->bindValue(':id_m', $data['id_m']);
        $req->bindValue(':ref_m', $data['ref_m']);
        $req->bindValue(':id_cl', $data['id_cl']);
        $req->execute();

        $this->zoho->insertToCrm('Prestations', $mission->transformForZoho());

        return ($mission);
    }

    private function createMissionId(bool $auto=false)
    {
        if ($auto)
            $sql = 'SELECT MAX(CONVERT(SUBSTRING(id_m, 2), UNSIGNED INTEGER)) as maximum from MISSION where id_m LIKE "s%";';
        else
            $sql = 'SELECT MAX(CONVERT(id_m, UNSIGNED INTEGER)) as maximum from MISSION where id_m NOT LIKE "s%";';

        $req = $this->db->query($sql);
        $data = $req->fetch();

        if (isset($data['maximum']))
            $newid = $data['maximum'] + 1;
        else
            $newid = 1;

        return (($auto ? 's' : '').$newid);
    }

    /* ---- Client related function ---- */
    public function createClientFromData(array $data)
    {
        $sql = "insert into `CLIENT` (";
        $val = '';

        foreach ($data as $key => $value) {
            $sql .= $key.', ';
            $val .= ':'.$key.', ';
        }
        $sql = substr($sql, 0, strlen($sql) - 2).') VALUE (';
        $val = substr($val, 0, strlen($val) - 2);
        $sql .= $val.');';

        printLog(__METHOD__, 'insert client: sql request: '.$sql);

        $req = $this->bd->prepare($sql);

        foreach ($data as $key => $value) {
            $req->bindValue(':'.$key, $value);
        }

        $req->execute();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    /* ---- Helper related function ---- */
    public function createHelperFromData(array $data)
    {
        $sql = "insert into `HELPER` (";
        $val = '';

        foreach ($data as $key => $value) {
            $sql .= $key.', ';
            $val .= ':'.$key.', ';
        }
        $sql = substr($sql, 0, strlen($sql) - 2).') VALUE (';
        $val = substr($val, 0, strlen($val) - 2);
        $sql .= $val.');';

        printLog(__METHOD__, 'insert helper: sql request: '.$sql);

        $req = $this->bd->prepare($sql);

        foreach ($data as $key => $value) {
            $req->bindValue(':'.$key, $value);
        }

        $req->execute();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    /**************************************/
    /* ----- Update to the database ----- */ // A MODIFIER: AJOUTER ZOHO --'
    /**************************************/

    /* ---- Mission related function ---- */
    public function updateMission_db(Mission $mission)
    {
        if (count($mission->getHistoric()) <= 0)
            return (false);
        $sql = 'UPDATE `MISSION` SET ';

        $updateValue = array();

        foreach($mission->getHistoric() as $value) {
            if (in_array($value, $updateValue) === false) {
                $sql .= '`'.$value.'`=:'.$value.', ';
                $updateValue[] = $value;
            }
        }
        $sql = substr($sql, - strlen($sql), -2);
        $sql .= ' WHERE id_m=:last_id_m';

        $req = $this->db->prepare($sql);

        foreach($updateValue as $key) {
            $method = 'get'.$mission->toKey($key);
            $value = $mission->$method();
            printLog(__METHOD__, '$req->bindValue(":'.$key.'", $'.$value.'); // mission->'.$method.'()');
            if ($value === null)
                $req->bindValue(':'.$key, null, PDO::PARAM_INT);
            else
                $req->bindValue(':'.$key, $value);
            $sql = str_replace(':'.$key, $value, $sql);
        }

        $lastId = $mission->getLastId();

        $sql = str_replace(':last_id_m', $lastId, $sql);

        $req->bindParam(':last_id_m', $lastId);
        $req->execute();

        printLog(__METHOD__, 'requete: '.$sql.'\nhostoric: '.print_r($mission->getHistoric(), true).'return code: '.$req->errorCode());
        $mission->resetHistoric();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    public function updateMission_zoho(Mission $mission)
    {
        $data = $mission->transformForZoho();
        $dataCRM = $this->zoho->getFromCRM('Prestations', 'Name', $data['Name']);
        $data['id'] = $dataCRM['id'];

        return ($this->zoho->updateToCRM('Prestations', $data));
    }

    public function updateMission(Mission $mission)
    {
        if (!$this->updateMission_db($mission)) {
            printLog(__METHOD__, 'Update to the database failled.', 1);
            throw new ORMException('Update to the database failled.');
        }
        if (!$this->updateMission_zoho($mission)) {
            printLog(__METHOD__, 'Update to the CRM failled.', 1);
            throw new ORMException('Update to the CRM failled.');
        }
    }

    /* ---- Helper related function ---- */
    public function updateHelper_db(Helper $helper)
    {
        if (count($helper->getHistoric()) <= 0)
            return (false);
        $sql = 'UPDATE `HELPER` SET ';

        $updateValue = array();

        foreach($helper->getHistoric() as $value) {
            if (in_array($value, $updateValue) === false) {
                $sql .= '`'.$value.'`=:'.$value.', ';
                $updateValue[] = $value;
            }
        }
        $sql = substr($sql, - strlen($sql), -2);
        $sql .= ' WHERE id_h=:last_id_h';

        $req = $this->db->prepare($sql);

        foreach($updateValue as $key) {
            $method = 'get'.$helper->toKey($key);
            $value = $helper->$method();
            printLog(__METHOD__, '$req->bindValue(":'.$key.'", '.$value.'); // $helper->'.$method.'()');
            if ($value === null)
                $req->bindValue(':'.$key, null, PDO::PARAM_INT);
            else
                $req->bindValue(':'.$key, $value);
            $sql = str_replace(':'.$key, $value, $sql);
        }

        $lastId = $helper->getLastId();

        $sql = str_replace(':last_id_h', $lastId, $sql);

        $req->bindParam(':last_id_h', $lastId);
        $req->execute();

        printLog(__METHOD__, 'requete: '.$sql.'\nhostoric: '.print_r($helper->getHistoric(), true).'return code: '.$req->errorCode());
        $helper->resetHistoric();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    public function updateHelper_zoho(Helper $helper)
    {
        $data = $helper->transformForZoho();
        $dataCRM = $this->zoho->getFromCRM('Helpers', 'ID_Helper', $data['ID_Helper']);
        $data['id'] = $dataCRM['id'];

        return ($this->zoho->updateToCRM('Helpers', $data));
    }

    public function updateHelper(Helper $helper)
    {
        if (!$this->updateHelper_db($helper)) {
            printLog(__METHOD__, 'Update to the database failled.', 1);
            throw new ORMException('Update to the database failled.');
        }
        if (!$this->updateHelper_zoho($helper)) {
            printLog(__METHOD__, 'Update to the CRM failled.', 1);
            throw new ORMException('Update to the CRM failled.');
        }
    }

    /* ---- Client related function ---- */
    public function updateClient_db(Client $client)
    {
        if (count($client->getHistoric()) <= 0)
            return (false);
        $sql = 'UPDATE `CLIENT` SET ';

        $updateValue = array();

        foreach($client->getHistoric() as $value) {
            if (in_array($value, $updateValue) === false) {
                $sql .= '`'.$value.'`=:'.$value.', ';
                $updateValue[] = $value;
            }
        }
        $sql = substr($sql, - strlen($sql), -2);
        $sql .= ' WHERE id_cl=:last_id_cl';

        $req = $this->db->prepare($sql);

        foreach($updateValue as $key) {
            $method = 'get'.$client->toKey($key);
            $value = $client->$method();
            printLog(__METHOD__, '$req->bindValue(":'.$key.'", '.$value.'); // $client->'.$method.'()');
            if ($value === null)
                $req->bindValue(':'.$key, null, PDO::PARAM_INT);
            else
                $req->bindValue(':'.$key, $value);
            $sql = str_replace(':'.$key, $value, $sql);
        }

        $lastId = $client->getLastId();

        $sql = str_replace(':last_id_cl', $lastId, $sql);

        $req->bindParam(':last_id_cl', $lastId);
        $req->execute();

        printLog(__METHOD__, 'requete: '.$sql.'\nhostoric: '.print_r($client->getHistoric(), true).'return code: '.$req->errorCode());
        $client->resetHistoric();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    public function updateClient_zoho(Client $client)
    {
        $data = $client->transformForZoho();
        $dataCRM = $this->zoho->getFromCRM('Contacts', 'Email', $data['Email']);
        $data['id'] = $dataCRM['id'];

        return ($this->zoho->updateToCRM('Contacts', $data));
    }

    public function updateClient(Client $client)
    {
        if (!$this->updateClient_db($client)) {
            printLog(__METHOD__, 'Update to the database failled.', 1);
            throw new ORMException('Update to the database failled.');
        }
        if (!$this->updateClient_zoho($client)) {
            printLog(__METHOD__, 'Update to the CRM failled.', 1);
            throw new ORMException('Update to the CRM failled.');
        }
    }

    /* ---- Prestation related function ---- */
    public function updatePrestation_db(Prestation $presta)
    {
        if (count($presta->getHistoric()) <= 0)
            return (false);
        $sql = 'UPDATE `PRESTATION` SET ';

        $updateValue = array();

        foreach($presta->getHistoric() as $value) {
            if (in_array($value, $updateValue) === false) {
                $sql .= '`'.$value.'`=:'.$value.', ';
                $updateValue[] = $value;
            }
        }
        $sql = substr($sql, - strlen($sql), -2);
        $sql .= ' WHERE id_presta=:last_id_p';

        $req = $this->db->prepare($sql);

        foreach($updateValue as $key) {
            $method = 'get'.$presta->toKey($key);
            $value = $presta->$method();
            printLog(__METHOD__, '$req->bindValue(":'.$key.'", '.$value.'); // prestation->'.$method.'()');
            if ($value === null)
                $req->bindValue(':'.$key, NULL, PDO::PARAM_INT);
            else
                $req->bindValue(':'.$key, $value);
            $sql = str_replace(':'.$key, $value, $sql);
        }

        $lastId = $presta->getLastId();

        $sql = str_replace(':last_id_p', $lastId, $sql);

        $req->bindParam(':last_id_p', $lastId);
        $req->execute();

        printLog(__METHOD__, 'requete: '.$sql.'\nhostoric: '.print_r($presta->getHistoric(), true).'return code: '.$req->errorCode());
        $presta->resetHistoric();

        if ($req->errorCode() === '00000')
            return (true);
        return (false);
    }

    public function updatePrestation(Prestation $presta)
    {
        if (!$this->updatePrestation_db($presta)) {
            printLog(__METHOD__, 'Update to the database failled.', 1);
            throw new ORMException('Update to the database failled.');
        }
        if (!$this->updatePrestation_zoho($presta)) {
            printLog(__METHOD__, 'Update to the CRM failled.', 1);
            throw new ORMException('Update to the CRM failled.');
        }
    }
}
