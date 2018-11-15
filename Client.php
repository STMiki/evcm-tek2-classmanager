<?php

require_once('log.php');

final class Client extends DatabaseObject {
    const __TYPE = __CLASS__;

    protected $id_cl = NULL;
    protected $prenom_cl = NULL;
    protected $nom_cl = NULL;
    protected $entreprise_cl = NULL;
    protected $mail_cl = NULL;
    protected $adresse_cl = NULL;
    protected $cp_cl = NULL;
    protected $ville_cl = NULL;
    protected $tel_cl = NULL;
    protected $siret_cl = NULL;
    protected $note_gen_cl = NULL;
    protected $note_exigence_cl = NULL;
    protected $type_abo_cl = NULL;
    protected $date_debut_abo_cl = NULL;
    protected $idclientzoho = NULL;
    protected $test = NULL;

    public function __construct(array $data)
    {
        if (!isset($data['id_cl'])) {
            printLog(__METHOD__, 'Creating a new Client without primary key', true);
            throw new ClientException('Creating a new Client without primary key');
        }
        $this->_hydrate($data);
        $this->last_id = $data['id_cl'];
    }

    public function __destruct()
    {
        $this->id_cl = NULL;
        $this->prenom_cl = NULL;
        $this->nom_cl = NULL;
        $this->entreprise_cl = NULL;
        $this->mail_cl = NULL;
        $this->adresse_cl = NULL;
        $this->cp_cl = NULL;
        $this->ville_cl = NULL;
        $this->tel_cl = NULL;
        $this->siret_cl = NULL;
        $this->note_gen_cl = NULL;
        $this->note_exigence_cl = NULL;
        $this->type_abo_cl = NULL;
        $this->date_debut_abo_cl = NULL;
        $this->idclientzoho = NULL;
        $this->test = NULL;
    }

    public function toKey(string $value)
    {
        $data = explode('_', $value);
        $result = '';
        foreach ($data as $word) {
            if ($word !== 'cl') {
                if ($word === 'm')
                    $result .= ucFirst('Mission');
                else if ($word === 'h')
                    $result .= ucFirst('Helper');
                else
                    $result .= ucFirst($word);
            }
        }
        return ($result);
    }

    public function transformForZoho()
    {
        $result = Array();

        $result['Layout'] = array('name' => 'EVCM', 'id' => '99037000000177017');

        $result['ID_client'] = $this->idclientzoho;
        $result['Email'] = $this->mail_cl;
        $result['type'] = ($this->entreprise_cl == NULL ? 'Particulier' : 'Pro');
        $result['soci_t'] = $this->entreprise_cl;
        $result['Mailing_Street'] = $this->adresse_cl;
        $result['Mailing_Zip'] = $this->cp_cl;
        $result['Mailing_City'] = $this->ville_cl;
        $result['First_Name'] = $this->prenom_cl;
        $result['Last_Name'] = $this->nom_cl;
        $result['Full_Name'] = $this->prenom_cl.' '.$this->nom_cl;
        $result['Phone'] = $this->tel_cl;
        $result['SIRET'] = $this->siret_cl;
        $result['Abonnement'] = $this->type_abo_cl;
        $result['D_but_d_abonnement'] = $this->date_debut_abo_cl;
        $result['TEST'] = $this->test;

        return ($result);
    }

    public function getId()           {return ($this->id_cl);}
    public function getPrenom()       {return ($this->prenom_cl);}
    public function getNom()          {return ($this->nom_cl);}
    public function getEntreprise()   {return ($this->entreprise_cl);}
    public function getMail()         {return ($this->mail_cl);}
    public function getAdresse()      {return ($this->adresse_cl);}
    public function getCp()           {return ($this->cp_cl);}
    public function getVille()        {return ($this->ville_cl);}
    public function getTel()          {return ($this->tel_cl);}
    public function getSiret()        {return ($this->siret_cl);}
    public function getNoteGen()      {return ($this->note_gen_cl);}
    public function getNoteExigence() {return ($this->note_exigence_cl);}
    public function getTypeAbo()      {return ($this->type_abo_cl);}
    public function getDateDebutAbo() {return ($this->date_debut_abo_cl);}
    public function getIdclientzoho() {return ($this->idclientzoho);}
    public function getTest()         {return ($this->test);}

    protected function setId(int $id)
    {
        $this->id_cl = (int) $id;
        $this->historic[] = 'id_cl';
        return (true);
    }

    public function setPrenom(string $prenom)
    {
        $prenom = (string) $prenom;
        if (strlen($prenom) <= 40) {
            $this->prenom_cl = $prenom;
            $this->historic[] = 'prenom_cl';
            return (true);
        }
        return (false);
    }

    public function setNom(string $nom)
    {
        $nom = (string) $nom;
        if (strlen($nom) <= 40) {
            $this->nom_cl = $nom;
            $this->historic[] = 'nom_cl';
            return (true);
        }
        return (false);
    }

