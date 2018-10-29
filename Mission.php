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
final class Mission extends DatabaseObject {
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
    private $id_prestation = null;
    private $commentaire_m = null;
    private $test = null;

    public function __construct(array $data)
    {
        if (!isset($data['id_m'])) {
            printLog(__METHOD__, 'Creating a new Mission without primary key', true);
            throw new MissionException('Creating a new Mission without primary key');
        }
        $this->hydrate($data);
        $this->last_id = $data['id_m'];
    }

    public function __destruct()
    {
        $this->ref_m = null;
        $this->id_m = null;
        $this->precisions_m = null;
        $this->mode_intervention_m = null;
        $this->etat_m = null;
        $this->retard_m = null;
        $this->date_demande_m = null;
        $this->date_souhaitee_m = null;
        $this->date_intervention_m = null;
        $this->date_fin_m = null;
        $this->support_utilise_m = null;
        $this->urgence_m = null;
        $this->facture_envoyee_m = null;
        $this->paye_m = null;
        $this->com_bil = null;
        $this->signature_bil = null;
        $this->rep_resultat_bil = null;
        $this->rep_question1_bil = null;
        $this->rep_question2_bil = null;
        $this->rep_question3_bil = null;
        $this->rep_question4_bil = null;
        $this->satisfaction_bil = null;
        $this->id_h = null;
        $this->id_cl = null;
        $this->forfait_m = null;
        $this->commentaire_m = null;
        $this->test = null;
    }

    protected function hydrate(array $data)
    {
        foreach($data as $key => $value) {
            $method = 'set'.$this->toKey($key);
            $this->$method($value);
        }
        $this->resetHistoric();
    }

    public function toKey(string $value)
    {
        $data = explode('_', $value);
        $result = '';
        foreach ($data as $word) {
            if ($word !== 'm') {
                if ($word === 'cl')
                    $result .= ucFirst('Client');
                else if ($word === 'h')
                    $result .= ucFirst('Helper');
                else
                    $result .= ucFirst($word);
            }
        }
        return ($result);
    }

    public function getRef()              {return ($this->ref_m);}
    public function getId()               {return ($this->id_m);}
    public function getPrecisions()       {return ($this->precisions_m);}
    public function getModeIntervention() {return ($this->mode_intervention_m);}
    public function getEtat()             {return ($this->etat_m);}
    public function getRetard()           {return ($this->retard_m);}
    public function getDateDemande()      {return ($this->date_demande_m);}
    public function getDateSouhaitee()    {return ($this->date_souhaitee_m);}
    public function getDateIntervention() {return ($this->date_intervention_m);}
    public function getDateFin()          {return ($this->date_fin_m);}
    public function getSupportUtilise()   {return ($this->support_utilise_m);}
    public function getUrgence()          {return ($this->urgence_m);}
    public function getFactureEnvoyee()   {return ($this->facture_envoyee_m);}
    public function getPaye()             {return ($this->paye_m);}
    public function getComBil()           {return ($this->com_bil);}
    public function getSignatureBil()     {return ($this->signature_bil);}
    public function getRepResultatBil()   {return ($this->rep_resultat_bil);}
    public function getRepQuestion1Bil()  {return ($this->rep_question1_bil);}
    public function getRepQuestion2Bil()  {return ($this->rep_question2_bil);}
    public function getRepQuestion3Bil()  {return ($this->rep_question3_bil);}
    public function getRepQuestion4Bil()  {return ($this->rep_question4_bil);}
    public function getSatisfactionBil()  {return ($this->satisfaction_bil);}
    public function getIdHelper()         {return ($this->id_h);}
    public function getIdCLient()         {return ($this->id_cl);}
    public function getForfait()          {return ($this->forfait_m);}
    public function getIdPrestation()     {return ($this->id_prestation);}
    public function getCommentaire()      {return ($this->commentaire_m);}
    public function getTest()             {return ($this->test);}

    public function setRef(string $ref)
    {
        $ref = (string) $ref;
        if (is_string($ref) && preg_match('/^([0-9]{2}((0[1-9])|(1[0-2]))((0[1-9])|([12][0-9])|(3[01]))((0[0-9])|(1[0-9])|(2[0-3])))-[A-Z]{4}-[1-9]([0-9]{1,})?$/', $ref) && strlen($ref) <= 25) {
            $this->ref_m = $ref;
            $this->historic[] = 'ref_m';
            return (true);
        }
        return (false);
    }

