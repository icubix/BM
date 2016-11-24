<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Auth_Model extends CI_Model{




    public function locationOfIp($ip) {
        #http://www.ipinfodb.com/ip_location_api.php
        #2de9d8d09c08af58ccf9a9176d895dce20330d1bcf6e783ac4c4c044955db951
        $d = @file_get_contents("http://api.ipinfodb.com/v3/ip-city/"
                . "?key=2de9d8d09c08af58ccf9a9176d895dce20330d1bcf6e783ac4c4c044955db951"
                . "&format=xml&ip=$ip",
                FALSE,
                stream_context_create(array('http'=>array('ignore_errors' => TRUE))));
                
        $returnString = 'Not available';
        //Use backup server if cannot make a connection
        if (!$d) {
            $backup = @file_get_contents("http://api.ipinfodb.com/v3/ip-country/"
                    . "?key=2de9d8d09c08af58ccf9a9176d895dce20330d1bcf6e783ac4c4c044955db951"
                    . "&format=xml&ip=$ip",
                    FALSE,
                    stream_context_create(array('http'=>array('ignore_errors' => TRUE))));
            if (!$backup) {
                return 'Not available'; // Failed to open connection
            }
            $result = new SimpleXMLElement($backup);
            $returnString = $result->countryName;
        } else {
            $result = new SimpleXMLElement($d);
            $returnString = $result->countryName . ' ' . $result->regionName
                    . ' ' . $result->cityName;
        }
        //Return the data as a string
        if (!$returnString) {
            return 'Not available'; // Failed to open connection
        }
        return $returnString;
    }

    private function cleanLoginEvents() {
        $failed_login_attempts_to_keep = 500 - 1;
        $query = $this->db->query('SELECT val FROM "ApplicationMetaSettings" '
                . "WHERE name = 'failed_login_attempts_to_keep';");
        if ($query->num_rows() > 0) {
            $failed_login_attempts_to_keep = $query->row()->val - 1;
        }
        if (is_numeric($failed_login_attempts_to_keep)
                && $failed_login_attempts_to_keep > 0) {
            $this->db->query('DELETE FROM "UserRoleEvent" '
                . "WHERE description = 'Failed Login'"
                . 'AND time_stamp < '
                . '(SELECT time_stamp from "UserRoleEvent" '
                . "WHERE description = 'Failed Login' "
                . 'ORDER BY time_stamp DESC OFFSET ? LIMIT 1);',
                array($failed_login_attempts_to_keep));
        } else {
            $this->db->query('DELETE FROM "UserRoleEvent" '
                . "WHERE description = 'Failed Login';");
        }
    }

         public function register() {
        #check to see if email is formed properly
        if (!Electronic_Address_Persistence::validateEmail($this->input->post('email'))) {
            return FALSE;
        }
        #check password length
        if (strlen($this->input->post('password')) < 6) {
            return FALSE;
        }
        # check to see if email has been used already, return false if so
        $this->db->where('electronic_address', $this->input->post('email'));
        $query = $this->db->get('vAuthUser');
        if ($query->num_rows() > 0) {
            return FALSE;
        }
        /* else */
        $hashedPassword = password_hash($this->input->post('password'), PASSWORD_BCRYPT, array('cost' => 14));
        $this->db->query('INSERT INTO "Party" VALUES (DEFAULT, 1000) RETURNING party_id;');

        $party = $this->db->query('SELECT LASTVAL()')->row()->lastval;
        $this->db->query('INSERT INTO "PartyRole" VALUES (?'
            . ', 1000, DEFAULT, DEFAULT) RETURNING party_role_id;', array($party));

        $party_role = $this->db->query('SELECT LASTVAL()')->row()->lastval;

        $this->db->query('INSERT INTO "UserData" VALUES (?, ?, '
            . "(CAST (NULL AS VARCHAR(512))), 'Gv1b14');", array($party_role, $hashedPassword));

        $query = $this->db->query('SELECT user_role_type_id FROM "UserRoleType" '
                        . "WHERE user_role = 'Guest';");

        $user_role_type_id = $query->row()->user_role_type_id;
        $this->db->query('INSERT INTO "UserRole" VALUES (?, ?);',
                        array($party_role, $user_role_type_id));

        $this->db->query('INSERT INTO "ContactMechanism" VALUES (DEFAULT, 1201) '
                        . 'RETURNING contact_mechanism_id;');

        $contact = $this->db->query('SELECT LASTVAL()')->row()->lastval;
        $this->db->query('INSERT INTO "PartyContactMechanism" VALUES (?, ?);',
            array($party_role, $contact));
        
        $this->db->query('INSERT INTO "ElectronicAddress" VALUES (?, ?);',
            array($contact, $this->input->post('email')));

        return array('username'      => $this->input->post('email'),
                     'is_logged_in'  => TRUE,
                     'party_role_id' => $party_role,
                     'user_level'     => 0
            );
    }


    public function validate() {
        echo "coming to model";
        $query = $this->db->query('SELECT electronic_address, "vAuthUser".party_role_id, '
                . '"UserData".pwd_hash, user_level, failed_attempts, '
                . "CASE WHEN thru_date IS NULL THEN 'No' "
                . "WHEN thru_date < CURRENT_TIMESTAMP THEN 'No' "
                . "ELSE 'Yes' END AS expired, "
                . 'GREATEST(EXTRACT(EPOCH FROM current_timestamp-failed_login_first)/3600,0) '
                . 'as since_failed_first, '
                . 'GREATEST(EXTRACT(EPOCH FROM current_timestamp-failed_login_last),0) '
                . 'as since_failed_last '
                . 'FROM "vAuthUser" '
                . 'INNER JOIN "UserData" '
                . 'ON ("vAuthUser".party_role_id = "UserData".party_role_id) '
                . 'WHERE electronic_address = ?;', array($this->input->post('email')));

        // echo ('SELECT electronic_address, "vAuthUser".party_role_id, '
        //         . '"UserData".pwd_hash, user_level, failed_attempts, '
        //         . "CASE WHEN thru_date IS NULL THEN 'No' "
        //         . "WHEN thru_date < CURRENT_TIMESTAMP THEN 'No' "
        //         . "ELSE 'Yes' END AS expired, "
        //         . 'GREATEST(EXTRACT(EPOCH FROM current_timestamp-failed_login_first)/3600,0) '
        //         . 'as since_failed_first, '
        //         . 'GREATEST(EXTRACT(EPOCH FROM current_timestamp-failed_login_last),0) '
        //         . 'as since_failed_last '
        //         . 'FROM "vAuthUser" '
        //         . 'INNER JOIN "UserData" '
        //         . 'ON ("vAuthUser".party_role_id = "UserData".party_role_id) '
        //         . 'WHERE electronic_address = ?;');
       

        if ($query->num_rows() > 0) {
            //  echo $query->num_rows();
            // echo "kali";
            #first see if it has been long enough since last failed login
            // $adjusted_attemps = $query->row()->failed_attempts
            //         - (2 * $query->row()->since_failed_first) - 20; #since_failed_first is in hours
            // $time_remaining = 0;
            // if ($adjusted_attemps > 60) {
            //     $time_remaining = 3600 - $query->row()->since_failed_last; #since_failed_last is in seconds
            // } elseif ($adjusted_attemps > 0) {
            //     $time_remaining = $adjusted_attemps*$adjusted_attemps
            //                             - $query->row()->since_failed_last; #since_failed_last is in seconds
            // }
            // if ($time_remaining > 600) {
            //     return 'There have been ' . $query->row()->failed_attempts . ' in the last '
            //             . round($query->row()->since_failed_first,2) . ' hours. '
            //             . 'If most of those where not you contact your administrator. '
            //             . 'Before you can login you must wait ' . $time_remaining
            //             . ' seconds.';
            // }
            // if ($time_remaining > 0) {
            //     return 'Too many failed login attempts.  Before you can login you must wait '
            //             . round($time_remaining) . ' seconds.';
            // }
            
            if (password_verify($this->input->post('password'), $query->row()->pwd_hash)) {
                /* Check to see if hash algorithm or parameters have changed */
                if (password_needs_rehash($query->row()->pwd_hash, PASSWORD_BCRYPT, array('cost' => 14))) {
                    $newHash = password_hash($this->input->post('password'), PASSWORD_BCRYPT, array('cost' => 14));
                    $this->db->where('party_role_id', $query->row()->party_role_id);
                    $this->db->update("UserData", array('pwd_hash' => $newHash));
                }
                return array('username'      => $query->row()->electronic_address,
                             'is_logged_in'  => TRUE,
                             'party_role_id' => $query->row()->party_role_id,
                             'user_level'     => $query->row()->user_level
                        );
            } else { #failed password validation
                if ($adjusted_attemps > -20) { #if we failed a lot recently
                    #then increase attempts by 1 and set last failed to now
                    $this->db->query('UPDATE "UserData" SET '
                            . '(failed_attempts, failed_login_last) '
                            . '= (?, current_timestamp) '
                            . 'WHERE party_role_id = ?;',
                            array($query->row()->failed_attempts + 1,
                                $query->row()->party_role_id));
                } else { #if we haven't failed much lately
                    #then set failed attempts to 1 and set first failed to now
                    $this->db->query('UPDATE "UserData" SET '
                            . '(failed_attempts, failed_login_first) '
                            . '= (1, current_timestamp) '
                            . 'WHERE party_role_id = ?;',
                            array($query->row()->party_role_id));
                }
                #then log the event
                $this->db->query('INSERT INTO "UserRoleEvent" '
                        . '(event_id, time_stamp, party_role_id, '
                        . 'client_ip, origin, description) VALUES '
                        . '(DEFAULT, DEFAULT, ?, ?, '
                        . pg_escape_literal($this->locationOfIp($this->input->ip_address()))
                        . ", 'Failed Login');",
                        array($query->row()->party_role_id,
                              $this->input->ip_address()
                        ));
                $this->cleanLoginEvents();
            }
        }

        return 'Invalid Email or Password';
    }


}