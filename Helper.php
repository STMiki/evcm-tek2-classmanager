<?php

require_once('log.php');

final class Helper extends DatabaseObject {
    const __TYPE = __CLASS__;

    protected $id_h = NULL;
    protected $prenom_h = NULL;
    protected $nom_h = NULL;
    protected $photo_h = NULL;
    protected $tel_h = NULL;
    protected $date_naissance_h = NULL;
    protected $mail_h = NULL;
    protected $adresse_h = NULL;
    protected $ville_h = NULL;
    protected $cp_h = NULL;
    protected $catego_h = NULL;
    protected $cv_h = NULL;
    protected $username_h = NULL;
    protected $pwd_h = NULL;
    protected $exp_h = NULL;
    protected $grade_h = NULL;
    protected $siret_h = NULL;
    protected $notegen_h = NULL;
    protected $note_savoir_faire_h = NULL;
    protected $note_savoir_etre_h = NULL;
    protected $description_h = NULL;
    protected $autre_infos_h = NULL;
    protected $lien_calendar_h = NULL;
    protected $idhelperzoho = NULL;
    protected $actif = NULL;
    protected $ambassadeur = NULL;
    protected $test = NULL;

    public function __construct(array $data)
    {
        if (!isset($data['id_h'])) {
            printLog(__METHOD__, 'Creating a new Helper without primary key', true);
            throw new HelperException('Creating a new Helper without primary key');
        }
        $this->_hydrate($data);
        $this->id_h = $data['id_h'];
        $this->last_id = $data['id_h'];
    }

    public function __destruct()
    {
        $id_h = NULL;
        $prenom_h = NULL;
        $nom_h = NULL;
        $photo_h = NULL;
        $tel_h = NULL;
        $date_naissance_h = NULL;
        $mail_h = NULL;
        $adresse_h = NULL;
        $ville_h = NULL;
        $cp_h = NULL;
        $catego_h = NULL;
        $cv_h = NULL;
        $username_h = NULL;
        $pwd_h = NULL;
        $exp_h = NULL;
        $grade_h = NULL;
        $siret_h = NULL;
        $notegen_h = NULL;
        $note_savoir_faire_h = NULL;
        $note_savoir_etre_h = NULL;
        $description_h = NULL;
        $autre_infos_h = NULL;
        $lien_calendar_h = NULL;
        $idhelperzoho = NULL;
        $actif = NULL;
        $ambassadeur = NULL;
    }

    public function toKey(string $value)
    {
        $data = explode('_', $value);
        $result = '';
        foreach ($data as $word) {
            if ($word !== 'h') {
                if ($word === 'cl')
                    $result .= ucFirst('Client');
                else if ($word === 'm')
                    $result .= ucFirst('Mission');
                else
                    $result .= ucFirst($word);
            }
        }
        return ($result);
    }

    public function transformForZoho()
    {
        $result = Array();

        $result['ID_Helper'] = $this->id_h;
        $result['Pr_nom'] = $this->prenom_h;
        $result['Name'] = $this->nom_h;
        $result['Quadri'] = createQuadri($this->prenom_h, $this->nom_h);
        $result['t_l_phone'] = $this->tel_h;
        $result['Email'] = $this->mail_h;
        $result['Adresse_rue'] = $this->adresse_h;
        $result['Adresse_CP'] = $this->cp_h;
        $result['Adresse_ville'] = $this->ville_h;
        $result['Profils'] = $this->catego_h;
        $result['SIREN'] = $this->siret_h;
        $result['Note_savoir_tre'] = $this->note_savoir_etre_h;
        $result['Note_technique'] = $this->note_savoir_faire_h;
        $result['Lien_agenda_Google'] = $this->lien_calendar_h;
        if ($this->actif)
            $result['Etat'] = 'Actif';
        $result['Embassadeur'] = ($this->ambassadeur ? 'OUI' : 'NON');
        $result['TEST'] = $this->test;

        return ($result);
    }