    public function setId($id) // string or null
    {
        if (is_int($id))
            $id = (string) $id;
        if ($id === null || (is_string($id) && preg_match('/^s?[1-9][0-9]{0,}$/', $id) && strlen($id) <= 20)) {
            $this->id_m = $id;
            $this->historic[] = 'id_m';
            return (true);
        }
        return (false);
    }

    public function setPrecisions($precisions) // string or null
    {
        if ($precisions !== null)
            $precisions = (string) $precisions;
        if ($precisions === null || is_string($precisions)) {
            $this->precisions_m = $precisions;
            $this->historic[] = 'precisions_m';
            return (true);
        }
        return (false);
    }

    public function setModeIntervention(string $mode)
    {
        $mode = (string) $mode;
        if (is_string($mode) && in_array($mode, ['A', 'S', 'D', 'T'])) {
            $this->mode_intervention_m = $mode;
            $this->historic[] = 'mode_intervention_m';
            return (true);
        }
        return (false);
    }

    public function setEtat(int $etat)
    {
        $etat = (int) $etat;
        if (is_int($etat) && ($etat >= -1 && $etat <= 6)) {
            $this->etat_m = $etat;
            $this->historic[] = 'etat_m';
            return (true);
        }
        return (false);
    }

    public function setRetard(int $retard)
    {
        $retard = (int) $retard;
        if (is_int($retard) && $retard >= 0) {
            $this->retard_m = $retard;
            $this->historic[] = 'retard_m';
            return (true);
        }
        return (false);
    }

    public function setDateDemande($date) // string or null
    {
        if ($date !== null)
            $date = (string) $date;
        if ($date === null || (is_string($date) && preg_match('/^([0-9]{4}-((0[1-9])|(1[0-2]))-((0[1-9])|([12][0-9])|(3[01]))\ (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])$/', $date))) {
            $this->date_demande_m = $date;
            $this->historic[] = 'date_demande_m';
            return (true);
        }
        return (false);
    }

    public function setDateSouhaitee($date) // string or null
    {
        if ($date !== null)
            $date = (string) $date;
        if ($date === null || (is_string($date) && preg_match('/^([0-9]{4}-((0[1-9])|(1[0-2]))-((0[1-9])|([12][0-9])|(3[01])) (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])$/', $date))) {
            $this->date_souhaitee_m = $date;
            $this->historic[] = 'date_souhaitee_m';
            return (true);
        }
        return (false);
    }

    public function setDateIntervention($date) // string or null
    {
        if ($date !== null)
            $date = (string) $date;
        if ($date === null || (is_string($date) && preg_match('/^([0-9]{4}-((0[1-9])|(1[0-2]))-((0[1-9])|([12][0-9])|(3[01])) (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])$/', $date))) {
            $this->date_intervention_m = $date;
            $this->historic[] = 'date_intervention_m';
            return (true);
        }
        return (false);
    }

    public function setDateFin($date) // string or null
    {
        if ($date !== null)
            $date = (string) $date;
        if ($date === null || (is_string($date) && preg_match('/^([0-9]{4}-((0[1-9])|(1[0-2]))-((0[1-9])|([12][0-9])|(3[01])) (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])$/', $date))) {
            $this->date_fin_m = $date;
            $this->historic[] = 'date_fin_m';
            return (true);
        }
        return (false);
    }

    public function setSupportUtilise(bool $used)
    {
        $used = (bool) $used;
        if (is_bool($used)) {
            $this->support_utilise_m = $used;
            $this->historic[] = 'support_utilise_m';
            return (true);
        }
        return (false);
    }

    public function setUrgence(bool $urgence)
    {
        $urgence = (bool) $urgence;
        if (is_bool($urgence)) {
            $this->urgence_m = $urgence;
            $this->historic[] = 'urgence_m';
            return (true);
        }
        return (false);
    }

    public function setFactureEnvoyee(bool $send)
    {
        $send = (bool) $send;
        if (is_bool($send)) {
            $this->facture_envoyee_m = $send;
            $this->historic[] = 'facture_envoyee_m';
            return (true);
        }
        return (false);
    }

