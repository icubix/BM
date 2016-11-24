<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charge_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $charge_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if ($this->counterpart->getField('charge_id') == 0) {
            if ($charge_id == 0) {
                $database->query('INSERT INTO "Charge" '
                        . '(charge_id, charge_desc, charge_type) '
                        . 'VALUES (DEFAULT, ?, ?) RETURNING charge_id;',
                        array($this->counterpart->getField('charge_desc'),
                            $this->counterpart->getField('charge_type')
                        ));
                $charge_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            } else {
                $this->counterpart->setField('charge_id', $charge_id);
                $database->query('UPDATE "Charge" SET '
                        . '(charge_desc, charge_type) = (?, ?) '
                        . 'WHERE charge_id = ?;',
                        array($this->counterpart->getField('charge_desc'),
                            $this->counterpart->getField('charge_type'),
                            $charge_id
                        ));
            }
            $this->counterpart->setField('charge_id', $charge_id);
        } else {
            $charge_id = $this->counterpart->getField('charge_id');
            $database->query('UPDATE "Charge" SET '
                    . '(charge_desc, charge_type) = (?, ?) '
                    . 'WHERE charge_id = ?;',
                    array($this->counterpart->getField('charge_desc'),
                        $this->counterpart->getField('charge_type'),
                        $charge_id
                    ));
        }
        if ($this->counterpart->getField('court')
                && !$this->counterpart->getField('court')->isEmpty()) {
            $court_persistence = new Court_Persistence($this->counterpart->getField('court'));
            $court_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('court', $court_persistence->getCounterpart());
            $court_id = $court_persistence->getCounterpart()->getField('party_role_id');
            $query = $database->query('SELECT * FROM "CourtCharge" '
                    . 'WHERE court_id = ? AND charge_id = ?;',
                    array($court_id, $charge_id));
            if (count($query->result()) < 1) {
                $database->query('INSERT INTO "CourtCharge" '
                        . '(court_id, charge_id) VALUES (?, ?);',
                        array($court_id, $charge_id));
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $charge_id) {
        $query = $database->query('SELECT charge_desc, charge_type FROM "Charge" '
                . 'WHERE charge_id = ?;', array($charge_id));
        $result = $query->result_array();
        if (count($result) > 0) {
            foreach($result[0] as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
        $query = $database->query('SELECT court_id FROM "CourtCharge" '
                . 'WHERE charge_id = ?;', array($charge_id));
        if ($query->num_rows() > 0) {
            $court_persistence = new Court_Persistence(
                    $this->counterpart->getField('court'));
            $court_persistence->getFromDatabase(
                    $database, $query->row()->court_id);
            $this->counterpart->setField('court',
                    $court_persistence->getCounterpart());
        }
    }
}

/* End of file charge_persistence.php */
/* Location: ./application/libraries/charge_persistence.php */