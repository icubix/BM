<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Recurring_Persistence extends Account_Persistence {
    public function saveToDatabase($database, $account_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return 'Empty';
        }
        if ($account_id == 0) {
            return 'Invalid Account ID: Select Defendant again.';
        }
        $start_date = $this->counterpart->getField('start_date');
        if ($start_date) {
            $start_date = strtotime($start_date);
            if ($start_date) {
                $start_date = date('Y-m-d', $start_date);
            } else {
                return 'Invalid Start Date format. Use YYYY-MM-DD';
            }
        } else {
            $start_date = NULL;
        }
        $end_date = $this->counterpart->getField('end_date');
        if ($end_date) {
            $end_date = strtotime($end_date);
            if ($end_date) {
                $end_date = date('Y-m-d', $end_date);
            } else {
                return 'Invalid End Date format. Use YYYY-MM-DD';
            }
        } else {
            $end_date = NULL;
        }
        $interval_number = $this->counterpart->getField('interval_number');
        if ($interval_number && !is_numeric($interval_number)) {
            return 'Invalid Appy Interval number.  Use only numbers.';
        }
        $interval_scale = $this->counterpart->getField('interval_scale');
        if ($interval_scale == 'WEEK') {
            $interval_number *= 7;
            $interval_scale = 'DAY';
        }
        if ($interval_scale && !in_array($interval_scale, array('YEAR', 'MONTH', 'DAY', 'HOUR', 'MINUTE', 'SECOND'))) {
            return 'Error: Invalid Apply Interval scale.';
        }
        if ($interval_number && $interval_scale) {
            $apply_interval = $interval_number . ' ' . $interval_scale;
        } else {
            return 'Apply Interval required.';
        }
        $account_amount = $this->counterpart->getField('account_amount');
        $recurring_entry_id = $this->counterpart->getField('recurring_entry_id');
        if ($account_amount && !$account_amount->isEmpty()) {
            $account_amount_persistence = new Account_Amount_Persistence(
                        $account_amount);
            $error = $account_amount_persistence->saveToDatabase($database, 'AccountRecurringEntry', $recurring_entry_id);
            $account_amount = $account_amount_persistence->getCounterpart();
            $this->counterpart->setField('account_amount', $account_amount);
            if ($error) {
                return $error;
            }
            $entry_amount_id = $account_amount->getField('entry_amount_id');
        } else {
            return 'Enter an Amount or Percentage.';
        }
        if ($recurring_entry_id == 0) {
            $database->query('INSERT INTO "AccountRecurringEntry" '
                    . '(account_id, entry_type_id, entry_amount_id, description, '
                    . 'last_apply_date, apply_interval, start_date, end_date) '
                    . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?) '
                    . 'RETURNING recurring_entry_id;',
                    array(
                            $account_id,
                            $this->counterpart->getField('entry_type_id'),
                            $entry_amount_id,
                            $this->counterpart->getField('description'),
                            $start_date,
                            $apply_interval,
                            $start_date,
                            $end_date
                    ));
            $recurring_entry_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $this->counterpart->setField('recurring_entry_id', $recurring_entry_id);
        } else {
            $database->query('UPDATE "AccountRecurringEntry" SET '
                    . '(account_id, entry_type_id, entry_amount_id, description, '
                    . 'last_apply_date, apply_interval, start_date, end_date) '
                    . '= (?, ?, ?, ?, ?, ?, ?, ?) '
                    . 'WHERE recurring_entry_id = ?;',
                        array(
                                $account_id,
                                $this->counterpart->getField('entry_type_id'),
                                $entry_amount_id,
                                $this->counterpart->getField('description'),
                                $start_date,
                                $apply_interval,
                                $start_date,
                                $end_date,
                                $recurring_entry_id
                        ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $recurring_entry_id) {
        $this->counterpart = new Account_Recurring();
        $query = $database->query('SELECT entry_type_id, entry_amount_id, description, '
            . 'last_apply_date, apply_interval, start_date, end_date FROM "AccountRecurringEntry" '
            . 'WHERE recurring_entry_id = ?;', array($recurring_entry_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('recurring_entry_id', $recurring_entry_id);
            $this->counterpart->setField('entry_type_id', $query->row()->entry_type_id);
            $account_amount = new Account_Amount();
            $account_amount_persistence = new Account_Amount_Persistence($account_amount);
            $account_amount_persistence->getFromDatabase($database, $query->row()->entry_amount_id);
            $account_amount = $account_amount_persistence->getCounterpart();
            $this->counterpart->setField('account_amount', $account_amount);
            $this->counterpart->setField('description', $query->row()->description);
            $this->counterpart->setField('last_apply_date', $query->row()->last_apply_date);
            $apply_interval = $query->row()->apply_interval;
            if ($apply_interval) {
                $explodedInterval = explode(' ', $apply_interval);
                if ($explodedInterval && count($explodedInterval) > 1 && is_numeric($explodedInterval[0])) {
                        $interval_number = $explodedInterval[0];
                        $interval_scale = $explodedInterval[1];
                }
            } else {
                $interval_number = NULL;
                $interval_scale = NULL;
            }
            if ($interval_scale == 'year') {
                $interval_scale = 'YEAR';
            }
            if ($interval_scale == 'mon') {
                $interval_scale = 'MONTH';
            }
            if ($interval_scale == 'day') {
                $interval_scale = 'DAY';
            }
            $this->counterpart->setField('interval_number', $interval_number);
            $this->counterpart->setField('interval_scale', $interval_scale);
            $this->counterpart->setField('start_date', $query->row()->start_date);
            $this->counterpart->setField('end_date', $query->row()->end_date);
        }
    }
}

/* End of file account_recurring__persistence.php */
/* Location: ./application/libraries/account_recurring__persistence.php */