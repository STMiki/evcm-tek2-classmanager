<?php

require_once('log.php');

final Class Prestation extends DatabaseObject {
    protected $id_presta = null;
    protected $titre_presta = null;
    protected $contenu_presta = null;
    protected $tarif = null;

    public function __construct(array $data)
    {
        if (!isset($data['id_presta'])) {
            printLog(__METHOD__, 'Creating a new Prestation without primary key', true);
            throw new PrestationException('Creating a new Prestation without primary key');
        }
        $this->hydrate($data);
        $this->last_id = $data['id_presta'];
    }

    public function __destruct()
    {
        $this->id_presta = null;
        $this->titre_presta = null;
        $this->contenu_presta = null;
        $this->tarif = null;
    }

    public function toKey(string $value)
    {
        $data = explode('_', $value);
        $result = '';
        foreach ($data as $word) {
            $result .= ucFirst($word);
        }
        return ($result);
    }

    public function getIdPresta()      {return ($this->id_presta);}
    public function getTitrePresta()   {return ($this->titre_presta);}
    public function getContenuPresta() {return ($this->contenu_presta);}
    public function getTarif()         {return ($this->tarif);}

    public function setIdPresta(string $id)
    {
        $id = (string) $id;
        if (strlen($id) <= 100 && preg_match('/^[0-9]{3}(\-[0-9]{3}){0,}$/', $id)) {
            $this->id_presta = $id;
            $this->historic[] = 'id_presta';
            return (true);
        }
        return (false);
    }

    public function setTitrePresta($titre)
    {
        if ($titre !== null)
            $titre = (string) $titre;
        if ($titre === null || (is_string($titre) && strlen($titre) <= 130)) {
            $this->titre_presta = $titre;
            $this->historic[] = 'titre_presta';
            return (true);
        }
        return (false);
    }

    public function setContenuPresta($contenu)
    {
        if ($contenu !== null)
            $contenu = (string) $contenu;
        if ($contenu === null || (is_string($contenu) && strlen($contenu) <= 130)) {
            $this->titre_presta = $contenu;
            $this->historic[] = 'contenu_presta';
            return (true);
        }
        return (false);
    }

    public function setTarif(int $tarif)
    {
        $tarif = (int) $tarif;
        if ($tarif >= 0) {
            $this->tarif = $tarif;
            $this->historic[] = 'tarif';
            return (true);
        }
        return (false);
    }
}