    public function getId()              {return ($this->id_h);}
    public function getPrenom()          {return ($this->prenom_h);}
    public function getNom()             {return ($this->nom_h);}
    public function getPhoto()           {return ($this->photo_h);}
    public function getTel()             {return ($this->tel_h);}
    public function getDateNaissance()   {return ($this->date_naissance_h);}
    public function getMail()            {return ($this->mail_h);}
    public function getAdresse()         {return ($this->adresse_h);}
    public function getVille()           {return ($this->ville_h);}
    public function getCp()              {return ($this->cp_h);}
    public function getCatego()          {return ($this->catego_h);}
    public function getCv()              {return ($this->cv_h);}
    public function getUsername()        {return ($this->username_h);}
    public function getPwd()             {return ($this->pwd_h);}
    public function getExp()             {return ($this->exp_h);}
    public function getGrade()           {return ($this->grade_h);}
    public function getSiret()           {return ($this->siret_h);}
    public function getNotegen()         {return ($this->notegen_h);}
    public function getNoteSavoirFaire() {return ($this->note_savoir_faire_h);}
    public function getNoteSavoirEtre()  {return ($this->note_savoir_etre_h);}
    public function getDescription()     {return ($this->description_h);}
    public function getAutreInfos()      {return ($this->autre_infos_h);}
    public function getLienCalendar()    {return ($this->lien_calendar_h);}
    public function getIdHelperZoho()    {return ($this->idhelperzoho);}
    public function getTest()            {return ($this->test);}
    public function getActif()           {return ($this->actif);}
    public function getAmbassadeur()     {return ($this->ambassadeur);}

    protected function setId($id)
    {
        return (true);
    }

    public function setPrenom(string $prenom)
    {
        $prenom = (string) $prenom;
        if (strlen($prenom) <= 50) {
            $this->prenom_h = $prenom;
            $this->historic[] = 'prenom_h';
            return (true);
        }
        return (false);
    }

    public function setNom(string $nom)
    {
        $nom = (string) $nom;
        if (strlen($nom) <= 50) {
            $this->nom_h = $nom;
            $this->historic[] = 'nom_h';
            return (true);
        }
        return (false);
    }

    public function setPhoto($photo) // string or NULL
    {
        if (empty($photo))
            $photo = NULL;
        if ($photo !== NULL)
            $photo = (string) $photo;
        if ($photo === NULL || (is_string($photo) && strlen($photo) <= 100)) {
            $this->photo_h = $photo;
            $this->historic[] = 'photo_h';
            return (true);
        }
        return (false);
    }

    public function setTel(string $tel)
    {
        $tel = (string) $tel;
        if ($tel === "" || (strlen($tel) <= 30 && preg_match('/^(((\(?\+([0-9]{2})|(00[0-9]{2}))\)?[ \-]{0,}0?[1-9])|(0[1-9][ \-]?))[ \-]{0,}([0-9]{2}[ \-]{0,}){4}$/', $tel))) {
            $this->tel_h = $tel;
            $this->historic[] = 'tel_h';
            return (true);
        }
        return (false);
    }

    public function setDateNaissance($date) // string or NULL
    {
        if (empty($date))
            $date = NULL;
        if ($date !== NULL)
            $date = (string) $date;
        if ($date === NULL || (is_string($date) && preg_match('/[0-9]{4}[\- ]((0[1-9])|(1[0-2]))[\- ]((0[1-9])|([12][0-9])|[3[01]])/', $date))) {
            $this->date_naissance_h = $date;
            $this->historic[] = 'date_naissance_h';
            return (true);
        }
        return (false);
    }

