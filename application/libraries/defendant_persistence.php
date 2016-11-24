<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Defendant_Persistence extends Person_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart
                || !$this->counterpart->getField('first_name')
                || !$this->counterpart->getField('last_name')) {
            return 'First and Last name required under personal information.';
        }
        $party_role_desc = $this->counterpart->getField('party_role_desc');
        if (!$party_role_desc || $party_role_desc == '') {
            $this->counterpart->setField('party_role_desc', 'Defendant');
        }
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $party_role_id = $this->counterpart->getField('party_role_id');
        $query = $database->query('SELECT * from "Defendant" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        if (count($query->result()) < 1) {
            $database->query('INSERT INTO "Defendant" (party_role_id, updated_time) '
                    . 'VALUES (?, DEFAULT);', array($party_role_id));
        } else { #update time it was updated since it exists
            $database->query('UPDATE "Defendant" SET (updated_time) = (DEFAULT) '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        }
        return NULL;
    }
}

/* End of file defendant_persistence.php */
/* Location: ./application/libraries/defendant_persistence.php */