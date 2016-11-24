<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        #we need an employer name to make the orginization to enter stuff if the attrubute is not empty
        if (!$this->counterpart->getField('employer')
                || !$this->counterpart->getField('employer')->getField('organization_name')
                || $this->counterpart->getField('employer')->getField('organization_name') == '') {
            return 'Employer name required.';
        }
        #we need and union name if there is a union phone number
        if ($this->counterpart->getField('union')) {
            if (!$this->counterpart->getField('union')->isEmpty()
                    && (!$this->counterpart->getField('union')->getField('organization_name')
                    || $this->counterpart->getField('union')->getField('organization_name') == '')) {
                return 'Union name required if phone is entered.';
            }
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT "EmploymentAttribute".attribute_id '
                    . 'FROM "DefendantAttribute" INNER JOIN "EmploymentAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "EmploymentAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
            if ($query->num_rows() > 0) {
                $attribute_id = $query->row()->attribute_id;
            } else {
                $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                        . "WHERE description = 'Employment';");
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
        $query = $database->query('SELECT employer_id, supervisor_id, union_id '
                . 'FROM "EmploymentAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $employer_persistence = new Employer_Persistence(
                        $this->counterpart->getField('employer'));
            $error = $employer_persistence->saveToDatabase($database,
                    $query->row()->employer_id);
            $this->counterpart->setField('employer',
                    $employer_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $supervisor = $this->counterpart->getField('supervisor');
            if (!$supervisor->isEmpty()) {
                $supervisor_persistence = new Supervisor_Persistence(
                            $supervisor);
                $error = $supervisor_persistence->saveToDatabase($database,
                        $query->row()->supervisor_id);
                $supervisor = $supervisor_persistence->getCounterpart();
                $this->counterpart->setField('supervisor', $supervisor);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "EmploymentAttribute" SET '
                        . 'supervisor_id = ? WHERE attribute_id = ?;',
                        array($supervisor->getField('party_role_id'),
                        $attribute_id));
            }
            $union = $this->counterpart->getField('union');
            if (!$union->isEmpty()) {
                $union_persistence = new Union_Persistence(
                            $union);
                $error = $union_persistence->saveToDatabase($database,
                        $query->row()->union_id);
                $union = $union_persistence->getCounterpart();
                $this->counterpart->setField('union', $union);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "EmploymentAttribute" SET '
                        . 'union_id = ? WHERE attribute_id = ?;',
                        array($union->getField('party_role_id'),
                        $attribute_id));
            }
            $database->query('UPDATE "EmploymentAttribute" SET '
                    . '(occupation, time_with_employer, job_title, shift) '
                    . '= (?, ?, ?, ?) WHERE attribute_id = ?;',
                    array(
                            $this->counterpart->getField('occupation'),
                            $this->counterpart->getField('time_with_employer'),
                            $this->counterpart->getField('job_title'),
                            $this->counterpart->getField('shift'),
                            $attribute_id
                    ));
        } else {
            $employer_persistence = new Employer_Persistence(
                        $this->counterpart->getField('employer'));
            $error = $employer_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('employer',
                    $employer_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $database->query('INSERT INTO "EmploymentAttribute" '
                    . '(attribute_id, employer_id, '
                    . 'occupation, time_with_employer, job_title, shift) '
                    . 'VALUES (?, ?, ?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $employer_persistence->getCounterpart()
                                    ->getField('party_role_id'),
                            $this->counterpart->getField('occupation'),
                            $this->counterpart->getField('time_with_employer'),
                            $this->counterpart->getField('job_title'),
                            $this->counterpart->getField('shift')
                    ));
            $supervisor = $this->counterpart->getField('supervisor');
            if (!$supervisor->isEmpty()) {
                $supervisor_persistence = new Supervisor_Persistence(
                            $supervisor);
                $error = $supervisor_persistence->saveToDatabase($database, 0);
                $supervisor = $supervisor_persistence->getCounterpart();
                $this->counterpart->setField('supervisor', $supervisor);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "EmploymentAttribute" SET '
                        . 'supervisor_id = ? WHERE attribute_id = ?;',
                        array($supervisor->getField('party_role_id'),
                        $attribute_id));
            }
            $union = $this->counterpart->getField('union');
            if (!$union->isEmpty()) {
                $union_persistence = new Union_Persistence(
                            $union);
                $error = $union_persistence->saveToDatabase($database, 0);
                $union = $union_persistence->getCounterpart();
                $this->counterpart->setField('union', $union);
                if ($error) {
                    return $error;
                }
                $database->query('UPDATE "EmploymentAttribute" SET '
                        . 'union_id = ? WHERE attribute_id = ?;',
                        array($union->getField('party_role_id'),
                        $attribute_id));
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $attribute_id = $this->counterpart->getField('attribute_id');
        $query = NULL;
        if ($attribute_id == 0) {
            $query = $database->query('SELECT "EmploymentAttribute".attribute_id, '
                    . 'occupation, time_with_employer, job_title, shift, employer_id, '
                    . 'supervisor_id, union_id FROM "EmploymentAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "EmploymentAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, '
                    . 'occupation, time_with_employer, job_title, shift, '
                    . 'employer_id, supervisor_id, union_id FROM "EmploymentAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            $this->counterpart->setField('attribute_id', $query->row()->attribute_id);
            $this->counterpart->setField('occupation', $query->row()->occupation);
            $this->counterpart->setField('time_with_employer',
                    $query->row()->time_with_employer);
            $this->counterpart->setField('job_title', $query->row()->job_title);
            $this->counterpart->setField('shift', $query->row()->shift);
            $employer_id = $query->row()->employer_id;
            $supervisor_id = $query->row()->supervisor_id;
            $union_id = $query->row()->union_id;
            #employer child
            $employer = $this->counterpart->getField('employer');
            $employer_persistence = new Employer_Persistence($employer);
            $employer_persistence->getFromDatabase($database, $employer_id);
            $this->counterpart->setField('employer',
                    $employer_persistence->getCounterpart());
            #supervisor child
            $supervisor = $this->counterpart->getField('supervisor');
            $supervisor_persistence = new Supervisor_Persistence($supervisor);
            $supervisor_persistence->getFromDatabase($database, $supervisor_id);
            $this->counterpart->setField('supervisor',
                    $supervisor_persistence->getCounterpart());
            #union child
            $union = $this->counterpart->getField('union');
            $union_persistence = new Union_Persistence($union);
            $union_persistence->getFromDatabase($database, $union_id);
            $this->counterpart->setField('union',
                    $union_persistence->getCounterpart());
        }
    }
}

/* End of file employment_attribute_persistence.php */
/* Location: ./application/libraries/employment_attribute_persistence.php */