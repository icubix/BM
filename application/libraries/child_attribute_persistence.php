<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Child_Attribute_Persistence extends Domain_Persistence {
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
                return 'Invalid Date of Birth format in Children Information. Use YYYY-MM-DD';
            }
        } else {
            $date_of_birth = NULL;
        }
        #we need a child name to make the person to enter stuff
        if (!$this->counterpart || !$this->counterpart->getField('child')
                || !$this->counterpart->getField('child')->getField('first_name')
                || $this->counterpart->getField('child')->getField('first_name') == ''
                || !$this->counterpart->getField('child')->getField('last_name')
                || $this->counterpart->getField('child')->getField('last_name') == '') {
            return 'Child first and last name required.';
        }
        #we need and employer name if there is a employer phone number
        if ($this->counterpart->getField('employer')) {
            if (!$this->counterpart->getField('employer')->isEmpty()
                    && (!$this->counterpart->getField('employer')->getField('organization_name')
                    || $this->counterpart->getField('employer')->getField('organization_name') == '')) {
                return 'Employer name required if phone is entered in Children.';
            }
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT "ChildAttribute".attribute_id '
                    . 'FROM "DefendantAttribute" INNER JOIN "ChildAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "ChildAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
            if ($query->num_rows() > 0) {
                $attribute_id = $query->row()->attribute_id;
            } else {
                $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                        . "WHERE description = 'Child';");
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
                . 'FROM "ChildAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $child_persistence = new Child_Persistence(
                        $this->counterpart->getField('child'));
            $error = $child_persistence->saveToDatabase($database,
                    $query->row()->reference_id);
            $this->counterpart->setField('child',
                    $child_persistence->getCounterpart());
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
                $database->query('UPDATE "ChildAttribute" SET '
                        . 'employer_id = ? WHERE attribute_id = ?;',
                        array($employer->getField('party_role_id'),
                        $attribute_id));
            }
            $database->query('UPDATE "ChildAttribute" SET '
                    . '(date_of_birth) '
                    . '= (?) WHERE attribute_id = ?;',
                    array(
                            $date_of_birth,
                            $attribute_id
                    ));
        } else {
            $child_persistence = new Child_Persistence(
                        $this->counterpart->getField('child'));
            $error = $child_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('child',
                    $child_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $database->query('INSERT INTO "ChildAttribute" '
                    . '(attribute_id, reference_id, '
                    . 'date_of_birth) '
                    . 'VALUES (?, ?, ?);',
                    array(
                            $attribute_id,
                            $child_persistence->getCounterpart()
                                    ->getField('party_role_id'),
                            $date_of_birth
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
                $database->query('UPDATE "ChildAttribute" SET '
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
            $query = $database->query('SELECT "ChildAttribute".attribute_id, '
                    . 'date_of_birth, reference_id, '
                    . 'employer_id FROM "ChildAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "ChildAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, date_of_birth, '
                    . 'reference_id, employer_id FROM "ChildAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            $this->counterpart->setField('attribute_id', $query->row()->attribute_id);
            $this->counterpart->setField('date_of_birth',
                    $query->row()->date_of_birth);
            $reference_id = $query->row()->reference_id;
            $employer_id = $query->row()->employer_id;
            #child child
            $child = $this->counterpart->getField('child');
            $child_persistence = new Child_Persistence($child);
            $child_persistence->getFromDatabase($database, $reference_id);
            $this->counterpart->setField('child',
                    $child_persistence->getCounterpart());
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
                . 'INNER JOIN "ChildAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "ChildAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $child_attributes = array();
        foreach ($query->result() as $row) {
            $new_child_attribute = new Child_Attribute();
            $new_child_attribute->setField('attribute_id',
                    $row->attribute_id);
            $new_child_attribute_persistenace
                    = new Child_Attribute_Persistence($new_child_attribute);
            $new_child_attribute_persistenace->getFromDatabase($database, $party_role_id);
            $child_attributes[count($child_attributes)]
                    = $new_child_attribute_persistenace->getCounterpart();
        }
        #if there are no reference attributes in the database return a blank one
        if (count($child_attributes) == 0) {
            $child_attributes[0] = new Child_Attribute();
        }
        return $child_attributes;
    }
}

/* End of file child_attribute_persistence.php */
/* Location: ./application/libraries/child_attribute_persistence.php */