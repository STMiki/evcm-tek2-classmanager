<?php

final class Client extends DatabaseObject {
    protected $id_cl = null;
    protected $prenom_cl = null;
    protected $nom_cl = null;
    protected $entreprise_cl = null;
    protected $mail_cl = null;
    protected $adresse_cl = null;
    protected $cp_cl = null;
    protected $ville_cl = null;
    protected $tel_cl = null;
    protected $siret_cl = null;
    protected $note_gen_cl = null;
    protected $note_exigence_cl = null;
    protected $type_abo_cl = null;
    protected $date_debut_abo_cl = null;
    protected $idclientzoho = null;
    protected $test = null;

    public function __construct(array $data)
    {
        if (!isset($data['id_cl'])) {
            printLog(__METHOD__, 'Creating a new Client without primary key', true);
            throw new ClientException('Creating a new Client without primary key');
        }
        $this->hydrate($data);
        $this->id_cl = $data['id_cl'];
        $this->last_id = $data['id_cl'];
    }

    public function __destruct()
    {
        $this->id_cl = null;
        $this->prenom_cl = null;
        $this->nom_cl = null;
        $this->entreprise_cl = null;
        $this->mail_cl = null;
        $this->adresse_cl = null;
        $this->cp_cl = null;
        $this->ville_cl = null;
        $this->tel_cl = null;
        $this->siret_cl = null;
        $this->note_gen_cl = null;
        $this->note_exigence_cl = null;
        $this->type_abo_cl = null;
        $this->date_debut_abo_cl = null;
        $this->idclientzoho = null;
        $this->test = null;
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

    public function setEntreprise($entreprise)
    {
        if ($entreprise  !== null)
            $entreprise = (string) $entreprise;
        if ($entreprise === null || (is_string($entreprise) && strlen($entreprise) <= 50)) {
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

    public function setAdresse($adresse)
    {
        if ($adresse !== null)
            $adresse = (string) $adresse;
        if ($adresse === null || (is_string($adresse) && strlen($adresse) <= 100)) {
            $this->adresse_cl = $adresse;
            $this->historic[] = 'adresse_cl';
            return (true);
        }
        return (false);
    }

    public function setCp($cp)
    {
        if ($cp !== null)
            $cp = (string) $cp;
        if ($cp === null || (is_string($cp) && strlen($cp) <= 5)) {
            $this->cp_cl = $cp;
            $this->historic[] = 'cp_cl';
            return (true);
        }
        return (false);
    }

    public function setVille($ville)
    {
        if ($ville !== null)
            $ville = (string) $ville;
        if ($ville === null || (is_string($ville) && strlen($ville) <= 100)) {
            $this->ville_cl = $ville;
            $this->historic[] = 'ville_cl';
            return (true);
        }
        return (false);
    }

    public function setTel($tel)
    {
        if ($tel !== null)
            $tel = (string) $tel;
        if ($tel === null || (is_string($tel) && strlen($tel) <= 100 && preg_match('//', $tel))) {
            $this->tel_cl = $tel;
            $this->historic[] = 'tel_cl';
            return (true);
        }
        return (false);
    }

    public function setSiret($siret)
    {
        if ($siret !== null)
            $siret = (string) $siret;
        if ($siret === null || (is_string($siret) && strlen($siret) <= 100)) {
            $this->siret_cl = $siret;
            $this->historic[] = 'siret_cl';
            return (true);
        }
        return (false);
    }

    public function setNoteGen(float $note)
    {
        $note = (float) $note;
        if ($note >= 0) {
            $this->note_gen_cl = $note;
            $this->historic[] = 'note_gen_cl';
            return (true);
        }
        return (false);
    }

    public function setNoteExigence(float $note)
    {
        $note = (float) $note;
        if ($note >= 0) {
            $this->note_gen_cl = $note;
            $this->historic[] = 'note_gen_cl';
            return (true);
        }
        return (false);
    }

    public function setTypeAbo($type)
    {
        if ($type !== null)
            $type = (string) $type;
        if ($type === null || (is_string($type) && strlen($type) <= 25)) {
            $this->type_abo_cl = $type;
            $this->historic[] = 'type_abo_cl';
            return (true);
        }
        return (false);
    }

    public function setDateDebutAbo($start)
    {
        if ($start !== null)
            $start = (string) $start;
        if ($start === null || (is_string($start) && strlen($start) <= 20)) {
            $this->date_debut_abo_cl = $start;
            $this->historic[] = 'date_debut_abo_cl';
            return (true);
        }
        return (false);
    }

    public function setIdclientzoho($id)
    {
        if ($id !== null)
            $id = (string) $id;
        if ($id === null || (is_string($id) && strlen($id) <= 20)) {
            $this->idclientzoho = $id;
            $this->historic[] = 'idclientzoho';
            return (true);
        }
        return (false);
    }

    public function setTest($test)
    {
        if ($test !== null)
            $test = (string) $test;
        if ($test === null || (is_string($test) && strlen($test) <= 7)) {
            $this->test = $test;
            $this->historic[] = 'test';
            return (true);
        }
        return (false);
    }
}
