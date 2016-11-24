<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if ($this->counterpart->getField('address_1st_line') == '') {
            return 'Address line 1 required';
        }
        $postal_code_id = 0;
        if ($this->counterpart->getField('postal_code') != '') {
            $query = $database->query('SELECT geo_id FROM "GeographicBoundary" '
                    . 'INNER JOIN "GeographicBoundaryType" '
                    . 'ON "GeographicBoundary".geo_type_id '
                    . '= "GeographicBoundaryType".geo_type_id '
                    . "WHERE geo_type_name = 'Postal Code' "
                    . 'AND geo_name = ?', array(substr(
                    $this->counterpart->getField('postal_code'), 0, 5)));
            if (count($query->result()) < 1) {
                return 'Invalid Zip Code';
            } #else
            $postal_code_id = $query->row()->geo_id;
        }
        if ($postal_code_id != 0 && $this->counterpart->getField('state_id')) {
            $query = $database->query('SELECT to_geo_id FROM "GeographicBoundaryAssociation" '
                    . 'INNER JOIN "GeographicBoundaryAssociationType" '
                    . 'ON ("GeographicBoundaryAssociation".geo_association_type_id '
                    . '= "GeographicBoundaryAssociationType".geo_association_type_id) '
                    . "WHERE from_geo_id = ? AND geo_association_name = 'ZipCodeCounty';",
                    array($postal_code_id));
            if ($query->num_rows() > 0) {
                $query = $database->query('SELECT to_geo_id FROM "GeographicBoundaryAssociation" '
                        . 'INNER JOIN "GeographicBoundaryAssociationType" '
                        . 'ON ("GeographicBoundaryAssociation".geo_association_type_id '
                        . '= "GeographicBoundaryAssociationType".geo_association_type_id) '
                        . "WHERE from_geo_id = ? AND geo_association_name = 'CountyState';",
                        array($query->row()->to_geo_id));
                if ($query->num_rows() > 0) {
                    if ($this->counterpart->getField('state_id')
                            != $query->row()->to_geo_id) {
                        return 'Zip Code and State do not match.';
                    }
                }
            }
        }
        $city_id = 0;
        if ($this->counterpart->getField('city') != '') {
            $query = $database->query('SELECT geo_id FROM "GeographicBoundary" '
                    . 'INNER JOIN "GeographicBoundaryType" '
                    . 'ON "GeographicBoundary".geo_type_id '
                    . '= "GeographicBoundaryType".geo_type_id '
                    . "WHERE geo_type_name = 'City' "
                    . 'AND geo_name = ?', array(
                    $this->counterpart->getField('city')));
            if ($query->num_rows() > 0) {
                $city_id = $query->row()->geo_id;
            }
        }
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $contact_mechanism_type_id = 0;
        if ($this->counterpart->getField('address_type')
                && is_numeric($this->counterpart->getField('address_type'))) {
            $contact_mechanism_type_id = $this->counterpart->getField('address_type');
        } else {
            $query = $database->query('SELECT contact_mechanism_type_id '
                    . 'FROM "ContactMechanismType" '
                    . "WHERE description = 'Primary Address';");
            $contact_mechanism_type_id = $query->row()->contact_mechanism_type_id;
        }
        if ($contact_mechanism_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put address
            } #else
            $database->query('INSERT INTO "ContactMechanism" '
                    . '(contact_mechanism_id, contact_mechanism_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING contact_mechanism_id;',
                    array($contact_mechanism_type_id));
            $contact_mechanism_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "PartyContactMechanism" '
                    . '(party_role_id, contact_mechanism_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT)',
                    array($party_role_id, $contact_mechanism_id));
            $this->counterpart->setField('contact_mechanism_id', $contact_mechanism_id);
        }
        if ($party_role_id == 0) {
            $query = $database->query('SELECT party_role_id FROM "PartyContactMechanism" '
                    . 'WHERE contact_mechanism_id = ?;', array($contact_mechanism_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $database->query('UPDATE "ContactMechanism" SET '
                . 'contact_mechanism_type_id = ? '
                . 'WHERE contact_mechanism_id = ?;',
                array($contact_mechanism_type_id, $contact_mechanism_id));
        $database->query('UPDATE "PartyContactMechanism" SET '
                . 'purpose_other = ? WHERE party_role_id = ? '
                . 'AND contact_mechanism_id = ?;',
                array($this->counterpart->getField('address_desc'),
                      $party_role_id, $contact_mechanism_id));
        $query = $database->query('SELECT postal_address_id FROM "PostalAddress" '
                . 'WHERE postal_address_id = ?;', array($contact_mechanism_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "PostalAddress" SET '
                    . '(address_1, address_2, city, state_id, postal_code) '
                    . '= (?, ?, ?, ?, ?) WHERE postal_address_id = ?;', array(
                            $this->counterpart->getField('address_1st_line'),
                            $this->counterpart->getField('address_2nd_line'),
                            $this->counterpart->getField('city'),
                            $this->counterpart->getField('state_id'),
                            $this->counterpart->getField('postal_code'),
                            $contact_mechanism_id
                    ));
        } else {
            $database->query('INSERT INTO "PostalAddress" '
                    . '(postal_address_id, address_1, address_2, city, state_id, postal_code) '
                    . 'VALUES (?, ?, ?, ?, ?, ?);', array(
                            $contact_mechanism_id,
                            $this->counterpart->getField('address_1st_line'),
                            $this->counterpart->getField('address_2nd_line'),
                            $this->counterpart->getField('city'),
                            $this->counterpart->getField('state_id'),
                            $this->counterpart->getField('postal_code')
                    ));
        }
        if ($postal_code_id != 0) {
            $database->query('UPDATE "PostalAddress" SET postal_code_id = ? '
                    . 'WHERE postal_address_id = ?;', array(
                            $postal_code_id, $contact_mechanism_id));
        }
        if ($city_id != 0) {
            $database->query('UPDATE "PostalAddress" SET city_id = ? '
                    . 'WHERE postal_address_id = ?;', array(
                            $city_id, $contact_mechanism_id));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $contact_mechanism_id = $this->counterpart->getField('contact_mechanism_id');
        $query = NULL;
        if ($contact_mechanism_id == 0) {
            $query = $database->query('SELECT contact_mechanism_id, '
                    . 'address_1 as address_1st_line, address_2 as address_2nd_line, '
                    . ' city, state_id, postal_code FROM "PartyContactMechanism" '
                    . 'INNER JOIN "PostalAddress" ON "PartyContactMechanism".contact_mechanism_id '
                    . '= "PostalAddress".postal_address_id '
                    . 'WHERE "PartyContactMechanism".party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT postal_address_id as contact_mechanism_id, '
                    . 'address_1 as address_1st_line, '
                    . 'address_2 as address_2nd_line,  city, '
                    . 'state_id, postal_code FROM "PostalAddress" '
                    . 'WHERE postal_address_id = ?;', array($contact_mechanism_id));
        }
        if ($query && $query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
            $query = $database->query('SELECT contact_mechanism_type_id FROM "ContactMechanism" '
                    . 'WHERE contact_mechanism_id = ?;',
                    array($this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('address_type', $query->row()->contact_mechanism_type_id);
            $query = $database->query('SELECT purpose_other FROM "PartyContactMechanism" '
                    . 'WHERE party_role_id = ? AND contact_mechanism_id = ?;',
                    array($party_role_id, $this->counterpart->getField('contact_mechanism_id')));
            $this->counterpart->setField('address_desc', $query->row()->purpose_other);
        }
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT contact_mechanism_id '
                . 'FROM "PartyContactMechanism" '
                . 'INNER JOIN "PostalAddress" '
                . 'ON "PartyContactMechanism".contact_mechanism_id '
                . '= "PostalAddress".postal_address_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $postal_addresses = array();
        foreach ($query->result() as $row) {
            $new_postal_address = new Postal_Address();
            $new_postal_address->setField('contact_mechanism_id',
                    $row->contact_mechanism_id);
            $new_postal_address_persistenace
                    = new Postal_Address_Persistence($new_postal_address);
            $new_postal_address_persistenace->getFromDatabase($database, $party_role_id);
            $postal_addresses[count($postal_addresses)]
                    = $new_postal_address_persistenace->getCounterpart();
        }
        #if there are no telecommunication numbers in the database return a blank one
        if (count($postal_addresses) == 0) {
            $postal_addresses[0] = new Postal_Address();
        }
        return $postal_addresses;
    }
}

/* End of file postal_address_persistence.php */
/* Location: ./application/libraries/postal_address_persistence.php */