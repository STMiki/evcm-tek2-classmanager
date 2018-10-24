<?php

require_once('log.php');

/*
 * Class Mission
 *
 * contain all the information from a mission.
 * modify all option and update it when you want.
 *
 * the update is not auto.
 *
 */
class Mission {
    private $db;
    private $last_id;

    private $ref_m = null;
    private $id_m = null;
    private $precisions_m = null;
    private $mode_intervention_m = null;
    private $etat_m = null;
    private $retard_m = null;
    private $date_demande_m = null;
    private $date_souhaitee_m = null;
    private $date_intervention_m = null;
    private $date_fin_m = null;
    private $support_utilise_m = null;
    private $urgence_m = null;
    private $facture_envoyee_m = null;
    private $paye_m = null;
    private $com_bil = null;
    private $signature_bil = null;
    private $rep_resultat_bil = null;
    private $rep_question1_bil = null;
    private $rep_question2_bil = null;
    private $rep_question3_bil = null;
    private $rep_question4_bil = null;
    private $satisfaction_bil = null;
    private $id_h = null;
    private $id_cl = null;
    private $forfait_m = null;
    private $commentaire_m = null;
    private $test = null;
    
    public function __construct(PDO $db, array $data)
    {
        $this->db  = $db;
        if (!isset($data['id_m'])) {
            printLog(__METHOD__, 'Creating a new Mission without primary key', true);
            throw new MissionException('Creating a new Mission without primary key');
        }
        $this->hydrate($data);
        $this->last_id = $data['id_m'];
    }

    public function __destruct()
    {
        $this->db = null;
    }

    private function hydrate(array $data)
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function selectHelper($id_h)
    {
        //
    }
}
