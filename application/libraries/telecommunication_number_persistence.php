<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telecommunication_Number_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $error = $this->counterpart->verify();
        if ($error) {
            return $error;
        }
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $contact_mechanism_type_id = 0;
        if ($this->counterpart->getField('phone_type')
                && is_numeric($this->counterpart->getField('phone_type'))) {
            $contact_mechanism_type_id = $this->counterpart->getField('phone_type');
        } else {
            $query = $database->query('SELECT contact_mechanism_type_id '
                    . 'FROM "ContactMechanismType" '
                    . "WHERE description = 'Primary Number';");
            $contact_mechanism_type_id = $query->row()->contact_mechanism_type_id;
        }
        if ($contact_mechanism_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put phone number
            } #else
            $database->query('INSERT INTO "ContactMechanism" '
                    . '(contact_mechanism_id, contact_mechanism_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING contact_mechanism_id;',
                    array($contact_mechanism_type_id));
            $contact_mechanism_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "PartyContactMechanism" '
                    . '(party_role_id, contact_mechanism_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT)',
                    array($party_role_id, $contact_mechanism_id));
            $this->counterpart->setField('contact_mechanism_id', $contact_mechanism_id);
        }
        if ($party_role_id == 0) {
            $query = $database->query('SELECT party_role_id FROM "PartyContactMechanism" '
                    . 'WHERE contact_mechanism_id = ?;', array($contact_mechanism_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $database->query('UPDATE "ContactMechanism" SET '
                . 'contact_mechanism_type_id = ? '
                . 'WHERE contact_mechanism_id = ?;',
                array($contact_mechanism_type_id, $contact_mechanism_id));
        $database->query('UPDATE "PartyContactMechanism" SET '
                . 'purpose_other = ? WHERE party_role_id = ? '
                . 'AND contact_mechanism_id = ?;',
                array($this->counterpart->getField('phone_desc'),
                      $party_role_id, $contact_mechanism_id));
        $query = $database->query('SELECT telecom_no_id FROM "TelecommunicationNumber" '
                . 'WHERE telecom_no_id = ?;', array($contact_mechanism_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "TelecommunicationNumber" SET '
                    . 'contact_number = ? WHERE telecom_no_id = ?;',
                    array(
                            $this->counterpart->getField('contact_number'),
                            $contact_mechanism_id
                    ));
        } else {
            $database->query('INSERT INTO "TelecommunicationNumber" '
                    . '(telecom_no_id, contact_number) VALUES (?, ?);', array(
                            $contact_mechanism_id,
                            $this->counterpart->getField('contact_number')
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $query = NULL;
        if ($contact_mechanism_id == 0) {
            $query = $database->query('SELECT contact_mechanism_id, contact_number '
                    . 'FROM "PartyContactMechanism" '
                    . 'INNER JOIN "TelecommunicationNumber" '
                    . 'ON "PartyContactMechanism".contact_mechanism_id '
                    . '= "TelecommunicationNumber".telecom_no_id '
                    . 'WHERE "PartyContactMechanism".party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT telecom_no_id as contact_mechanism_id, '
                    . 'contact_number FROM "TelecommunicationNumber" '
                    . 'WHERE telecom_no_id = ?;', array($contact_mechanism_id));
        }
        if ($query && $query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
            $query = $database->query('SELECT contact_mechanism_type_id FROM "ContactMechanism" '
                    . 'WHERE contact_mechanism_id = ?;',
                    array($this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('phone_type', $query->row()->contact_mechanism_type_id);
            $query = $database->query('SELECT purpose_other FROM "PartyContactMechanism" '
                    . 'WHERE party_role_id = ? AND contact_mechanism_id = ?;',
                    array($party_role_id, $this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('phone_desc', $query->row()->purpose_other);
        }
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT contact_mechanism_id '
                . 'FROM "PartyContactMechanism" '
                . 'INNER JOIN "TelecommunicationNumber" '
                . 'ON "PartyContactMechanism".contact_mechanism_id '
                . '= "TelecommunicationNumber".telecom_no_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $telecommunication_numbers = array();
        foreach ($query->result() as $row) {
            $new_telecommunication_number = new Telecommunication_Number();
            $new_telecommunication_number->setField('contact_mechanism_id',
                    $row->contact_mechanism_id);
            $new_telecommunication_number_persistenace
                    = new Telecommunication_Number_Persistence($new_telecommunication_number);
            $new_telecommunication_number_persistenace->getFromDatabase($database, $party_role_id);
            $telecommunication_numbers[count($telecommunication_numbers)]
                    = $new_telecommunication_number_persistenace->getCounterpart();
        }
        #if there are no telecommunication numbers in the database return a blank one
        if (count($telecommunication_numbers) == 0) {
            $telecommunication_numbers[0] = new Telecommunication_Number();
        }
        return $telecommunication_numbers;
    }
}

/* End of file telecommunication_number_persistence.php */
/* Location: ./application/libraries/telecommunication_number_persistence.php */