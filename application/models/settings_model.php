<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Settings_Model extends CI_Model {
    #clear left over data from previous add, edit, or view
    public function clearUserdata() {
        unset($_SESSION['singleUser']);
        unset($_SESSION['defendantSelectLists']);
    }

    #returns true if loaded user, false if auth fail or id fail
    public function loadSingleUser($party_role_id) {
        $user_persistence = new User_Persistence(new User_Instance());
        $user_persistence->getFromDatabase($this->db, $party_role_id);
        $user_instance = $user_persistence->getCounterpart();
        #make sure user being edited is a lower auth level then current user
        #or user is an admin or user is editing themselves
        $this->load->model('limited_auth_model');
        if (!($this->limited_auth_model->authLevel('100')
                    || $this->limited_auth_model->authLevel(
                            $user_instance->getField('user_level')+1
                       )
                    || $this->limited_auth_model->partyRoleMatches(
                            $user_instance->getField('party_role_id')
                    ))) {
            $this->limited_auth_model->logAccessViolation(current_url());
            $user_instance = new User_Instance();
            return FALSE;
        }
        #see if selectlists has not been started yet
        if (!isset($_SESSION['defendantSelectLists'])) {
            $this->load->model('defendant_model');
            $_SESSION['defendantSelectLists'] = $this->defendant_model->getSelectLists();
        }
        $_SESSION['singleUser'] = serialize($user_instance);
        return TRUE;
    }

    public function getSingleUser() {
        #see if defendant has not been started yet
        $user_instance = NULL;
        if (isset($_SESSION['singleUser'])) {
            $user_instance = unserialize($_SESSION['singleUser']);
        } else {
            $user_instance = new User_Instance();
            $_SESSION['singleUser'] = serialize($user_instance);
        }
        if (!isset($_SESSION['defendantSelectLists'])) {
            $this->load->model('defendant_model');
            $_SESSION['defendantSelectLists'] = $this->defendant_model->getSelectLists();
        }
        return $user_instance;
    }
    
    # Sets the current form data into the userdata
    public function setSingleUserData() {
        $user_instance = $this->settings_model->getSingleUser();
        #$this->input->post can not be iterated on, so we use $_POST directly
        foreach($_POST as $key => $value) {
            #do not store emtpy values or hidden fields
            if (!in_array($key, array('na-current_password', 'na-new_password',
                    'na-new_password_confirm', 'passwordBoxShown',
                    'form_source', 'submitForm'))) {
                $explodedKey = explode('-', $key);
                #remove user_instance from start of key
                #  we do this so the rest of the code is simular to how Domain works
                $explodedKey = array_slice($explodedKey, 1);
                $user_instance->setField($explodedKey, $value);
            }
        }
        $_SESSION['singleUser'] = serialize($user_instance);
    }

    public function increaseUserFieldCount($fieldGroup) {
        $user_instance = $this->settings_model->getSingleUser();
        $user_instance->setField('increaseFieldCount', $fieldGroup);
        $_SESSION['singleUser'] = serialize($user_instance);
    }

    public function decreaseUserFieldCount($fieldGroup) {
        $user_instance = $this->settings_model->getSingleUser();
        $user_instance->setField('decreaseFieldCount', $fieldGroup);
        $_SESSION['singleUser'] = serialize($user_instance);
    }

    # Saves the data from userdata into the database
    #  Returns error message or NULL on successful insert
    public function saveUser() {
        $user_instance = $this->settings_model->getSingleUser();
        $this->load->model('limited_auth_model');
        if (!($this->limited_auth_model->authLevel('100')
                    || $this->limited_auth_model->authLevel(
                            $user_instance->getField('user_level')
                       ))) {
            $this->limited_auth_model->logAccessViolation(current_url());
            return 'Can not raise user level past personal access.';
        }
        #if password may need to be changed we do that here
        # so it's not stored more places then it needs to be
        if ($this->input->post('passwordBoxShown')) {
            $new_password = $this->input->post('na-new_password');
            if ($new_password != $this->input->post('na-new_password_confirm')) {
                return 'New passwords do not match';
            }
            if (strlen($new_password) > 0) {
                if (strlen($new_password) < 6) {
                    return 'New password too short. Minimum 6 characters.';
                } #else
                $this->load->model('auth_model');
                #if changing their own password
                $error = NULL;
                if ($this->limited_auth_model->partyRoleMatches(
                        $user_instance->getField('party_role_id'))) {
                    $error = $this->auth_model->verifyAndUpdatePassword(
                            $user_instance->getField('party_role_id'),
                            $new_password,
                            $this->input->post('na-current_password')
                        );
                } else { #changing another users's password
                    #note: user level compairisons are done in the function
                    $error = $this->auth_model->updatePassword(
                            $user_instance->getField('party_role_id'),
                            $new_password
                        );
                }
                if ($error) {
                    return $error;
                }
            }
        }
            
        $error = (new User_Persistence($user_instance))->saveToDatabase($this->db);
        return $error; #might be NULL if no error is found
    }

    public function resetLoginAttempts() {
        $user_instance = $this->settings_model->getSingleUser();
        $this->load->model('limited_auth_model');
        #if you're an admin or you're not trying to edit someone
        # of the same level or higher then reduce login attempt delay to 0
        if ($this->limited_auth_model->authLevel('100')
                    || !$this->limited_auth_model->authLevel(
                            $user_instance->getField('user_level')
                       )) {
            $this->db->query('UPDATE "UserData" SET failed_attempts = 0 '
                    . 'WHERE party_role_id = ?;',
                    array($user_instance->getField('party_role_id')));
        } else { #error message shouldn't be displayed often, since the button was hidden
            return 'Can only reset login time of someone of a lower user level.';
        }
    }

    #user jqGrid function support
    public function getUsersData($params = "", $page = "") {
        #$queryWhereAttached signifies if a WHERE has been attached to a statement
        # and it's safe to do AND instead of WHERE
        $queryWhereAttached = false;
        $queryText = 'SELECT electronic_address, "vAuthUser".party_role_id, '
                . '"vAuthUser".user_level, user_role, description, '
                . '(CASE WHEN organization_name IS NOT NULL '
                . 'THEN organization_name '
                . "ELSE first_name || ' ' || last_name "
                . 'END) AS name '
                . 'FROM "vAuthUser" '
                . 'INNER JOIN "UserRoleType" '
                . 'ON ("vAuthUser".user_level = "UserRoleType".user_level) '
                . 'INNER JOIN "PartyRole" '
                . 'ON ("vAuthUser".party_role_id = "PartyRole".party_role_id) '
                . 'INNER JOIN "Party" '
                . 'ON ("PartyRole".party_id = "Party".party_id) '
                . 'LEFT JOIN "Organization" '
                . 'ON ("Party".party_id = organization_id) '
                . 'LEFT JOIN "Person" '
                . 'ON ("Party".party_id = person_id)';
        $queryParams = array();
        
        # only show user levels below current users and their-self
        $user_level = $this->session->userdata('user_level');
        $party_role_id = $this->session->userdata('party_role_id');
        if ($user_level != 100) {
            if ($queryWhereAttached) {
                $queryText .= ' AND ("vAuthUser".user_level < ? OR "vAuthUser".party_role_id = ?)';
            } else {
                $queryText .= ' WHERE ("vAuthUser".user_level < ? OR "vAuthUser".party_role_id = ?)';
                $queryWhereAttached = true;
            }
            $queryParams[] = $user_level;
            $queryParams[] = $party_role_id;
        }
        # search strings
        if ($params['electronic_address'] != null) {
            if ($queryWhereAttached) {
                $queryText .= ' AND electronic_address LIKE ?';
            } else {
                $queryText .= ' WHERE electronic_address LIKE ?';
                $queryWhereAttached = true;
            }
            $queryParams[] = '%' . $params['electronic_address'] . '%';
        }
        if ($params['user_level'] != null) {
            if ($queryWhereAttached) {
                $queryText .= ' AND "vAuthUser".user_level = ?';
            } else {
                $queryText .= ' WHERE "vAuthUser".user_level = ?';
                $queryWhereAttached = true;
            }
            $queryParams[] = $params['user_level'];
        }
        if ($params['user_role'] != null) {
            if ($queryWhereAttached) {
                $queryText .= ' AND user_role LIKE ?';
            } else {
                $queryText .= ' WHERE user_role LIKE ?';
                $queryWhereAttached = true;
            }
            $queryParams[] = '%' . $params['user_role'] . '%';
        }
        if ($params['description'] != null) {
            if ($queryWhereAttached) {
                $queryText .= ' AND description LIKE ?';
            } else {
                $queryText .= ' WHERE description LIKE ?';
                $queryWhereAttached = true;
            }
            $queryParams[] = '%' . $params['description'] . '%';
        }
        if ($params['name'] != null) {
            if ($queryWhereAttached) {
                $queryText .= ' AND name LIKE ?';
            } else {
                $queryText .= ' WHERE name LIKE ?';
                $queryWhereAttached = true;
            }
            $queryParams[] = '%' . $params['name'] . '%';
        }

        # order by
        if ($params['sort_by']) {
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

/* End of file settings_model.php */
/* Location: ./application/models/settings_model.php */