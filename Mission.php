<?php

require_once('log.php');

/*
 * Class Mission
 *
 * contain all the information from a mission.
 * this class dosent create a new Mission when you create it.
 * when you create it, this contain a empty Mission.
 * you can select a Mission or create a new one.
 * modify all option and update it when you want.
 *
 * the update is not auto.
 *
 */
class Mission {
    private $ref;
    private $id;
    private $precision;
    private $mode_intervention; //site, telephone, distant
    private $etat;
    private $retard;
    private $date_created;
    private $date_wished;
    private $date_mission;
    private $paye;
    private $comment_helper;
    private $signature;
    private $result;
    private $helper;
    private $client;
    private $forfait;
    private $comment_client;
    private $test;

    public function __construct($orm, $constructor=null) {
        parent::__construct($orm, $constructor);
    }

    public function __destruct() {
        parent::__destruct();
    }

    private function hydrate(array $data) {
        $opt = {'ref':                 'ref',
            'id_m':                'id',
            'presicions_m':        'precision',
            'mode_intervention_m': 'mode_intervention',
            'etat_m':                'etat',
            'retard_m':            'retard',
            'date_demande_m':        'date_created',
            'date_souhaitee_m':    'date_wished',
            'date_intervention_m': 'date_mission',
            'paye_m':                'paye',
            'com_bil':             'comment_helper',
            'signature_bil':        'signature',
            'rep_result_bil':        'result',
            'id_h':                'helper',
            'id_cl':                'client',
            'forfait_m':            'forfait',
            'commentaire_m':        'comment_client',
            'test':                'test'
        };

        try {
            foreach ($data as $key => $value) {
                if ($key == 'id_h') {
                    $id = $value;
                    $value = new Helper($this->db);
                    $value->fillById($id);
                } else if ($key == 'id_cl') {
                    $id = $value;
                    $value = new Client($this->db);
                    $value->fillById($id);
                }
                if (isset($this->orm->missionopt[$key]))
                    $this->$opt[$key] = $value;
            }
        } catch (Exception $e) {
            printLog(__METHOD__, $e->getMessage(), true);
            return (false);
        }

        return (true);
    }

    private function fillById($id) {
        $stmt = $this->db->prepare('SELECT * FROM MISSION WHERE id_m=:id_m'); // change the star for what we want (* not secure)
        $stmt->bindParam(':id_m', $id);
        $stmt->execute();

        if ($stmt->rowCount() <= 0)
            return (false);

        return ($this->hydrate($stmt->fetch()));
    }

    public function selectHelperAuto() {
        //
    }

    public function selectHelper($id_h) {
        //
    }
}
