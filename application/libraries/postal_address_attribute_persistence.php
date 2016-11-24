<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $error = (new Postal_Address_Persistence(
                    $this->counterpart->getField('postal_address')
                ))->saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $contact_mechanism_id = $this->counterpart->getField('postal_address')
                ->getField('contact_mechanism_id');
        if (!$contact_mechanism_id || $contact_mechanism_id == 0) {
            return 'Address line 1 required';
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if (!($attribute_id)) {
            $query = $database->query('SELECT attribute_type_id FROM '
                    . '"AttributeType" ' . "WHERE description = 'PostalAddressInfo';");
            $attribute_type_id = $query->row()->attribute_type_id;
            $database->query('INSERT INTO "Attribute" '
                    . '(attribute_id, attribute_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                    array($attribute_type_id));
            $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "DefendantAttribute" '
                    . '(attribute_id, party_role_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT);',
                    array($attribute_id, $party_role_id));
            $database->query('INSERT INTO "PostalAddressAttribute" '
                    . '(attribute_id, postal_address_id, rent_not_own, '
                    . 'landlord_mortgage) VALUES (?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $contact_mechanism_id,
                            $this->counterpart->getField('rent_not_own'),
                            $this->counterpart->getField('landlord_mortgage')
                    ));
            $this->counterpart->setField('attribute_id', $attribute_id);
        } else {
            $database->query('UPDATE "PostalAddressAttribute" SET '
                    . '(postal_address_id, rent_not_own, '
                    . 'landlord_mortgage) = (?, ?, ?) '
                    . 'WHERE attribute_id = ?;',
                    array(
                            $contact_mechanism_id,
                            $this->counterpart->getField('rent_not_own'),
                            $this->counterpart->getField('landlord_mortgage'),
                            $attribute_id
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id) {
            $query = $database->query('SELECT attribute_id, '
                    . 'rent_not_own, landlord_mortgage, '
                    . 'postal_address_id FROM "PostalAddressAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        } else {
            $query = $database->query('SELECT "PostalAddressAttribute".attribute_id, '
                    . 'rent_not_own, landlord_mortgage, '
                    . 'postal_address_id FROM "DefendantAttribute" '
                    . 'INNER JOIN "PostalAddressAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "PostalAddressAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
        }
        $postal_address_id = 0;
        if ($query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                if ($key == 'postal_address_id') {
                    $postal_address_id = $value;
                }
                $this->counterpart->setField($key, $value);
            }
        }
        $postal_address = $this->counterpart->getField('postal_address');
        if ($postal_address_id) {
            $postal_address->setField('contact_mechanism_id', $postal_address_id);
        }
        $postal_address_persistence = new Postal_Address_Persistence($postal_address);
        $postal_address_persistence->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('postal_address',
                $postal_address_persistence->getCounterpart());
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" '
                . 'INNER JOIN "PostalAddressAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "PostalAddressAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $postal_address_attributes = array();
        foreach ($query->result() as $row) {
            $new_postal_address_attribute = new Postal_Address_Attribute();
            $new_postal_address_attribute->setField('attribute_id',
                    $row->attribute_id);
            $new_postal_address_attribute_persistenace
                    = new Postal_Address_Attribute_Persistence($new_postal_address_attribute);
            $new_postal_address_attribute_persistenace->getFromDatabase($database, $party_role_id);
            $postal_address_attributes[count($postal_address_attributes)]
                    = $new_postal_address_attribute_persistenace->getCounterpart();
        }
        #if there are no postal address attributes in the database return a blank one
        if (count($postal_address_attributes) == 0) {
            $postal_address_attributes[0] = new Postal_Address_Attribute();
        }
        return $postal_address_attributes;
    }
}

/* End of file postal_address_attribute_persistence.php */
/* Location: ./application/libraries/postal_address_attribute_persistence.php */