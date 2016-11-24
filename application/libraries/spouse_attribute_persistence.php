<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spouse_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $date_of_birth = $this->counterpart->getField('date_of_birth');
        if ($date_of_birth) {
            $date_of_birth = strtotime($date_of_birth);
            if ($date_of_birth) {
                $date_of_birth = date('Y-m-d', $date_of_birth);
            } else {
                return 'Invalid Date of Birth format in Spouse Information. Use YYYY-MM-DD';
            }
        } else {
            $date_of_birth = NULL;
        }
        #we need a spouse name to make the person to enter stuff
        if (!$this->counterpart || !$this->counterpart->getField('spouse')
                || !$this->counterpart->getField('spouse')->getField('first_name')
                || $this->counterpart->getField('spouse')->getField('first_name') == ''
                || !$this->counterpart->getField('spouse')->getField('last_name')
                || $this->counterpart->getField('spouse')->getField('last_name') == '') {
            return 'Spouse first and last name required.';
        }
        #we need and employer name if there is a employer phone number
        if ($this->counterpart->getField('employer')) {
            if (!$this->counterpart->getField('employer')->isEmpty()
                    && (!$this->counterpart->getField('employer')->getField('organization_name')
                    || $this->counterpart->getField('employer')->getField('organization_name') == '')) {
                return 'Employer name required if phone is entered in Spouse.';
            }
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT "SpouseAttribute".attribute_id '
                    . 'FROM "DefendantAttribute" INNER JOIN "SpouseAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "SpouseAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
            if ($query->num_rows() > 0) {
                $attribute_id = $query->row()->attribute_id;
            } else {
                $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                        . "WHERE description = 'Spouse';");
                $database->query('INSERT INTO "Attribute" '
                        . '(attribute_id, attribute_type_id) '
                        . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                        array($query->row()->attribute_type_id));
                $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
                $database->query('INSERT INTO "DefendantAttribute" '
                        . '(attribute_id, party_role_id, from_date) '
                        . 'VALUES (?, ?, DEFAULT)',
                        array($attribute_id, $party_role_id));
            }
            $this->counterpart->setField('attribute_id', $attribute_id);
        }
        if ($party_role_id == 0) { #this should not happen here
            $query = $database->query('SELECT party_role_id FROM "DefendantAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $query = $database->query('SELECT reference_id, employer_id '
                . 'FROM "SpouseAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $spouse_persistence = new Spouse_Persistence(
                        $this->counterpart->getField('spouse'));
            $error = $spouse_persistence->saveToDatabase($database,
                    $query->row()->reference_id);
            $this->counterpart->setField('spouse',
                    $spouse_persistence->getCounterpart());
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
                $database->query('UPDATE "SpouseAttribute" SET '
                        . 'employer_id = ? WHERE attribute_id = ?;',
                        array($employer->getField('party_role_id'),
                        $attribute_id));
            }
            $database->query('UPDATE "SpouseAttribute" SET '
                    . '(date_of_birth, maiden_name, occupation, shift, '
                    . 'time_with_employer, job_title) '
                    . '= (?, ?, ?, ?, ?, ?) WHERE attribute_id = ?;',
                    array(
                            $date_of_birth,
                            $this->counterpart->getField('maiden_name'),
                            $this->counterpart->getField('occupation'),
                            $this->counterpart->getField('shift'),
                            $this->counterpart->getField('time_with_employer'),
                            $this->counterpart->getField('job_title'),
                            $attribute_id
                    ));
        } else {
            $spouse_persistence = new Spouse_Persistence(
                        $this->counterpart->getField('spouse'));
            $error = $spouse_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('spouse',
                    $spouse_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $database->query('INSERT INTO "SpouseAttribute" '
                    . '(attribute_id, reference_id, '
                    . 'date_of_birth, maiden_name, occupation, shift, '
                    . 'time_with_employer, job_title) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $spouse_persistence->getCounterpart()
                                    ->getField('party_role_id'),
                            $date_of_birth,
                            $this->counterpart->getField('maiden_name'),
                            $this->counterpart->getField('occupation'),
                            $this->counterpart->getField('shift'),
                            $this->counterpart->getField('time_with_employer'),
                            $this->counterpart->getField('job_title')
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
                $database->query('UPDATE "SpouseAttribute" SET '
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
            $query = $database->query('SELECT "SpouseAttribute".attribute_id, '
                    . 'date_of_birth, maiden_name, occupation, shift, '
                    . 'time_with_employer, job_title, reference_id, '
                    . 'employer_id FROM "SpouseAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "SpouseAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, '
                    . 'date_of_birth, maiden_name, occupation, shift, '
                    . 'time_with_employer, job_title, '
                    . 'reference_id, employer_id FROM "SpouseAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            $this->counterpart->setField('attribute_id', $query->row()->attribute_id);
            $this->counterpart->setField('date_of_birth',
                    $query->row()->date_of_birth);
            $this->counterpart->setField('maiden_name', $query->row()->maiden_name);
            $this->counterpart->setField('occupation', $query->row()->occupation);
            $this->counterpart->setField('shift', $query->row()->shift);
            $this->counterpart->setField('time_with_employer',
                    $query->row()->time_with_employer);
            $this->counterpart->setField('job_title', $query->row()->job_title);
            $reference_id = $query->row()->reference_id;
            $employer_id = $query->row()->employer_id;
            #spouse child
            $spouse = $this->counterpart->getField('spouse');
            $spouse_persistence = new Spouse_Persistence($spouse);
            $spouse_persistence->getFromDatabase($database, $reference_id);
            $this->counterpart->setField('spouse',
                    $spouse_persistence->getCounterpart());
            #employer child
            $employer = $this->counterpart->getField('employer');
            $employer_persistence = new Employer_Persistence($employer);
            $employer_persistence->getFromDatabase($database, $employer_id);
            $this->counterpart->setField('employer',
                    $employer_persistence->getCounterpart());
        }
    }
}

/* End of file spouse_attribute_persistence.php */
/* Location: ./application/libraries/spouse_attribute_persistence.php */