    public function setPaye(bool $paye)
    {
        $paye = (bool) $paye;
        if (is_bool($paye)) {
            $this->paye_m = $paye;
            $this->historic[] = 'paye_m';
            return (true);
        }
        return (false);
    }

    public function setComBil($commentaire) // string or null
    {
        if ($commentaire !== null)
            $commentaire = (string) $commentaire;
        if ($commentaire === null || is_string($commentaire)) {
            $this->com_bil = $commentaire;
            $this->historic[] = 'com_bil';
            return (true);
        }
        return (false);
    }

    public function setSignatureBil($url) // string(url) or null
    {
        if ($url !== null)
            $url = (string) $url;
        if ($url === null || (is_string($url) && preg_match("/^[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}([-a-zA-Z0-9@:%_\+.~#?&\/\/=]*)$/", $url) && strlen($url) <= 150)) {
            $this->signature_bil = $url;
            $this->historic[] = 'signature_bil';
            return (true);
        }
        return (false);
    }

    public function setRepResultatBil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->rep_resultat_bil = $reponse;
            $this->historic[] = 'rep_resultat_bil';
            return (true);
        }
        return (false);
    }

    public function setRepQuestion1Bil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->rep_question1_bil = $reponse;
            $this->historic[] = 'rep_question1_bil';
            return (true);
        }
        return (false);
    }

    public function setRepQuestion2Bil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->rep_question2_bil = $reponse;
            $this->historic[] = 'rep_question2_bil';
            return (true);
        }
        return (false);
    }

    public function setRepQuestion3Bil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->rep_question3_bil = $reponse;
            $this->historic[] = 'rep_question3_bil';
            return (true);
        }
        return (false);
    }

    public function setRepQuestion4Bil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->rep_question4_bil = $reponse;
            $this->historic[] = 'rep_question4_bil';
            return (true);
        }
        return (false);
    }

    public function setSatisfactionBil($reponse) // bool or null
    {
        if ($reponse !== null)
            $reponse = (bool) $reponse;
        if ($reponse === null || is_bool($reponse)) {
            $this->satisfaction_bil = $reponse;
            $this->historic[] = 'satisfaction_bil';
            return (true);
        }
        return (false);
    }

    public function setIdHelper($id) // int or null
    {
        if ($id !== null)
            $id = (int) $id;
        if ($id === null || is_int($id)) {
            $this->id_h = $id;
            $this->historic[] = 'id_h';
            return (true);
        }
        return (false);
    }

    public function setIdCLient($id) // int or null
    {
        if ($id !== null)
            $id = (int) $id;
        if ($id === null || is_int($id)) {
            $this->id_cl = $id;
            $this->historic[] = 'id_cl';
            return (true);
        }
        return (false);
    }

    public function setForfait($forfait) // string or null
    {
        if ($forfait !== null)
            $forfait = (string) $forfait;
        if ($forfait === null || (is_string($forfait) && strlen($forfait) <= 1)) {
            $this->forfait_m = $forfait;
            $this->historic[] = 'forfait_m';
            return (true);
        }
        return (false);
    }

    public function setIdPrestation($id)
    {
        if($id !== null)
            $id = (string) $id;
        if ($id === null || (is_string($id) && preg_match('/^[0-9]{3}(\-[0-9]{3}){0,}$/', $id))) {
            $this->id_prestation = $id;
            $this->historic[] = 'id_prestation';
            return (true);
        }
        return (false);
    }

    public function setCommentaire($commentaire) // string or null
    {
        if ($commentaire !== null)
            $commentaire = (string) $commentaire;
        if ($commentaire === null || is_string($commentaire)) {
            $this->commentaire_m = $commentaire;
            $this->historic[] = 'commentaire_m';
            return (true);
        }
        return (false);
    }

    public function setTest($test) // string or null
    {
        if ($test === null || is_int($test) || is_bool($test) || is_string($test)) {
                if ($test === null)
                    $this->test = $test;
                else if (is_string($test) && in_array(strtolower($test), ['oui', 'non']))
                    $this->test = (strtolower($test) === 'oui' ? 'OUI' : 'NON');
                else {
                    $test = (bool) $test;
                    $this->test = ($test ? 'OUI' : 'NON');
                }
                $this->historic[] = 'test';
                return (true);
        }
        return (false);
    }
}