    public function setEntreprise($entreprise) // string or NULL
    {
        if (empty($entreprise))
            $entreprise = NULL;
        if ($entreprise  !== NULL)
            $entreprise = (string) $entreprise;
        if ($entreprise === NULL || (is_string($entreprise) && strlen($entreprise) <= 50)) {
            $this->entreprise_cl = $entreprise;
            $this->historic[] = 'entreprise_cl';
            return (true);
        }
        return (false);
    }

    public function setMail(string $mail)
    {
        $mail = (string) $mail;
        if (strlen($mail) <= 50) {
            $this->mail_cl = $mail;
            $this->historic[] = 'mail_cl';
            return (true);
        }
        return (false);
    }

    public function setAdresse($adresse) // string or NULL
    {
        if (empty($adresse))
            $adresse = NULL;
        if ($adresse !== NULL)
            $adresse = (string) $adresse;
        if ($adresse === NULL || (is_string($adresse) && strlen($adresse) <= 100)) {
            $this->adresse_cl = $adresse;
            $this->historic[] = 'adresse_cl';
            return (true);
        }
        return (false);
    }

    public function setCp($cp) // string or NULL
    {
        if (empty($cp))
            $cp = NULL;
        if ($cp !== NULL)
            $cp = (string) $cp;
        if ($cp === NULL || (is_string($cp) && strlen($cp) <= 5)) {
            $this->cp_cl = $cp;
            $this->historic[] = 'cp_cl';
            return (true);
        }
        return (false);
    }

    public function setVille($ville) // string or NULL
    {
        if (empty($ville))
            $ville = NULL;
        if ($ville !== NULL)
            $ville = (string) $ville;
        if ($ville === NULL || (is_string($ville) && strlen($ville) <= 100)) {
            $this->ville_cl = $ville;
            $this->historic[] = 'ville_cl';
            return (true);
        }
        return (false);
    }

    public function setTel($tel) // string or int or NULL
    {
        if (empty($tel))
            $tel = NULL;
        if ($tel !== NULL)
            $tel = (string) $tel;
        if ($tel === NULL || (is_string($tel) && strlen($tel) <= 100 && preg_match('/^(((\(?\+([0-9]{2})|(00[0-9]{2}))\)?[ \-]{0,}0?[1-9])|(0[1-9][ \-]?))[ \-]{0,}([0-9]{2}[ \-]{0,}){4}$/', $tel))) {
            $this->tel_cl = $tel;
            $this->historic[] = 'tel_cl';
            return (true);
        }
        return (false);
    }

    public function setSiret($siret) // string or NULL
    {
        if (empty($siret))
            $siret = NULL;
        if ($siret !== NULL)
            $siret = (string) $siret;
        if ($siret === NULL || (is_string($siret) && strlen($siret) <= 100)) {
            $this->siret_cl = $siret;
            $this->historic[] = 'siret_cl';
            return (true);
        }
        return (false);
    }

    public function setNoteGen($note)
    {
        $note = floatval($note);
        if ($note >= 0.0 && $note <= 5.0) {
            $this->note_gen_cl = $note;
            $this->historic[] = 'note_gen_cl';
            return (true);
        }
        return (false);
    }

    public function setNoteExigence($note)
    {
        $note = floatval($note);
        if ($note >= 0 && $note <= 5) {
            $this->note_exigence_cl = $note;
            $this->historic[] = 'note_exigence_cl';
            return (true);
        }
        return (false);
    }

    public function setTypeAbo($type) // string or NULL
    {
        if (empty($type))
            $type = NULL;
        if ($type !== NULL)
            $type = (string) $type;
        if ($type === NULL || (is_string($type) && strlen($type) <= 25)) {
            $this->type_abo_cl = $type;
            $this->historic[] = 'type_abo_cl';
            return (true);
        }
        return (false);
    }

    public function setDateDebutAbo($start) // string or NULL
    {
        if (empty($start))
            $start = NULL;
        if ($start !== NULL)
            $start = (string) $start;
        if ($start === NULL || (is_string($start) && strlen($start) <= 20)) {
            $this->date_debut_abo_cl = $start;
            $this->historic[] = 'date_debut_abo_cl';
            return (true);
        }
        return (false);
    }

    public function setIdclientzoho($id) // string or NULL
    {
        if (empty($id))
            $id = NULL;
        if ($id !== NULL)
            $id = (string) $id;
        if ($id === NULL || (is_string($id) && strlen($id) <= 20)) {
            $this->idclientzoho = $id;
            $this->historic[] = 'idclientzoho';
            return (true);
        }
        return (false);
    }

    public function setTest($test) // string or NULL
    {
        if (empty($test))
            $test = NULL;
        if ($test == NULL || is_int($test) || is_bool($test) || is_string($test)) {
                if ($test == NULL)
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
