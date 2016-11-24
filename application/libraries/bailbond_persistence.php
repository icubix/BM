<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BailBond_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $defendant_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return 'Empty';
        }
        $bond_date = $this->counterpart->getField('bond_date');
        if ($bond_date) {
            $bond_date = strtotime($bond_date);
            if ($bond_date) {
                $bond_date = date('Y-m-d', $bond_date);
            } else {
                return 'Invalid Bond Date format in BailBond. Use YYYY-MM-DD';
            }
        } else {
            $bond_date = NULL;
        }
        $bond_time = $this->counterpart->getField('bond_time');
        if ($bond_time) {
            $bond_time = strtotime($bond_time);
            if ($bond_time) {
                $bond_time = date('h:i A T', $bond_time);
                $this->counterpart->setField('bond_time', $bond_time);
            } else {
                return 'Invalid Bond Time format in BailBond. Use hh:MM meridian';
            }
        } else {
            $bond_time = NULL;
        }
        $bailbond_id = $this->counterpart->getField('bailbond_id');
        if ($this->counterpart->getField('defendant_id') != 0) {
            $defendant_id = $this->counterpart->getField('defendant_id');
        } else {
            $this->counterpart->setField('defendant_id', $defendant_id);
        }
        if ($bailbond_id == 0) {
            if ($defendant_id == 0) {
                return 'Error: Select Defendant again.';#No place to save bailbond
            } else {
                $database->query('INSERT INTO "BailBond" '
                        . '(defendant_id, bailbond_id, folio_number, '
                        . 'bond_date, bond_time, bond_amount, case_number, spn, '
                        . 'jail_location, from_date) '
                        . 'VALUES (?, DEFAULT, ?, ?, ?, ?, ?, ?, ?, DEFAULT) '
                        . 'RETURNING bailbond_id;',
                        array(
                                $defendant_id,
                                $this->counterpart->getField('folio_number'),
                                $bond_date,
                                $bond_time,
                                $this->counterpart->getField('bond_amount'),
                                $this->counterpart->getField('case_number'),
                                $this->counterpart->getField('spn'),
                                $this->counterpart->getField('jail_location')
                        ));
                $bailbond_id = $database->query('SELECT LASTVAL()')->row()->lastval;
                $this->counterpart->setField('bailbond_id', $bailbond_id);
            }
        } else {
            $database->query('UPDATE "BailBond" SET '
                    . '(defendant_id, folio_number, '
                    . 'bond_date, bond_time, bond_amount, case_number, spn, '
                    . 'jail_location) = (?, ?, ?, ?, ?, ?, ?, ?) '
                    . 'WHERE bailbond_id = ?;',
                        array(
                                $defendant_id,
                                $this->counterpart->getField('folio_number'),
                                $bond_date,
                                $bond_time,
                                $this->counterpart->getField('bond_amount'),
                                $this->counterpart->getField('case_number'),
                                $this->counterpart->getField('spn'),
                                $this->counterpart->getField('jail_location'),
                                $bailbond_id
                        ));
        }
        if ($this->counterpart->getField('charge')
                && !$this->counterpart->getField('charge')->isEmpty()) {
            $charge_persistence = new Charge_Persistence(
                    $this->counterpart->getField('charge'));
            $charge_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('charge', $charge_persistence->getCounterpart());
            $charge_id = $charge_persistence->getCounterpart()->getField('charge_id');
            $database->query('UPDATE "BailBond" SET '
                    . '(charge_id) = (?) WHERE bailbond_id = ?;',
                    array($charge_id, $bailbond_id));
        }
        if ($this->counterpart->getField('power')
                && !$this->counterpart->getField('power')->isEmpty()) {
            $power_persistence = new Power_Persistence(
                    $this->counterpart->getField('power'));
            $power_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('power', $power_persistence->getCounterpart());
            $power_id = $power_persistence->getCounterpart()->getField('power_id');
            $database->query('UPDATE "BailBond" SET '
                    . '(power_id) = (?) WHERE bailbond_id = ?;',
                    array($power_id, $bailbond_id));
        }
        $indemnitor_id = $this->counterpart->getField('indemnitor_id');
        if ($indemnitor_id != 0) {
            $query = $database->query('SELECT party_role_id FROM "Indemnitor" '
                    . 'WHERE party_role_id = ?', array($indemnitor_id));
            if ($query->num_rows() > 0) {
                $query = $database->query('SELECT indemnitor_id from "IndemnitorBailBond" '
                        . 'WHERE bailbond_id = ?;', array($bailbond_id));
                if ($query->num_rows() > 0) {
                    $database->query('UPDATE "IndemnitorBailBond" SET '
                            . '(indemnitor_id) = (?) WHERE bailbond_id = ?;',
                            array($indemnitor_id, $bailbond_id));
                } else {
                    $database->query('INSERT INTO "IndemnitorBailBond" '
                            . '(bailbond_id, indemnitor_id) VALUES (?, ?);',
                            array($bailbond_id, $indemnitor_id));
                }
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $bailbond_id) {
        $this->counterpart = new BailBond_Instance();
        $query = $database->query('SELECT defendant_id, folio_number, '
                . 'bond_date, bond_time, bond_amount, case_number, spn, '
                . 'charge_id, jail_location, power_id FROM "BailBond" '
                . 'WHERE bailbond_id = ?;', array($bailbond_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('defendant_id', $query->row()->defendant_id);
            $this->counterpart->setField('bailbond_id', $bailbond_id);
            $this->counterpart->setField('folio_number', $query->row()->folio_number);
            $this->counterpart->setField('bond_date', $query->row()->bond_date);
            $bond_time = $query->row()->bond_time;
            if ($bond_time) {
                $bond_time = strtotime($bond_time);
                if ($bond_time) {
                    $bond_time = date('h:i A T', $bond_time);
                }
            }
            $this->counterpart->setField('bond_time', $bond_time);
            $this->counterpart->setField('bond_amount', $query->row()->bond_amount);
            $this->counterpart->setField('case_number', $query->row()->case_number);
            $this->counterpart->setField('spn', $query->row()->spn);
            $charge_id = $query->row()->charge_id;
            $this->counterpart->setField('jail_location', $query->row()->jail_location);
            $power_id = $query->row()->power_id;
            #charge child
            $charge_persistence = new Charge_Persistence(
                    $this->counterpart->getField('charge'));
            $charge_persistence->getFromDatabase($database, $charge_id);
            $this->counterpart->setField('charge',
                    $charge_persistence->getCounterpart());
            #power child
            $power_persistence = new Power_Persistence(
                    $this->counterpart->getField('power'));
            $power_persistence->getFromDatabase($database, $power_id);
            $this->counterpart->setField('power',
                    $power_persistence->getCounterpart());
        }
        $query = $database->query('SELECT indemnitor_id from "IndemnitorBailBond" '
                . 'WHERE bailbond_id = ?;', array($bailbond_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('indemnitor_id',
                    $query->row()->indemnitor_id);
        }
    }

    /* depricated : moved to defendant model
    public function getIndemnitorList($database, $defendant_id) {
        $returnArray = array(array('key' => 0, 'name' => 'None'));
        if (!$defendant_id) {
            return $returnArray;
        }
        $query = $database->query('SELECT "IndemnitorAttribute".indemnitor_id, '
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
            array_push($returnArray,
                    array('key' => $row['indemnitor_id'], 'name' => $fullName));
        }
        return $returnArray;
    }*/
}

/* End of file bailbond_persistence.php */
/* Location: ./application/libraries/bailbond_persistence.php */