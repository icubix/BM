<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $power_id = 0) {
        // print_r($this->counterpart);
        // print_r('kali');
        // print_r($this->counterpart->getField('power_number'));
        

        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        if ($this->counterpart->getField('power_id') == 0) {
            if ($power_id == 0) {
                $database->query('INSERT INTO "Power" '
                        . '(power_id, power_number, power_amount) '
                        . 'VALUES (DEFAULT, ?, ?) RETURNING power_id;',
                        array($this->counterpart->getField('power_number'),
                            $this->counterpart->getField('power_amount')
                        ));
                $power_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            } else {
                $database->query('UPDATE "Power" SET '
                        . '(power_number, power_amount) = (?, ?) '
                        . 'WHERE power_id = ?;',
                        array($this->counterpart->getField('power_number'),
                                $this->counterpart->getField('power_amount'),
                                $power_id
                        ));
            }
            $this->counterpart->setField('power_id', $power_id);
        } else {
            $power_id = $this->counterpart->getField('power_id');
            $database->query('UPDATE "Power" SET '
                    . '(power_number, power_amount) = (?, ?) '
                    . 'WHERE power_id = ?;',
                    array($this->counterpart->getField('power_number'),
                            $this->counterpart->getField('power_amount'),
                            $power_id
                    ));
        }
        if ($this->counterpart->getField('surety')
                && !$this->counterpart->getField('surety')->isEmpty()) {
            $surety_persistence = new Surety_Persistence(
                    $this->counterpart->getField('surety'));
            $surety_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('surety', $surety_persistence->getCounterpart());
            $surety_id = $surety_persistence->getCounterpart()->getField('party_role_id');
            $database->query('UPDATE "Power" SET '
                    . '(surety_id) = (?) WHERE power_id = ?;',
                    array($surety_id, $power_id));
        }
        if ($this->counterpart->getField('agent')
                && !$this->counterpart->getField('agent')->isEmpty()) {
            $agent_persistence = new Agent_Persistence(
                    $this->counterpart->getField('agent'));
            $agent_persistence->saveToDatabase($database, 0);
            $this->counterpart->setField('agent', $agent_persistence->getCounterpart());
            $agent_id = $agent_persistence->getCounterpart()->getField('party_role_id');
            $database->query('UPDATE "Power" SET '
                    . '(agent_id) = (?) WHERE power_id = ?;',
                    array($agent_id, $power_id));
        }
        return NULL;
    }

    public function getFromDatabase($database, $power_id) {
        $query = $database->query('SELECT power_id, power_number, power_amount, '
                . 'surety_id, agent_id FROM "Power" '
                . 'WHERE power_id = ?;', array($power_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('power_id', $query->row()->power_id);
            $this->counterpart->setField('power_number', $query->row()->power_number);
            $this->counterpart->setField('power_amount', $query->row()->power_amount);
            $surety_id = $query->row()->surety_id;
            $agent_id = $query->row()->agent_id;
            #surety child
            $surety_persistence = new Surety_Persistence(
                    $this->counterpart->getField('surety'));
            $surety_persistence->getFromDatabase($database, $surety_id);
            $this->counterpart->setField('surety',
                    $surety_persistence->getCounterpart());
            #agent child
            $agent_persistence = new Agent_Persistence(
                    $this->counterpart->getField('agent'));
            $agent_persistence->getFromDatabase($database, $agent_id);
            $this->counterpart->setField('agent',
                    $agent_persistence->getCounterpart());
        }
    }

    public function getPowers($database){
        $query = $database->query('SELECT power_id,power_number,power_amount from "Power"');
        if($query->num_rows() > 0)
        {
            return $query->result_array();
        }

    }
}

/* End of file power_persistence.php */
/* Location: ./application/libraries/power_persistence.php */