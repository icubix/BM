<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surety_Persistence extends Organization_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $party_role_desc = $this->counterpart->getField('party_role_desc');
        if (!$party_role_desc || $party_role_desc == '') {
            $this->counterpart->setField('party_role_desc', 'Surety');
        }
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $party_role_id = $this->counterpart->getField('party_role_id');
        $query = $database->query('SELECT * from "Surety" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        if (count($query->result()) < 1) {
            $database->query('INSERT INTO "Surety" (party_role_id) '
                    . 'VALUES (?);', array($party_role_id));
        }
        return NULL;
    }
}

/* End of file surety_persistence.php */
/* Location: ./application/libraries/surety_persistence.php */