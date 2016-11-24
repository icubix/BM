<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party_Role_Persistence extends Party_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        #party_id should have been set by the parent before getting here
        #party_role_desc should have been set by child before getting here
        $party_role_desc = $this->counterpart->getField('party_role_desc');
        if ($party_role_desc && $party_role_desc != '') {
            $query = $database->query('SELECT party_role_type_id '
                    . 'FROM "PartyRoleType" WHERE description '
                    . "= ?;", array($party_role_desc));
            $this->counterpart->setField('party_role_type_id',
                    $query->row()->party_role_type_id);
        }
        if ($party_role_id == 0) {
            $query = $database->query('SELECT party_role_id FROM "PartyRole" '
                    . 'WHERE party_id = ? AND party_role_type_id = ?;',
                    array($this->counterpart->getField('party_id'),
                          $this->counterpart->getField('party_role_type_id')));
            if ($query->num_rows() > 0) {
                $party_role_id = $query->row()->party_role_id;
            } else {
                $database->query('INSERT INTO "PartyRole" (party_id, party_role_type_id, '
                        . 'from_date, party_role_id) VALUES (?, ?, DEFAULT, DEFAULT) '
                        . 'RETURNING party_role_id;',
                        array($this->counterpart->getField('party_id'),
                            $this->counterpart->getField('party_role_type_id')));
                $party_role_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            }
        } else {
            // print_r($this->counterpart->getField('party_role_type_id'));
            // exit(); 
            $database->query('UPDATE "PartyRole" SET party_role_type_id = ? '
                    . 'WHERE party_role_id = ?;',
                    array($this->counterpart->getField('party_role_type_id'), $party_role_id));
        }
        $this->counterpart->setField('party_role_id', $party_role_id);
        $telecommunication_number = $this->counterpart->getField('telecommunication_number');
        if ($telecommunication_number) {
            $query = $database->query('SELECT "PartyContactMechanism".contact_mechanism_id '
                    . 'FROM "TelecommunicationNumber" INNER JOIN "PartyContactMechanism" '
                    . 'ON telecom_no_id = "PartyContactMechanism".contact_mechanism_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
            #this foreach will cycle through items in database and delete ones that are not
            #  matches for items to be saved
            foreach($query->result() as $row) {
                $noMatchFound = TRUE;
                foreach($telecommunication_number as $innerRow) {
                    if ($row->contact_mechanism_id
                            == $innerRow->getField('contact_mechanism_id')) {
                        $noMatchFound = FALSE;
                        break;
                    }
                }
                if ($noMatchFound) {
                    $database->query('DELETE FROM "ContactMechanism" '
                            . 'WHERE contact_mechanism_id = ?;',
                            array($row->contact_mechanism_id));
                }
            }
            foreach ($telecommunication_number as $row) {
                $error = (new Telecommunication_Number_Persistence($row)
                        )->saveToDatabase($database, $party_role_id);
                if ($error) {
                    return $error;
                }
            }
        }
        $postal_address = $this->counterpart->getField('postal_address');
        if ($postal_address) {
            $error = (new Postal_Address_Persistence($postal_address))->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        parent::getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('party_role_id', $party_role_id);
        $query = $database->query('SELECT party_role_type_id from "PartyRole" '
                . 'WHERE party_role_id = ?', array($party_role_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('party_role_type_id',
                    $query->row()->party_role_type_id);
        }
        $query = $database->query('SELECT description from "PartyRoleType" '
                . 'WHERE party_role_type_id = ?',
                array($this->counterpart->getField('party_role_type_id')));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('party_role_desc',
                    $query->row()->description);
        }
        if ($this->counterpart->getField('telecommunication_number')) {
            $this->counterpart->setField('telecommunication_number',
                    Telecommunication_Number_Persistence::getFullListFromDatabase($database, $party_role_id));
        }
        if ($this->counterpart->getField('postal_address')) {
            $postal_address_persistence = new Postal_Address_Persistence(
                    $this->counterpart->getField('postal_address'));
            $postal_address_persistence->getFromDatabase($database, $party_role_id);
            $this->counterpart->setField('postal_address',
                    $postal_address_persistence->getCounterpart());
        }
    }
}

/* End of file party_persistence.php */
/* Location: ./application/libraries/party_persistence.php */