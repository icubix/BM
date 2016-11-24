<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School_Persistence extends Organization_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $query = $database->query('SELECT party_role_type_id '
                . 'FROM "PartyRoleType" WHERE description '
                . "= 'Employer';");
        $this->counterpart->setField('party_role_type_id',
                $query->result()[0]->party_role_type_id);
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $party_role_id = $this->counterpart->getField('party_role_id');
        $query = $database->query('SELECT * from "Employer" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        if (count($query->result()) < 1) {
            $database->query('INSERT INTO "Employer" (party_role_id) '
                    . 'VALUES (?);', array($party_role_id));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        parent::getFromDatabase($database, $party_role_id);
    }
}

/* End of file school_persistence.php */
/* Location: ./application/libraries/school_persistence.php */