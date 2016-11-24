<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Defendant_Model extends CI_Model {
    #clear left over data from previous add, edit, or view
    public function clearUserdata() {
        unset($_SESSION['defendantData']);
        unset($_SESSION['defendantSelectLists']);
    }

    public function getSelectLists() {
        $query = $this->db->query('SELECT state_id as key, geo_abbreviation as name '
                . 'FROM "GeographicBoundary" '
                . 'INNER JOIN "State" ON state_id = geo_id ORDER BY geo_abbreviation;');
        $stateList = $query->result_array();
        $query = $this->db->query('SELECT country_id as key, geo_name as name '
                . 'FROM "GeographicBoundary" '
                . 'INNER JOIN "Country" ON country_id = geo_id ORDER BY geo_name;');
        $countryList = $query->result_array();
        $yesnoList = array(array('key' => 0, 'name' => 'No'),
                            array('key' => 1, 'name' => 'Yes'));
        $rentorown = array(array('key' => 1, 'name' => 'Rent'),
                            array('key' => 0, 'name' => 'Own'));
        $query = $this->db->query('SELECT contact_mechanism_type_id as key, '
                . 'description as name '
                . 'FROM "ContactMechanismType" '
                . 'WHERE parent_contact_mechanism_type_id = 1000 '
                . 'ORDER BY contact_mechanism_type_id;');
        $postalList = $query->result_array();
        $query = $this->db->query('SELECT contact_mechanism_type_id as key, '
                . 'description as name '
                . 'FROM "ContactMechanismType" '
                . 'WHERE parent_contact_mechanism_type_id = 1100 '
                . 'ORDER BY contact_mechanism_type_id;');
        $telcomnoList = $query->result_array();
        $query = $this->db->query('SELECT contact_mechanism_type_id as key, '
                . 'description as name '
                . 'FROM "ContactMechanismType" '
                . 'WHERE parent_contact_mechanism_type_id = 1200 '
                . 'ORDER BY contact_mechanism_type_id;');
        $emailList = $query->result_array();
        $query = $this->db->query('SELECT description as key, '
                . 'description as name '
                . 'FROM "PartyRoleType" '
                . 'WHERE parent_role_type_id = 2003 '
                . 'ORDER BY parent_role_type_id;');
        $referenceTypeList = $query->result_array();
        $query = $this->db->query('SELECT description as key, '
                . 'description as name '
                . 'FROM "PartyType" '
                . 'ORDER BY party_type_id;');
        $partyTypeList = $query->result_array();
        $user_level = $this->session->userdata('user_level');
        if ($user_level == 100) {
            $query = $this->db->query('SELECT user_level as key, '
                    . "(user_level || ': ' || user_role || ': ' || description) as name "
                    . 'FROM "UserRoleType";');
        } else {
            $query = $this->db->query('SELECT user_level as key, '
                    . "(user_level || ': ' || user_role || ': ' || description) as name "
                    . 'FROM "UserRoleType" where user_level < ?;', array($user_level));
        }
        $userLevels = $query->result_array();
        $query = $this->db->query('SELECT user_level as key, '
                . "(user_level || ': ' || user_role || ': ' || description) as name "
                . 'FROM "UserRoleType" where user_level = ?;', array($user_level));
        $selfLevel = $query->result_array();
        $query = $this->db->query('SELECT attribute_type_id as key, '
                . 'description as name '
                . 'FROM "AttributeType" '
                . 'WHERE parent_attribute_type_id = 1005 '
                . 'ORDER BY attribute_type_id;');
        $FinancialAccountTypeList = $query->result_array();
        $indemnitorList = array(array('key' => 0, 'name' => 'None'));
        $powerList = array(array('key' => 0, 'name' => 'Empty'));
        return serialize(array(
                'state'          => $stateList,
                'country'        => $countryList,
                'yesno'          => $yesnoList,
                'rentorown'      => $rentorown,
                'postal'         => $postalList,
                'telcomno'       => $telcomnoList,
                'email'          => $emailList,
                'referenceType'  => $referenceTypeList,
                'partyType'      => $partyTypeList,
                'userLevels'     => $userLevels,
                'selfLevel'      => $selfLevel,
                'financialType'  => $FinancialAccountTypeList,
                'indemnitorList' => $indemnitorList,
                'powerList'      => $powerList
            ));
    }

    public function getUserdata() {
        #see if defendant has not been started yet
        if (isset($_SESSION['defendantData'])) {
            $defendant = unserialize($_SESSION['defendantData']);
        } else {
            $defendant = new Domain();
            $_SESSION['defendantData'] = serialize($defendant);
        }
        if (!isset($_SESSION['defendantSelectLists'])) {
            $_SESSION['defendantSelectLists'] = $this->defendant_model->getSelectLists();
        }
        $defendant = $this->defendant_model->getSelectLists();
        // $defendant = new Domain();
         //print_r($defendant);
        return $defendant;
    }

    public function updateDynamicLists($defendant_id = 0) {
        if (!$defendant_id) {
            $defendant_id = $this->getUserdata()->getField('party_role_id');
        }
        if (!$defendant_id) {
            return;
        }
        if (!isset($_SESSION['defendantSelectLists'])) {
            $_SESSION['defendantSelectLists'] = $this->defendant_model->getSelectLists();
        }
        $defendantSelectLists = unserialize($_SESSION['defendantSelectLists']);
        $indemnitorList = array(array('key' => 0, 'name' => 'None'));
        $query = $this->db->query('SELECT "IndemnitorAttribute".indemnitor_id, '
                . 'first_name, middle_name, last_name, suffix_name '
                . 'FROM "DefendantAttribute" '
                . 'INNER JOIN "IndemnitorAttribute" '
                . 'ON ("DefendantAttribute".attribute_id '
                    . '= "IndemnitorAttribute".attribute_id) '
                . 'INNER JOIN "PartyRole" '
                . 'ON ("IndemnitorAttribute".indemnitor_id = "PartyRole".party_role_id) '
                . 'INNER JOIN "Person" '
                . 'ON ("PartyRole".party_id = "Person".person_id) '
                . 'WHERE "DefendantAttribute".party_role_id = ?;', array($defendant_id));
        $result = $query->result_array();
        foreach ($result as $row) {
            $fullName = $row['indemnitor_id'] . ': ' . $row['first_name'] . ' '
                    . $row['middle_name'] . ' ' . $row['last_name'] . ' '
                    . $row['suffix_name'];
            array_push($indemnitorList,
                    array('key' => $row['indemnitor_id'], 'name' => $fullName));
        }
        $defendantSelectLists['indemnitorList'] = $indemnitorList;
        $powerList = array(array('key' => 0, 'name' => 'Empty'));
        $query = $this->db->query('SELECT "BailBond".bailbond_id, '
                . 'power_number, folio_number, power_amount '
                . 'FROM "BailBond" '
                . 'LEFT JOIN "Power" '
                . 'ON ("BailBond".power_id '
                    . '= "Power".power_id) '
                . 'LEFT JOIN "IndemnitorBailBond" '
                . 'ON ("BailBond".bailbond_id = "IndemnitorBailBond".bailbond_id) '
                . 'WHERE "BailBond".defendant_id = ?;', array($defendant_id));
        $result = $query->result_array();
        foreach ($result as $row) {
            $fullName = $row['bailbond_id'] . ': ' . $row['power_number'] . ' '
                    . $row['folio_number'] . ' ' . $row['power_amount'];
            array_push($powerList,
                    array('key' => $row['bailbond_id'], 'name' => $fullName));
        }
        $defendantSelectLists['powerList'] = $powerList;
        $_SESSION['defendantSelectLists'] = serialize($defendantSelectLists);
    }

    # Sets the current form data into the userdata
    public function setUserdata() {
        $defendant = $this->defendant_model->getUserdata();
        #$this->input->post can not be iterated on, so we use $_POST directly
        foreach($_POST as $key => $value) {
            #do not store emtpy values or hidden fields
            if (!in_array($key, array('isNew', 'form_source', 'submitForm', 'readonly_defendant_name'))) {
                $explodedKey = explode('-', $key);
                #checking to see if we are at a valid page is handled in the class Domain
                #the document and bailbond pages are handled differently
                if ($explodedKey && $explodedKey[0] && $explodedKey[0] != 'documents') {
                        $defendant->setField($explodedKey, $value);
                }
                /*if ($currentPage && $currentPage != 'documents') {
                    $lastIndex = count($explodedKey) - 1;
                    #if $value has a number after the page,
                    #that number is one more than the index into the array in domain
                    if ($lastIndex > 0 && is_numeric($explodedKey[1])) {
                        #next see if the second to Last Index is numeric
                        if ($lastIndex > 1 && is_numeric($explodedKey[2])) {
                            #if so then we have a nested field
                            # and we need to also send the second to last number
                            # the '- 1' outside the brakets is because field numbering starts at 1 not 0
                            $defendant->setField($currentPage, array($explodedKey[$lastIndex] - 1
                                    array_slice($explodedKey, 1, $lastIndex - 2),
                                    array($explodedKey[$lastIndex - 1] - 1, $value)));
                        } else { #we send just the last Index
                            $defendant->setField($currentPage, array($explodedKey[$lastIndex] - 1,
                                    array_slice($explodedKey, 1, $lastIndex - 1), $value));
                        }
                    } else {
                        $defendant->setField($currentPage, array(array_slice($explodedKey, 1), $value));
                    }
                }*/
            }
        }
        $_SESSION['defendantData'] = serialize($defendant);
    }

    public function increaseFieldCount($fieldGroup) {
        $defendant = $this->defendant_model->getUserdata();
        $defendant->setField('increaseFieldCount', $fieldGroup);
        $_SESSION['defendantData'] = serialize($defendant);
    }

    public function decreaseFieldCount($fieldGroup) {
        $defendant = $this->defendant_model->getUserdata();
        $defendant->setField('decreaseFieldCount', $fieldGroup);
        $_SESSION['defendantData'] = serialize($defendant);
    }

    public function loadUserData($party_role_id) {
        $defendant = $this->defendant_model->getUserdata();
        $defendant_persistence = new Domain_Persistence($defendant);
        $defendant_persistence->getFromDatabase($this->db, $party_role_id);
        $defendant = $defendant_persistence->getCounterpart();
        $_SESSION['defendantData'] = serialize($defendant);
    }


    # Saves the data from userdata into the database
    #  Returns error message or NULL on successful insert
    public function save() {
        $defendant = $this->defendant_model->getUserdata();
        $domain_persistence = new Domain_Persistence($defendant);
        $error = $domain_persistence->saveToDatabase($this->db);
        $_SESSION['defendantData'] = serialize($domain_persistence->getCounterpart());
        return $error; #might be NULL if no error is found
    }

    #defendant jqGrid function support
    public function getData($params = "", $page = "")
    {
        # TODO: verify login
        #if ( ! $this->session->userdata('is_logged_in') )
        #{
        #    return null;
        #}

        # TODO: if restricted user, only show active defendants
        #if ( ! (strcasecmp($this->session->userdata('individual_type'), 'Admin') == 0 || strcasecmp($this->session->userdata('individual_type'), 'Inspector') == 0) )
        #{
        #    $params[ 'active' ] = '1';
        #        $this->db->where("(individual_id = '" . $this->session->userdata('individual_id') . "' OR entered_by = '" . $this->session->userdata('individual_id') . "')");
        #}         {
        $queryText = 'SELECT COALESCE (power_number, folio_number, '
                . "'') as number, last_name, first_name, charge_desc, "
                . 'contact_number, "PartyRole".party_role_id, updated_time from "Person" '
                . 'INNER JOIN "PartyRole" '
                . 'ON ("Person".person_id = "PartyRole".party_id) '
                . 'INNER JOIN "Defendant" '
                . 'ON ("PartyRole".party_role_id = "Defendant".party_role_id) '
                . 'LEFT JOIN (SELECT DISTINCT ON (phoneparty) contact_number, '
                    . '"PartyContactMechanism".party_role_id as phoneparty '
                    . 'FROM "TelecommunicationNumber" '
                    . 'INNER JOIN "ContactMechanism" '
                    . 'ON ("TelecommunicationNumber".telecom_no_id '
                        . '= "ContactMechanism".contact_mechanism_id) '
                    . 'INNER JOIN "ContactMechanismType" '
                    . 'ON ("ContactMechanismType".description '
                        . "= 'Primary Number') "
                    . 'INNER JOIN "PartyContactMechanism" '
                    . 'ON ("ContactMechanism".contact_mechanism_id '
                        . '= "PartyContactMechanism".contact_mechanism_id)) '
                . 'as phone ON ("phone".phoneparty '
                    . '= "PartyRole".party_role_id) '
                . 'LEFT JOIN "BailBond" '
                . 'ON ("PartyRole".party_role_id = "BailBond".defendant_id) '
                . 'LEFT JOIN "Charge" '
                . 'ON ("BailBond".charge_id = "Charge".charge_id) '
                . 'LEFT JOIN "Power" '
                . 'ON ("BailBond".power_id = "Power".power_id) '
                . 'WHERE "PartyRole".party_role_type_id = 2000';
        $queryParams = array();

        # search strings
        if ($params['party_role_id'] != null) {
            $queryText .= ' AND "PartyRole".party_role_id = ?';
            $queryParams[] = $params['party_role_id'];
        }
        if ($params['last_name'] != null) {
            $queryText .= ' AND last_name LIKE ?';
            $queryParams[] = '%' . $params['last_name'] . '%';
        }
        if ($params['first_name'] != null) {
            $queryText .= ' AND first_name LIKE ?';
            $queryParams[] = '%' . $params['first_name'] . '%';
        }
        if ($params['contact_number'] != null) {
            $queryText .= ' AND contact_number LIKE ?';
            $queryParams[] = '%' . $params['contact_number'] . '%';
        }
        if ($params['number'] != null) {
            $queryText .= ' AND (power_number LIKE ? OR folio_number LIKE ?)';
            $queryParams[] = '%' . $params['number'] . '%';
            $queryParams[] = '%' . $params['number'] . '%';
        }
        if ($params['charge_desc'] != null) {
            $queryText .= ' AND charge_desc LIKE ?';
            $queryParams[] = '%' . $params['charge_desc'] . '%';
        }

        # order by
        if ($params['sort_by']) {
            #if party_role_id then make sure it's not ambiguous
            if (strncmp($params['sort_by'],  'party_role_id', 13) == 0) {
                $params['sort_by'] = '"PartyRole".party_role_id';
            }
            if (strtolower($params['sort_direction']) == 'asc') {
                $queryText .= ' ORDER BY ' . pg_escape_string($params['sort_by']) . ' ASC';
            } else {
                $queryText .= ' ORDER BY ' . pg_escape_string($params['sort_by']) . ' DESC';
            }
        }

        # : - pages - limit
        if ($page != "all") {
            if (is_numeric($params['num_rows']) && is_numeric($params['page'])) {
                $queryText .= ' LIMIT ' . pg_escape_string($params['num_rows']) . ' OFFSET '
                        . pg_escape_string($params["num_rows"] *  ($params["page"] - 1));
            }
        }
        #end command
        $queryText .= ';';

        #run query
        $query = $this->db->query($queryText, $queryParams);
        return $query->result();
    }



    
}

/* End of file defendant_model.php */
/* Location: ./application/models/defendant_model.php */