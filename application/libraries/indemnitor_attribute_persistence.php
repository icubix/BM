<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indemnitor_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if (!$this->counterpart || !$this->counterpart->getField('indemnitor')
                || !$this->counterpart->getField('indemnitor')->getField('first_name')
                || $this->counterpart->getField('indemnitor')->getField('first_name') == ''
                || !$this->counterpart->getField('indemnitor')->getField('last_name')
                || $this->counterpart->getField('indemnitor')->getField('last_name') == '') {
            return 'Indemnitor first and last name required.';
        }
        $date_of_birth = $this->counterpart->getField('date_of_birth');
        if ($date_of_birth) {
            $date_of_birth = strtotime($date_of_birth);
            if ($date_of_birth) {
                $date_of_birth = date('Y-m-d', $date_of_birth);
            } else {
                return 'Invalid Date of Birth format in Indemnitor Information. Use YYYY-MM-DD';
            }
        } else {
            $date_of_birth = NULL;
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                    . "WHERE description = 'Indemnitor';");
            $database->query('INSERT INTO "Attribute" '
                    . '(attribute_id, attribute_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                    array($query->row()->attribute_type_id));
            $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "DefendantAttribute" '
                    . '(attribute_id, party_role_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT)',
                    array($attribute_id, $party_role_id));
            $this->counterpart->setField('attribute_id', $attribute_id);
        }
        if ($party_role_id == 0) { #this should not happen here
            $query = $database->query('SELECT party_role_id FROM "DefendantAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $query = $database->query('SELECT indemnitor_id, employer_id '
                . 'FROM "IndemnitorAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $indemnitor_persistence = new Indemnitor_Persistence(
                        $this->counterpart->getField('indemnitor'));
            $error = $indemnitor_persistence->saveToDatabase($database,
                    $query->row()->indemnitor_id);
            $this->counterpart->setField('indemnitor',
                    $indemnitor_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $employer = $this->counterpart->getField('employer');
            if (!$employer->isEmpty()) {
                $employer_persistence = new Employer_Persistence(
                            $employer);
                $error = $employer_persistence->saveToDatabase($database,
                        $query->row()->employer_id);
                $employer = $employer_persistence->getCounterpart();
                $this->counterpart->setField('employer', $employer);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "IndemnitorAttribute" SET '
                        . 'employer_id = ? WHERE attribute_id = ?;',
                        array($employer->getField('party_role_id'),
                        $attribute_id));
            }
            $database->query('UPDATE "IndemnitorAttribute" SET '
                    . '(date_of_birth, driver_license_no, driver_license_state_id) '
                    . '= (?, ?, ?) WHERE attribute_id = ?;',
                    array(
                            $date_of_birth,
                            $this->counterpart->getField('driver_license_no'),
                            $this->counterpart->getField('driver_license_state_id'),
                            $attribute_id
                    ));
        } else {
            $indemnitor_persistence = new Indemnitor_Persistence(
                        $this->counterpart->getField('indemnitor'));
            $error = $indemnitor_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('indemnitor',
                    $indemnitor_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $database->query('INSERT INTO "IndemnitorAttribute" '
                    . '(attribute_id, indemnitor_id, '
                    . 'date_of_birth, driver_license_no, driver_license_state_id) '
                    . 'VALUES (?, ?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $indemnitor_persistence->getCounterpart()
                                    ->getField('party_role_id'),
                            $date_of_birth,
                            $this->counterpart->getField('driver_license_no'),
                            $this->counterpart->getField('driver_license_state_id')
                    ));
            $employer = $this->counterpart->getField('employer');
            if (!$employer->isEmpty()) {
                $employer_persistence = new Employer_Persistence(
                            $employer);
                $error = $employer_persistence->saveToDatabase($database, 0);
                $employer = $employer_persistence->getCounterpart();
                $this->counterpart->setField('employer', $employer);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "IndemnitorAttribute" SET '
                        . 'employer_id = ? WHERE attribute_id = ?;',
                        array($employer->getField('party_role_id'),
                        $attribute_id));
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $attribute_id = $this->counterpart->getField('attribute_id');
        $query = NULL;
        if ($attribute_id == 0) {
            $query = $database->query('SELECT "IndemnitorAttribute".attribute_id, '
                    . 'date_of_birth, driver_license_no, driver_license_state_id, '
                    . 'indemnitor_id, employer_id FROM "IndemnitorAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "IndemnitorAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, '
                    . 'date_of_birth, driver_license_no, driver_license_state_id, '
                    . 'indemnitor_id, employer_id FROM "IndemnitorAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            $this->counterpart->setField('attribute_id', $query->row()->attribute_id);
            $this->counterpart->setField('date_of_birth', $query->row()->date_of_birth);
            $this->counterpart->setField('driver_license_no', $query->row()->driver_license_no);
            $this->counterpart->setField('driver_license_state_id', $query->row()->driver_license_state_id);
            $indemnitor_id = $query->row()->indemnitor_id;
            $employer_id = $query->row()->employer_id;
            #indemnitor child
            $indemnitor = $this->counterpart->getField('indemnitor');
            $indemnitor_persistence = new Indemnitor_Persistence($indemnitor);
            $indemnitor_persistence->getFromDatabase($database, $indemnitor_id);
            $this->counterpart->setField('indemnitor',
                    $indemnitor_persistence->getCounterpart());
            #employer child
            $employer = $this->counterpart->getField('employer');
            $employer_persistence = new Employer_Persistence($employer);
            $employer_persistence->getFromDatabase($database, $employer_id);
            $this->counterpart->setField('employer',
                    $employer_persistence->getCounterpart());
        }
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" '
                . 'INNER JOIN "IndemnitorAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "IndemnitorAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $indemnitor_attributes = array();
        foreach ($query->result() as $row) {
            $new_indemnitor_attribute = new Indemnitor_Attribute();
            $new_indemnitor_attribute->setField('attribute_id',
                    $row->attribute_id);
            $new_indemnitor_attribute_persistenace
                    = new Indemnitor_Attribute_Persistence($new_indemnitor_attribute);
            $new_indemnitor_attribute_persistenace->getFromDatabase($database, $party_role_id);
            $indemnitor_attributes[count($indemnitor_attributes)]
                    = $new_indemnitor_attribute_persistenace->getCounterpart();
        }
        #if there are no indemnitor attributes in the database return a blank one
        if (count($indemnitor_attributes) == 0) {
            $indemnitor_attributes[0] = new Indemnitor_Attribute();
        }
        return $indemnitor_attributes;
    }
}

/* End of file indemnitor_attribute_persistence.php */
/* Location: ./application/libraries/indemnitor_attribute_persistence.php */