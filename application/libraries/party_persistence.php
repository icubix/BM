<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party_Persistence extends Domain_Persistence {

    public function saveToDatabase($database, $party_role_id = 0) {
        echo "jadsfasdf";
        if ($party_role_id == 0) {
            #party_type_id should have been set by child before getting here
            $party_type_id = $this->counterpart->getField('party_type_id');
            #party_id may be 0
            if ($this->counterpart->getField('party_id') == 0) {
                $database->query('INSERT INTO "Party" '
                        . '(party_id, party_type_id) '
                        . 'VALUES (DEFAULT, ?) RETURNING party_id',
                        array($party_type_id));
                $this->counterpart->setField('party_id', $database->query('SELECT LASTVAL()')->row()->lastval);
            }
        } else { #set party_id
            $query = $database->query('SELECT party_id,party_role_type_id from "PartyRole" '
                    . 'WHERE party_role_id = ?', array($party_role_id));
            $this->counterpart->setField('party_id', $query->row()->party_id);
             $this->counterpart->setField('party_role_type_id', $query->row()->party_role_type_id);
            #party_type_id should have been set by child before getting here
            $party_type_id = $this->counterpart->getField('party_type_id');
            if ($party_type_id != 0) {
                $database->query('UPDATE "Party" SET party_type_id = ? '
                        . 'WHERE party_id = ?;', array(
                                $party_type_id,
                                $query->row()->party_id)
                        );
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT party_id from "PartyRole" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('party_id', $query->row()->party_id);
            $query = $database->query('SELECT party_type_id from "Party" '
                    . 'WHERE party_id = ?;', array($query->row()->party_id));
            $this->counterpart->setField('party_type_id', $query->row()->party_type_id);
        }
    }
}

/* End of file party_role_persistence.php */
/* Location: ./application/libraries/party_role_persistence.php */