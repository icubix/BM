<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Child_Persistence extends Person_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $party_role_desc = $this->counterpart->getField('party_role_desc');
        if (!$party_role_desc || $party_role_desc == '') {
            $this->counterpart->setField('party_role_desc', 'Child');
        }
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $party_role_id = $this->counterpart->getField('party_role_id');
        $query = $database->query('SELECT * from "Reference" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        if (count($query->result()) < 1) {
            $database->query('INSERT INTO "Reference" (party_role_id) '
                    . 'VALUES (?);', array($party_role_id));
        }
        return NULL;
    }
}

/* End of file child_persistence.php */
/* Location: ./application/libraries/child_persistence.php */