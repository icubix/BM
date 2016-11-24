<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organization_Persistence extends Party_Role_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        
         
        if (!$this->counterpart || $this->counterpart->isEmpty()) {

            return NULL;
        }
        if ($this->counterpart->getField('party_type_id') == 0) {

            $query = $database->query('SELECT party_type_id FROM "PartyType" '
                    . "WHERE Description = 'Organization';");
            $this->counterpart->setField('party_type_id',
                    $query->row()->party_type_id);

            //print_r($query->row()->party_type_id);
        }
        echo "adsfasdf";
        $error = parent::saveToDatabase($database, $party_role_id);
        print_r($error);
        

        if ($error) {
            return $error;
        }
        $query = $database->query('SELECT organization_id FROM "Organization" '
                . 'WHERE organization_id = ?;',
                array($this->counterpart->getField('party_id')));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "Organization" SET '
                    . 'organization_name = ? '
                    . 'WHERE organization_id = ?;', array(
                            $this->counterpart->getField('organization_name'),
                            $this->counterpart->getField('party_id')
                    ));
        } else {
            echo "cal";
            #incase this Organization was previously a Person
            $database->query('DELETE FROM "Person" WHERE person_id = ?;',
                    array($this->counterpart->getField('party_id')));
            $database->query('INSERT INTO "Organization" '
                    . '(organization_id, organization_name) VALUES (?, ?);',
                    array(
                            $this->counterpart->getField('party_id'),
                            $this->counterpart->getField('organization_name')
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        parent::getFromDatabase($database, $party_role_id);
        $query = $database->query('SELECT organization_name FROM "Organization" '
                . 'WHERE organization_id = ?;',
                array($this->counterpart->getField('party_id')));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('organization_name',
                    $query->row()->organization_name);
        }
    }

    public function getAllOrganizations($database)
    {
        $query = $database->query('SELECT organization_id,organization_name FROM "Organization"');
        if($query->num_rows() > 0)
        {
            // print_r($query->result_array());
            // exit();
            return $query->result_array();
        }
    }
}

/* End of file organization_persistence.php */
/* Location: ./application/libraries/organization_persistence.php */