    public function setMail(string $mail)
    {
        $mail = (string) $mail;
        if ($mail === "" || (strlen($mail) <= 80 && preg_match('/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/', $mail))) {
            $this->mail_h = $mail;
            $this->historic[] = 'mail_h';
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
            $this->adresse_h = $adresse;
            $this->historic[] = 'adresse_h';
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
        if ($ville === NULL || (is_string($ville) && strlen($ville) <= 50)) {
            $this->ville_h = $ville;
            $this->historic[] = 'ville_h';
            return (true);
        }
        return (false);
    }

    public function setCp($codePostal) // string or NULL
    {
        if (empty($codePostal))
            $codePostal = NULL;
        if ($codePostal !== NULL)
            $codePostal = (string) $codePostal;
        if ($codePostal === NULL || (is_string($codePostal) && strlen($codePostal) <= 5)) {
            $this->cp_h = $codePostal;
            $this->historic[] = 'cp_h';
            return (true);
        }
        return (false);
    }

    public function setCatego(string $categorie)
    {
        $categorie = (string) $categorie;
        if (strlen($categorie) <= 25) {
            $this->catego_h = $categorie;
            $this->historic[] = 'catego_h';
            return (true);
        }
        return (false);
    }

    public function setCv($cv) // string or NULL
    {
        if (empty($cv))
            $cv = NULL;
        if ($cv !== NULL)
            $cv = (string) $cv;
        if ($cv === NULL || (is_string($cv) && strlen($cv) <= 100)) {
            $this->cv_h = $cv;
            $this->historic[] = 'cv_h';
            return (true);
        }
        return (false);
    }

    public function setUsername($username) // string or NULL
    {
        if (empty($username))
            $username = NULL;
        if ($username !== NULL)
            $username = (string) $username;
        if ($username === NULL || (is_string($username) && strlen($username) <= 25)) {
            $this->username_h = $username;
            $this->historic[] = 'username_h';
            return (true);
        }
        return (false);
    }

    public function setPwd(string $pwd)
    {
        $pwd = (string) $pwd;
        if ($pwd === NULL || (is_string($pwd) && strlen($pwd) <= 100)) {
            $this->pwd_h = $pwd;
            $this->historic[] = 'pwd_h';
            return (true);
        }
        return (false);
    }

    public function setExp(int $exp)
    {
        $exp = (int) $exp;
        if ($exp < 0)
            return (false);
        $this->exp_h = $exp;
        $this->historic[] = 'exp_h';
        return (true);
    }

    public function setGrade(string $grade)
    {
        $grade = (string) $grade;
        if (strlen($grade) <= 25) {
            $this->grade_h = $grade;
            $this->historic[] = 'grade_h';
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
        if ($siret === NULL || (is_string($siret) && strlen($siret) <= 14)) {
            $this->siret_h = $siret;
            $this->historic[] = 'siret_h';
            return (true);
        }
        return (false);
    }

    public function setNotegen($note) // float or NULL
    {
        if ($note !== NULL)
            $note = (float) $note;
        $this->notegen_h = $note;
        $this->historic[] = 'notegen_h';
        return (true);
    }

    public function setNoteSavoirFaire($note) // float or NULL
    {
        if ($note !== NULL)
            $note = (float) $note;
        $this->note_savoir_faire_h = $note;
        $this->historic[] = 'note_savoir_faire_h';
        return (true);
    }

    public function setNoteSavoirEtre($note) // float or NULL
    {
        if ($note !== NULL)
            $note = (float) $note;
        $this->note_savoir_etre_h = $note;
        $this->historic[] = 'note_savoir_etre_h';
        return (true);
    }

    public function setDescription($description) // string or NULL
    {
        if (empty($description))
            $description = NULL;
        if ($description !== NULL)
            $description = (string) $description;
        $this->description_h = $description;
        $this->historic[] = 'description_h';
        return (true);
    }

    public function setAutreInfos($info) // string or NULL
    {
        if (empty($info))
            $info = NULL;
        if ($info !== NULL)
            $info = (string) $info;
        $this->autre_infos_h = $info;
        $this->historic[] = 'autre_infos_h';
        return (true);
    }

    public function setLienCalendar($link) // string or NULL
    {
        if (empty($link))
            $link = NULL;
        if ($link !== NULL)
            $link = (string) $link;
        if ($link === NULL || (is_string($link) && strlen($link) <= 255)) {
            $this->lien_calendar_h = $link;
            $this->historic[] = 'lien_calendar_h';
            return (true);
        }
        return (false);
    }

    public function setIdhelperzoho($id) // int or NULL
    {
        if (empty($id))
            $id = NULL;
        if ($id !== NULL)
            $id = (int) $id;
        if ($id === NULL || $id >= 0) {
            $this->idhelperzoho = $id;
            $this->historic[] = 'idhelperzoho';
            return (true);
        }
        return (false);
    }

    public function setTest($test) // string or INTEGER or boolean (or null but null is transformed to 'NON')
    {
        if ($test == null || is_int($test) || is_bool($test) || is_string($test)) {
                if ($test == null)
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

    public function setActif(bool $actif)
    {
        $this->actif = (bool) $actif;
        $this->historic[] = 'actif';
        return (true);
    }

    public function setAmbassadeur(bool $ambassadeur)
    {
        $this->ambassadeur = (bool) $ambassadeur;
        $this->historic[] = 'ambassadeur';
        return (true);
    }
}
