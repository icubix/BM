<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Limited_Auth_Model extends CI_Model {
    private function cleanAccessEvents() {
        $restricted_access_violations_to_keep = 500 - 1;
        $query = $this->db->query('SELECT val FROM "ApplicationMetaSettings" '
                . "WHERE name = 'restricted_access_violations_to_keep';");
        if ($query->num_rows() > 0) {
            $restricted_access_violations_to_keep = $query->row()->val - 1;
        }
        if (is_numeric($restricted_access_violations_to_keep)
                && $restricted_access_violations_to_keep > 0) {
            $this->db->query('DELETE FROM "UserRoleEvent" '
                . "WHERE description = 'Access Violation'"
                . 'AND time_stamp < '
                . '(SELECT time_stamp from "UserRoleEvent" '
                . "WHERE description = 'Access Violation' "
                . 'ORDER BY time_stamp DESC OFFSET ? LIMIT 1);',
                array($restricted_access_violations_to_keep));
        } else {
            $this->db->query('DELETE FROM "UserRoleEvent" '
                . "WHERE description = 'Access Violation';");
        }
    }
    
    #this function is used to verify the user has privliges to the requested level
    #if level requested is less than level of the user true is returned
    #otherwise false meaning that the user doesn't have privileges necessary
    public function authLevel($levelRequested) {
        if (!$this->session->userdata('is_logged_in')) {
            return FALSE;
        }
        $userLevel = $this->session->userdata('user_level');
        if (is_numeric($levelRequested) && is_numeric($userLevel)) {
            if ($levelRequested <= $userLevel) {
                return TRUE;
            }
        } #else
        return FALSE;
    }

    #this function is used to verify the passed party role ID matches
    #the logged in users ID
    #for use in cases when you can only perform certain actions to your own account
    public function partyRoleMatches($passed_party_role_id) {
        if (!$this->session->userdata('is_logged_in')) {
            return FALSE;
        }
        $party_role_id = $this->session->userdata('party_role_id');
        if (is_numeric($passed_party_role_id) && is_numeric($party_role_id)) {
            if ($passed_party_role_id == $party_role_id) {
                return TRUE;
            }
        } #else
        return FALSE;
    }

    public function logAccessViolation($currentURL) {
        if ($this->session->userdata('is_logged_in')) {
            $this->load->model('auth_model');
            $this->db->query('INSERT INTO "UserRoleEvent" '
                    . '(event_id, time_stamp, party_role_id, '
                    . 'client_ip, origin, description, detail) VALUES '
                    . '(DEFAULT, DEFAULT, ?, ?, '
                    . pg_escape_literal($this->auth_model->locationOfIp(
                                    $this->input->ip_address()))
                    . ", 'Access Violation', ?);",
                    array($this->session->userdata('party_role_id'),
                            $this->input->ip_address(),
                            $currentURL
                    ));

            # then make sure the log isn't too big
            $this->cleanAccessEvents();
        }
    }
}

/* End of file limited_auth_model.php */
/* Location: ./application/models/limited_auth_model.php */