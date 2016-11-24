<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if ($this->counterpart->getField('document_name') == '') {
            return 'Document name required';
        }
        if ($this->counterpart->getField('path') == '') {
            return 'Error reached: start over from Defendants.';
        }
        $query = $database->query('SELECT document_id FROM "Document" '
                . 'WHERE document_name = ? AND path = ?;',
                array($this->counterpart->getField('document_name'),
                        $this->counterpart->getField('path')));

        if ($query->num_rows() > 0) {
            $database->query('UPDATE "Document" SET '
                    . '(document_name, document_type, document_size, '
                    . 'uploaded_by, description, path, tags) '
                    . '= (?, ?, ?, ?, ?, ?, ?) '
                    . 'WHERE document_id = ?;',
                    array($this->counterpart->getField('document_name'),
                          $this->counterpart->getField('document_type'),
                          $this->counterpart->getField('document_size'),
                          $this->counterpart->getField('uploaded_by'),
                          $this->counterpart->getField('description'),
                          $this->counterpart->getField('path'),
                          $this->counterpart->getField('tags'),
                          $query->row()->document_id
                    ));
            $this->counterpart->setField('document_id', $query->row()->document_id);
        } else {
            $database->query('INSERT INTO "Document" '
                    . '(document_id, document_name, document_type, '
                    . 'document_size, uploaded_by, description, path, tags) '
                    . 'VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?) '
                    . 'RETURNING document_id;',
                    array($this->counterpart->getField('document_name'),
                          $this->counterpart->getField('document_type'),
                          $this->counterpart->getField('document_size'),
                          $this->counterpart->getField('uploaded_by'),
                          $this->counterpart->getField('description'),
                          $this->counterpart->getField('path'),
                          $this->counterpart->getField('tags')
                    ));
            $document_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $query = $database->query('SELECT attribute_type_id '
                    . 'FROM "AttributeType" '
                    . "WHERE description = 'Document';");
            $attribute_type_id = $query->row()->attribute_type_id;
            $database->query('INSERT INTO "Attribute" '
                    . '(attribute_id, attribute_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                    array($attribute_type_id));
            $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "DefendantAttribute" '
                    . '(attribute_id, party_role_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT)',
                    array($attribute_id, $party_role_id));
            $database->query('INSERT INTO "DocumentAttribute" '
                    . '(attribute_id, document_id) VALUES (?, ?);',
                    array($attribute_id, $document_id));
            $this->counterpart->setField('document_id', $document_id);
        }
        return NULL;
    }

    public function getFromDatabase($database, $document_id) {
        $query = $database->query('SELECT * FROM "Document" '
                . 'WHERE document_id = ?;', array($document_id));
        $result = $query->result_array();
        if (count($result) > 0) {
            foreach($result[0] as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
    }
}

/* End of file document_persistence.php */
/* Location: ./application/libraries/document_persistence.php */