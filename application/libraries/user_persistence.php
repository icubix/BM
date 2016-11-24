<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }

        if ($this->counterpart->getField('party_role_id') != 0) {
            $party_role_id = $this->counterpart->getField('party_role_id');
        }

        #check email is valid and not in use
        $contacts = $this->counterpart->getField('contacts');
        $primaryEmail = NULL;
        #first get id for primary email used for login
        $query = $database->query('SELECT contact_mechanism_type_id FROM "ContactMechanismType" '
                . "WHERE description = 'Primary Electronic Address';");
        $primary_electronic_address_id = 0;
        if ($query->num_rows() > 0) {
            $primary_electronic_address_id = $query->row()->contact_mechanism_type_id;
        } else {
            return "Internal Error: Contact Administrator. Email type not found.";
        }
        #check if a primary email is in the contacts list
        foreach($contacts as $row) {
            if (is_a($row, 'Electronic_Address')) {
                if ($primary_electronic_address_id == $row->getField('email_type')) {
                    $primaryEmail = $row->getField('electronic_address');
                    if ($primaryEmail) {
                        break;
                    }
                }
            }
        }
        if (!$primaryEmail) {
            return 'One primary email is required for log-in.';
        }
        #then see if email is used by someone else for login
        $query = $database->query('SELECT electronic_address from "vAuthUser" '
                . 'WHERE electronic_address = ? AND party_role_id != ?;',
                array($primaryEmail, $party_role_id));
        if ($query->num_rows() > 0) {
            return 'Email address in use by another user.';
        }

        #check thru_date/account experation
        #note: this isn't saved until later
        $thru_date = $this->counterpart->getField('thru_date');
        if ($this->counterpart->getField('expires')) {
            if ($thru_date) {
                $thru_date = strtotime($thru_date);
                if ($thru_date) {
                    $thru_date = date('Y-m-d', $thru_date);
                } else {
                    return 'Invalid Experation Date format. Use YYYY-MM-DD';
                }
            } else {
                return 'Must give Experation Date if account expires.';
            }
        } else {
            $thru_date = NULL;
        }

        #save person or org to database
        $partyTypeDescrption = $this->counterpart->getField('description');
        if ($partyTypeDescrption == 'Person') {
            $person = $this->counterpart->getField('person');
            $person->setField('party_role_desc', 'User');
            $partyTypePersistence = new Person_Persistence($person);
            $error = $partyTypePersistence->saveToDatabase($database, $party_role_id);
            $this->counterpart->setField('person',
                    $partyTypePersistence->getCounterpart());
            #if adding a new user set party_role_id to inserted id
            if ($party_role_id == 0) {
                $party_role_id = $partyTypePersistence->getCounterpart()->getField('party_role_id');
            }
            if ($error) {
                return $error;
            }
        } elseif ($partyTypeDescrption == 'Organization') {
            $organization = $this->counterpart->getField('organization');
            $organization->setField('party_role_desc', 'User');
            $partyTypePersistence = new Organization_Persistence($organization);
            $error = $partyTypePersistence->saveToDatabase($database, $party_role_id);
            $this->counterpart->setField('organization',
                    $partyTypePersistence->getCounterpart());
            #if adding a new user set party_role_id to inserted id
            if ($party_role_id == 0) {
                $party_role_id = $partyTypePersistence->getCounterpart()->getField('party_role_id');
            }
            if ($error) {
                return $error;
            }
        } else {
            return 'Must select Person or Organization';
        }

        #note: checking expires and validating formate is done above
        $query = $database->query('UPDATE "PartyRole" SET thru_date = ? '
                . 'WHERE party_role_id = ?;', array($thru_date, $party_role_id));

        #save $contacts which was grabbed above;
        $query = $database->query('SELECT contact_mechanism_id '
                . 'FROM "PartyContactMechanism" '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($contacts as $innerRow) {
                if ($row->contact_mechanism_id
                        == $innerRow->getField('contact_mechanism_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "ContactMechanism" '
                        . 'WHERE contact_mechanism_id = ?;',
                        array($row->contact_mechanism_id));
            }
        }
        foreach ($contacts as $row) {
            if (is_a($row, 'Postal_Address')) {
                $row = new Postal_Address_Persistence($row);
            } elseif (is_a($row, 'Electronic_Address')) {
                $row = new Electronic_Address_Persistence($row);
            } elseif (is_a($row, 'Telecommunication_Number')) {
                $row = new Telecommunication_Number_Persistence($row);
            } else { #nothing will be saved, due to NULL/isEmpty check
                $row = new Telecommunication_Number_Persistence(NULL);
            }
            $error = $row->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }

        #it should have been ensured that UserData
        # has this party_role_id in it before getting here
        $user_level = $this->counterpart->getField('user_level');
        #make sure user_level is valid
        $query = $database->query('SELECT user_role_type_id from "UserRoleType" '
                . 'WHERE user_level = ?;', array($user_level));
        if ($query->num_rows() > 0) {
            $user_role_type_id = $query->row()->user_role_type_id;
            #see if we need insert or update
            $query = $database->query('SELECT user_role_type_id from "UserRole" '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
            if ($query->num_rows() > 0) {
                $query = $database->query('UPDATE "UserRole" SET '
                        . 'user_role_type_id = ? WHERE party_role_id = ?;',
                        array($user_role_type_id, $party_role_id));
            } else {
                $database->query('INSERT INTO "UserRole" '
                        . '(party_role_id, user_role_type_id) VALUES (?, ?);',
                        array($party_role_id, $user_role_type_id));
            }
        } else {
            return 'Invalid user level specified';
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $this->counterpart->setField('party_role_id', $party_role_id);
        #do not grab the password here
        $description = NULL;
        $electronic_address = NULL;
        #get if user is person or organization first
        $query = $database->query('SELECT "PartyType".description '
                . 'FROM "PartyRole" '
                . 'INNER JOIN "Party" '
                . 'ON ("PartyRole".party_id = "Party".party_id) '
                . 'INNER JOIN "PartyType" '
                . 'ON ("Party".party_type_id = "PartyType".party_type_id) '
                . "WHERE party_role_id = ?;",
                array($party_role_id));
        if ($query->num_rows() > 0) {
            $description = $query->row()->description;
            $this->counterpart->setField('description', $description);
        }
        #then get their names
        if ($description == 'Person') {
            $person_persistence = new Person_Persistence(new Person());
            $person_persistence->getFromDatabase($database, $party_role_id);
            $this->counterpart->setField('person',
                    $person_persistence->getCounterpart());
        } elseif ($description == 'Organization') {
            $organization_persistence = new Organization_Persistence(new Organization());
            $organization_persistence->getFromDatabase($database, $party_role_id);
            $this->counterpart->setField('organization',
                    $organization_persistence->getCounterpart());
        } elseif ($description != NULL) {
            error_log('unkown party type description: ' . $description);
        }
        #get user level
        $query = $database->query('SELECT user_level from "UserRole" '
                . 'INNER JOIN "UserRoleType" '
                . 'ON ("UserRole".user_role_type_id = "UserRoleType".user_role_type_id) '
                . 'WHERE party_role_id = ?', array($party_role_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('user_level', $query->row()->user_level);
        }
        #then get other info from vAuthUser
        $query = $database->query('SELECT '
                . "date_trunc('minute', thru_date) as rounded_thru_date "
                . 'FROM "vAuthUser" WHERE party_role_id = ?;', array($party_role_id));
        if ($query->num_rows() > 0) {
            if ($query->row()->rounded_thru_date == NULL) {
                $this->counterpart->setField('expires', 0);
            } else {
                $this->counterpart->setField('expires', 1);
            }
            $this->counterpart->setField('thru_date', substr($query->row()->rounded_thru_date, 0, -6));
        }
        #then get contacts
        $telcomnoContacts = Telecommunication_Number_Persistence::getFullListFromDatabase($database, $party_role_id);
        if (count($telcomnoContacts) == 1 && $telcomnoContacts[0]->isEmpty()) {
            $telcomnoContacts = array();
        }
        $postalContacts = Postal_Address_Persistence::getFullListFromDatabase($database, $party_role_id);
        if (count($postalContacts) == 1 && $postalContacts[0]->isEmpty()) {
            $postalContacts = array();
        }
        #we do not need to check the email list to see if it's empty because it holds the login email
        $contacts = array_merge(
                Electronic_Address_Persistence::getFullListFromDatabase(
                        $database, $party_role_id),
                $telcomnoContacts, $postalContacts
            );
        $this->counterpart->setField('contacts', $contacts);
        #finally get how long user has to wait between logins
        $query = $database->query("SELECT date_trunc('minute', failed_login_first) "
                . 'as rounded_login_first, failed_attempts, '
                . 'GREATEST(EXTRACT(EPOCH FROM current_timestamp-failed_login_first)/3600,0) '
                . 'as since_failed_first '
                . 'FROM "UserData" WHERE party_role_id = ?;',
                array($party_role_id));
        if ($query->num_rows() > 0) {
            $failed_login_first = substr($query->row()->rounded_login_first, 0, -6);
            $failed_attempts = $query->row()->failed_attempts;
            $since_failed_first = $query->row()->since_failed_first;
            $adjusted_attemps = $failed_attempts - (2 * $since_failed_first) - 20;
            $failed_login_wait = NULL;
            if ($adjusted_attemps < 1) {
                $failed_login_wait = 'None';
            } elseif ($adjusted_attemps < 60) {
                $failed_login_wait = $adjusted_attemps*$adjusted_attemps; #in seconds
                if ($failed_login_wait < 300) {
                    $failed_login_wait = round($failed_login_wait, 0) . ' seconds';
                } else {
                    $failed_login_wait = round($failed_login_wait/60, 1) . ' minutes';
                }
            } else {
                $failed_login_wait = '1 hour';
            }
            $this->counterpart->setField('failed_login_wait', $failed_login_wait);
            if ($failed_login_wait != 'None') {
                $this->counterpart->setField('failed_attempts', $failed_attempts);
                $this->counterpart->setField('failed_login_first', $failed_login_first);
            } else {
                $this->counterpart->setField('failed_attempts', 'NA');
                $this->counterpart->setField('failed_login_first', 'NA');
            }
        }
    }
}

/* End of file user_persistence.php */
/* Location: ./application/libraries/user_persistence.php */