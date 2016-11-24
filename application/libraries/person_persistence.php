<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person_Persistence extends Party_Role_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if ($this->counterpart->getField('ssn') &&
                !ctype_digit(str_replace(array(' ', '_', '-', '/', '.', ','),
                        '', $this->counterpart->getField('ssn')))) {
            return 'SSN contains invalid characters.';
        }
        if ($this->counterpart->getField('party_type_id') == 0) {
            $query = $database->query('SELECT party_type_id FROM "PartyType" '
                    . "WHERE Description = 'Person';");
            $this->counterpart->setField('party_type_id',
                    $query->row()->party_type_id);
        }
        $error = parent::saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $party_id = $this->counterpart->getField('party_id');
        $query = $database->query('SELECT person_id FROM "Person" '
                . 'WHERE person_id = ?', array($party_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "Person" SET first_name = ?, middle_name = ?, '
                    . 'last_name = ?, suffix_name = ?, ssn = ? WHERE person_id = ?;',
                    array(
                        $this->counterpart->getField('first_name'),
                        $this->counterpart->getField('middle_name'),
                        $this->counterpart->getField('last_name'),
                        $this->counterpart->getField('suffix_name'),
                        $this->counterpart->getField('ssn'),
                        $party_id
                    )
                );
        } else {
            #incase this party_id was previously an organization
            $database->query('DELETE FROM "Organization" WHERE organization_id = ?;',
                    array($party_id));
            $database->query('INSERT INTO "Person" '
                . '(person_id, first_name, middle_name, last_name, suffix_name, ssn)'
                . 'VALUES (?, ?, ?, ?, ?, ?);',
                    array(
                        $party_id,
                        $this->counterpart->getField('first_name'),
                        $this->counterpart->getField('middle_name'),
                        $this->counterpart->getField('last_name'),
                        $this->counterpart->getField('suffix_name'),
                        $this->counterpart->getField('ssn')
                    )
                );
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        parent::getFromDatabase($database, $party_role_id);
        $query = $database->query('SELECT first_name, middle_name, last_name, '
                . 'suffix_name, ssn FROM "Person" WHERE person_id = ?;',
                array($this->counterpart->getField('party_id')));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array()[0] as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
    }
}

/* End of file person_persistence.php */
/* Location: ./application/libraries/person_persistence.php */