<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_Attribute_Persistence extends Domain_Persistence {
    #insert date seperately
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $query = $database->query('SELECT "VehicleAttribute".attribute_id '
                    . 'FROM "DefendantAttribute" INNER JOIN "VehicleAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "VehicleAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
            if ($query->num_rows() > 0) {
                $attribute_id = $query->row()->attribute_id;
            } else {
                $query = $database->query('SELECT attribute_type_id from "AttributeType" '
                        . "WHERE description = 'Vehicle';");
                $database->query('INSERT INTO "Attribute" '
                        . '(attribute_id, attribute_type_id) '
                        . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                        array($query->row()->attribute_type_id));
                $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
                $database->query('INSERT INTO "DefendantAttribute" '
                        . '(attribute_id, party_role_id, from_date) '
                        . 'VALUES (?, ?, DEFAULT)',
                        array($attribute_id, $party_role_id));
            }
            $this->counterpart->setField('attribute_id', $attribute_id);
        }
        if ($party_role_id == 0) { #this should not happen here
            $query = $database->query('SELECT party_role_id FROM "DefendantAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $query = $database->query('SELECT attribute_id '
                . 'FROM "VehicleAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "VehicleAttribute" SET '
                    . '(year_built, make, model, color, tag_number, state_id, '
                    . 'amount_owed, lien_holder, insurer, vehicle_agent) '
                    . '= (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) WHERE attribute_id = ?;',
                    array(
                            $this->counterpart->getField('year_built'),
                            $this->counterpart->getField('make'),
                            $this->counterpart->getField('model'),
                            $this->counterpart->getField('color'),
                            $this->counterpart->getField('tag_number'),
                            $this->counterpart->getField('state_id'),
                            $this->counterpart->getField('amount_owed'),
                            $this->counterpart->getField('lien_holder'),
                            $this->counterpart->getField('insurer'),
                            $this->counterpart->getField('vehicle_agent'),
                            $attribute_id
                    ));
        } else {
            $database->query('INSERT INTO "VehicleAttribute" '
                    . '(attribute_id, year_built, make, model, color, tag_number, state_id, '
                    . 'amount_owed, lien_holder, insurer, vehicle_agent) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $this->counterpart->getField('year_built'),
                            $this->counterpart->getField('make'),
                            $this->counterpart->getField('model'),
                            $this->counterpart->getField('color'),
                            $this->counterpart->getField('tag_number'),
                            $this->counterpart->getField('state_id'),
                            $this->counterpart->getField('amount_owed'),
                            $this->counterpart->getField('lien_holder'),
                            $this->counterpart->getField('insurer'),
                            $this->counterpart->getField('vehicle_agent')
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $attribute_id = $this->counterpart->getField('attribute_id');
        $query = NULL;
        if ($attribute_id == 0) {
            $query = $database->query('SELECT "VehicleAttribute".attribute_id, '
                    . 'year_built, make, model, color, tag_number, state_id, '
                    . 'amount_owed, lien_holder, insurer, vehicle_agent '
                    . 'FROM "VehicleAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "VehicleAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT attribute_id, '
                    . 'year_built, make, model, color, tag_number, state_id, '
                    . 'amount_owed, lien_holder, insurer, vehicle_agent '
                    . 'FROM "VehicleAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
    }
}

/* End of file vehicle_attribute_persistence.php */
/* Location: ./application/libraries/vehicle_attribute_persistence.php */