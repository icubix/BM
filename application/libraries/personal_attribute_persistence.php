<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        $date_of_birth = $this->counterpart->getField('date_of_birth');
        if ($date_of_birth) {
            $date_of_birth = strtotime($date_of_birth);
            if ($date_of_birth) {
                $date_of_birth = date('Y-m-d', $date_of_birth);
            } else {
                return 'Invalid Date of Birth format in Personal Info. Use YYYY-MM-DD';
            }
        } else {
            $date_of_birth = NULL;
        }
        $driver_license_exp = $this->counterpart->getField('driver_license_exp');
        if ($driver_license_exp) {
            $driver_license_exp = strtotime($driver_license_exp);
            if ($driver_license_exp) {
                $driver_license_exp = date('Y-m-d', $driver_license_exp);
            } else {
                return 'Invalid Driver License date format in Personal Info. Use YYYY-MM-DD';
            }
        } else {
            $driver_license_exp = NULL;
        }
        $passport_issued = $this->counterpart->getField('passport_issued');
        if ($passport_issued) {
            $passport_issued = strtotime($passport_issued);
            if ($passport_issued) {
                $passport_issued = date('Y-m-d', $passport_issued);
            } else {
                return 'Invalid Passport Issued Date format in Personal Info. Use YYYY-MM-DD';
            }
        } else {
            $passport_issued = NULL;
        }
        $passport_expires = $this->counterpart->getField('passport_expires');
        if ($passport_expires) {
            $passport_expires = strtotime($passport_expires);
            if ($passport_expires) {
                $passport_expires = date('Y-m-d', $passport_expires);
            } else {
                return 'Invalid Passport Experation Date format in Personal Info. Use YYYY-MM-DD';
            }
        } else {
            $passport_expires = NULL;
        }
        $defendant_persistence = new Defendant_Persistence(
                    $this->counterpart->getField('defendant'));
        $error = $defendant_persistence->saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $this->counterpart->setField('defendant',
                $defendant_persistence->getCounterpart());
        if ($party_role_id == 0) {
            $party_role_id = $this->counterpart->getField('defendant')
                    ->getField('party_role_id');
        }
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if (!($attribute_id)) {
            $query = $database->query('SELECT "PersonalAttribute".attribute_id '
                    . 'FROM "DefendantAttribute" '
                    . 'INNER JOIN "PersonalAttribute" '
                    . 'ON "DefendantAttribute".attribute_id '
                    . '= "PersonalAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;',
                    array($party_role_id));
            if ($query->num_rows() > 0) {
                $attribute_id = $query->row()->attribute_id;
            }
        }
        if (!($attribute_id)) {
            $query = $database->query('SELECT attribute_type_id FROM '
                    . '"AttributeType" ' . "WHERE description = 'PersonalInfo';");
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
            $database->query('INSERT INTO "PersonalAttribute" '
                    . '(attribute_id, aliases, date_of_birth, '
                    . 'place_of_birth, height, weight, eyes, '
                    . 'hair, race, id_marks, '
                    . 'driver_license_no, driver_license_state_id, '
                    . 'driver_license_exp, passport_no, passport_country, '
                    . 'passport_issued, passport_expires, arrests_for, '
                    . 'arrest_loc, on_probation, probation_location, '
                    . 'probation_officer) VALUES '
                    . '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $this->counterpart->getField('aliases'),
                            $date_of_birth,
                            $this->counterpart->getField('place_of_birth'),
                            $this->counterpart->getField('height'),
                            $this->counterpart->getField('weight'),
                            $this->counterpart->getField('eyes'),
                            $this->counterpart->getField('hair'),
                            $this->counterpart->getField('race'),
                            $this->counterpart->getField('id_marks'),
                            $this->counterpart->getField('driver_license_no'),
                            $this->counterpart->getField('driver_license_state_id'),
                            $driver_license_exp,
                            $this->counterpart->getField('passport_no'),
                            $this->counterpart->getField('passport_country'),
                            $passport_issued,
                            $passport_expires,
                            $this->counterpart->getField('arrests_for'),
                            $this->counterpart->getField('arrest_loc'),
                            $this->counterpart->getField('on_probation'),
                            $this->counterpart->getField('probation_location'),
                            $this->counterpart->getField('probation_officer')
                    ));
        } else {
            $database->query('UPDATE "PersonalAttribute" SET '
                    . '(aliases, date_of_birth, '
                    . 'place_of_birth, height, weight, eyes, '
                    . 'hair, race, id_marks, '
                    . 'driver_license_no, driver_license_state_id, '
                    . 'driver_license_exp, passport_no, passport_country, '
                    . 'passport_issued, passport_expires, arrests_for, '
                    . 'arrest_loc, on_probation, probation_location, '
                    . 'probation_officer) = '
                    . '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) '
                    . 'WHERE attribute_id = ?;',
                    array(
                            $this->counterpart->getField('aliases'),
                            $date_of_birth,
                            $this->counterpart->getField('place_of_birth'),
                            $this->counterpart->getField('height'),
                            $this->counterpart->getField('weight'),
                            $this->counterpart->getField('eyes'),
                            $this->counterpart->getField('hair'),
                            $this->counterpart->getField('race'),
                            $this->counterpart->getField('id_marks'),
                            $this->counterpart->getField('driver_license_no'),
                            $this->counterpart->getField('driver_license_state_id'),
                            $driver_license_exp,
                            $this->counterpart->getField('passport_no'),
                            $this->counterpart->getField('passport_country'),
                            $passport_issued,
                            $passport_expires,
                            $this->counterpart->getField('arrests_for'),
                            $this->counterpart->getField('arrest_loc'),
                            $this->counterpart->getField('on_probation'),
                            $this->counterpart->getField('probation_location'),
                            $this->counterpart->getField('probation_officer'),
                            $attribute_id
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $defendant_persistence = new Defendant_Persistence(
                    $this->counterpart->getField('defendant'));
        $defendant_persistence->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('defendant', $defendant_persistence->getCounterpart());
        $query = $database->query('SELECT "PersonalAttribute".attribute_id, '
                . 'aliases, date_of_birth, '
                . 'place_of_birth, height, weight, eyes, '
                . 'hair, race, id_marks, '
                . 'driver_license_no, driver_license_state_id, '
                . 'driver_license_exp, passport_no, passport_country, '
                . 'passport_issued, passport_expires, arrests_for, '
                . 'arrest_loc, on_probation, probation_location, '
                . 'probation_officer '
                . 'FROM "DefendantAttribute" '
                . 'INNER JOIN "PersonalAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "PersonalAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        if ($query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
    }
}

/* End of file personal_attribute_persistence.php */
/* Location: ./application/libraries/personal_attribute_persistence.php */