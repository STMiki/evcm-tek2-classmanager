<?php

class Client {
    private $id;
    private $mission = array();

    /* private $orm; *//* this is implemented in the ORMConstructor class */

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set'.ucFirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /* id_h */
    private function setId_h($value) {
        try {
            $value = (int) $value;
        } catch() {
            return;
        }
        $this->id = $value;
    }

    public function getId_h() {
        return ($this->id);
    }
}
