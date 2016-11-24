<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Electronic_Address_Persistence extends Domain_Persistence {
    public static function validateEmail($email) {
        $ampisthere = FALSE;
        $spacesthere = FALSE;

        $textbeforeamp = FALSE;
        $textafteramp = FALSE;
        $dotafteramp = FALSE;
        $othererror = FALSE;

        for($i = 0; $i < strlen($email); $i += 1) {
            if ($email[$i] == '@') {
                if($ampisthere) {
                    $othererror = TRUE;
                }
                $ampisthere = TRUE;
            } elseif (!$ampisthere) {
                $textbeforeamp = TRUE;
            } elseif ($email[$i] == '.') {
                    $dotafteramp = TRUE;
            } else {
                    $textafteramp = TRUE;
            }
            if($email[$i] == ' ' || $email[$i] == ',') {
                $spacesthere = TRUE;
            }
        }

        if($spacesthere || !$ampisthere || !$textafteramp
                || !$textbeforeamp || !$dotafteramp || $othererror) {
            return FALSE;
        } #else
        return TRUE;
    }

    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $electronic_address = $this->counterpart->getField('electronic_address');
        if (!$this->validateEmail($electronic_address)) {
            $query = $database->query('SELECT contact_mechanism_type_id as key '
                . 'FROM "ContactMechanismType" '
                . 'WHERE parent_contact_mechanism_type_id = 1200 '
                . "AND description = 'URL Address';");
            if ($query && $this->counterpart->getField('email_type') != $query->row()->key) {
                return 'Invalid email format.';
            }
        }
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $contact_mechanism_type_id = 0;
        if ($this->counterpart->getField('email_type')
                && is_numeric($this->counterpart->getField('email_type'))) {
            $contact_mechanism_type_id = $this->counterpart->getField('email_type');
        } else {
            $query = $database->query('SELECT contact_mechanism_type_id '
                    . 'FROM "ContactMechanismType" '
                    . "WHERE description = 'Electronic Address';");
            $contact_mechanism_type_id = $query->row()->contact_mechanism_type_id;
        }
        #check to see if email is in used for primary email addresses
        $query = $database->query('SELECT contact_mechanism_type_id FROM '
                . '"ContactMechanismType" where description = '
                . "'Primary Electronic Address';");
        #make sure type is still in use
        if ($query->num_rows() > 0) {
            #if it is a primary email address and not another type of email
            if ($contact_mechanism_type_id == $query->row()->contact_mechanism_type_id) {
                $query = $database->query('SELECT electronic_address_id FROM '
                        . '"ElectronicAddress" WHERE electronic_address = ?;'
                        , array($electronic_address));
                #for each email that matches
                foreach ($query->result_array() as $row) {
                    #if they are not the same contact_mechanism_id
                    if ($row['electronic_address_id'] != $contact_mechanism_id) {
                        return 'Email address in use: ' . htmlspecialchars($electronic_address);
                    }
                }
            }
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
                array($this->counterpart->getField('email_desc'),
                      $party_role_id, $contact_mechanism_id));
        $query = $database->query('SELECT electronic_address_id FROM "ElectronicAddress" '
                . 'WHERE electronic_address_id = ?;', array($contact_mechanism_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "ElectronicAddress" SET '
                    . 'electronic_address = ? WHERE electronic_address_id = ?;',
                    array(
                            $electronic_address,
                            $contact_mechanism_id
                    ));
        } else {
            $database->query('INSERT INTO "ElectronicAddress" '
                    . '(electronic_address_id, electronic_address) VALUES (?, ?);', array(
                            $contact_mechanism_id,
                            $electronic_address
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $query = NULL;
        if ($contact_mechanism_id == 0) {
            $query = $database->query('SELECT contact_mechanism_id, electronic_address '
                    . 'FROM "PartyContactMechanism" '
                    . 'INNER JOIN "ElectronicAddress" '
                    . 'ON "PartyContactMechanism".contact_mechanism_id '
                    . '= "ElectronicAddress".electronic_address_id '
                    . 'WHERE "PartyContactMechanism".party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT electronic_address_id as contact_mechanism_id, '
                    . 'electronic_address FROM "ElectronicAddress" '
                    . 'WHERE electronic_address_id = ?;', array($contact_mechanism_id));
        }
        if ($query && $query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
            $query = $database->query('SELECT contact_mechanism_type_id FROM "ContactMechanism" '
                    . 'WHERE contact_mechanism_id = ?;',
                    array($this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('email_type', $query->row()->contact_mechanism_type_id);
            $query = $database->query('SELECT purpose_other FROM "PartyContactMechanism" '
                    . 'WHERE party_role_id = ? AND contact_mechanism_id = ?;',
                    array($party_role_id, $this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('email_desc', $query->row()->purpose_other);
        }
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT contact_mechanism_id '
                . 'FROM "PartyContactMechanism" '
                . 'INNER JOIN "ElectronicAddress" '
                . 'ON "PartyContactMechanism".contact_mechanism_id '
                . '= "ElectronicAddress".electronic_address_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $electronic_addresses = array();
        foreach ($query->result() as $row) {
            $new_electronic_address = new Electronic_Address();
            $new_electronic_address->setField('contact_mechanism_id',
                    $row->contact_mechanism_id);
            $new_electronic_address_persistenace
                    = new Electronic_Address_Persistence($new_electronic_address);
                    $new_electronic_address_persistenace->getFromDatabase($database, $party_role_id);
            $electronic_addresses[count($electronic_addresses)]
                    = $new_electronic_address_persistenace->getCounterpart();
        }
        #if there are no electronic addresses in the database return a blank one
        if (count($electronic_addresses) == 0) {
            $electronic_addresses[0] = new Electronic_Address();
        }
        return $electronic_addresses;
    }
}

/* End of file electronic_address_persistence.php */
/* Location: ./application/libraries/electronic_address_persistence.php */