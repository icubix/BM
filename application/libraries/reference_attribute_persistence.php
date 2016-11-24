<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if (!$this->counterpart || !$this->counterpart->getField('reference')
                || !$this->counterpart->getField('reference')->getField('first_name')
                || $this->counterpart->getField('reference')->getField('first_name') == ''
                || !$this->counterpart->getField('reference')->getField('last_name')
                || $this->counterpart->getField('reference')->getField('last_name') == '') {
            return 'Reference first and last name required.';
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                    . "WHERE description = 'Reference';");
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
        $query = $database->query('SELECT reference_id, employer_id '
                . 'FROM "ReferenceAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $reference_persistence = new Reference_Persistence(
                        $this->counterpart->getField('reference'));
            $error = $reference_persistence->saveToDatabase($database,
                    $query->row()->reference_id);
            $this->counterpart->setField('reference',
                    $reference_persistence->getCounterpart());
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
                $database->query('UPDATE "ReferenceAttribute" SET '
                        . 'employer_id = ? WHERE attribute_id = ?;',
                        array($employer->getField('party_role_id'),
                        $attribute_id));
            }
        } else {
            $reference_persistence = new Reference_Persistence(
                        $this->counterpart->getField('reference'));
            $error = $reference_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('reference',
                    $reference_persistence->getCounterpart());
            if ($error) {
                return $error;
            }
            $database->query('INSERT INTO "ReferenceAttribute" '
                    . '(attribute_id, reference_id) '
                    . 'VALUES (?, ?);',
                    array(
                            $attribute_id,
                            $reference_persistence->getCounterpart()
                                    ->getField('party_role_id')
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
                $database->query('UPDATE "ReferenceAttribute" SET '
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
            $query = $database->query('SELECT "ReferenceAttribute".attribute_id, '
                    . 'reference_id, employer_id FROM "ReferenceAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "ReferenceAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, reference_id, employer_id '
                    . 'FROM "ReferenceAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            $this->counterpart->setField('attribute_id', $query->row()->attribute_id);
            $this->counterpart->setField('powers', $query->row()->powers);
            $reference_id = $query->row()->reference_id;
            $employer_id = $query->row()->employer_id;
            #reference child
            $reference = $this->counterpart->getField('reference');
            $reference_persistence = new Reference_Persistence($reference);
            $reference_persistence->getFromDatabase($database, $reference_id);
            $this->counterpart->setField('reference',
                    $reference_persistence->getCounterpart());
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
                . 'INNER JOIN "ReferenceAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "ReferenceAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $reference_attributes = array();
        foreach ($query->result() as $row) {
            $new_reference_attribute = new Reference_Attribute();
            $new_reference_attribute->setField('attribute_id',
                    $row->attribute_id);
            $new_reference_attribute_persistenace
                    = new Reference_Attribute_Persistence($new_reference_attribute);
            $new_reference_attribute_persistenace->getFromDatabase($database, $party_role_id);
            $reference_attributes[count($reference_attributes)]
                    = $new_reference_attribute_persistenace->getCounterpart();
        }
        #if there are no reference attributes in the database return a blank one
        if (count($reference_attributes) == 0) {
            $reference_attributes[0] = new Reference_Attribute();
        }
        return $reference_attributes;
    }
}

/* End of file reference_attribute_persistence.php */
/* Location: ./application/libraries/reference_attribute_persistence.php */