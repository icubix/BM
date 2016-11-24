<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Amount_Persistence extends Account_Persistence {
    private function makeCurrency($string) {
        $newstring = "";

        $array = str_split($string);
        foreach($array as $char) {
            if (($char >= '0' && $char <= '9') || $char == '.') {
                $newstring .= $char;
            }
        }

        return $newstring;
    }

    public function saveToDatabase($database, $account_entry_type, $account_entry_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return 'Empty';
        }
        $amount = $this->counterpart->getField('amount');
        if ($amount == '') {
            $amount = NULL;
        } elseif ($amount && !is_numeric($amount)) {
            return 'Invalid Amount.  Use only numbers.';
        }
        $percentage = $this->counterpart->getField('percentage');
        if ($percentage == '') {
            $percentage = NULL;
        } elseif ($percentage && !is_numeric($percentage)) {
            return 'Invalid percentage.  Use only numbers.';
        }
        $target = $this->counterpart->getField('target');
        if ($target == '') {
            $target = NULL;
        } elseif ($target && !is_numeric($target)) {
            return 'Error: Invalid Target.';
        }
        $inflation = $this->counterpart->getField('inflation');
        if ($inflation == '') {
            $inflation = NULL;
        } elseif ($inflation && !is_numeric($inflation)) {
            return 'Invalid Inflation.  Use only numbers.';
        }
        if (!$amount && !$percentage) {
            return 'Enter amount or percentage.';
        }
        $amount_type_id = $this->counterpart->getField('amount_type_id');
        if (!(!$amount_type_id || !is_numeric($amount_type_id) || $amount_type_id != 0)) {
            return 'Error: Invalid amount type';
        }
        $entry_amount_id = $this->counterpart->getField('entry_amount_id');
        if ($entry_amount_id == 0 && $account_entry_id != 0) {
            if (in_array($account_entry_type, array('AccountEntry', 'AccountEntryDefault',
                            'AccountRecurringEntry', 'AccountRecurringDefault'))) {
                $idsForGivenTable = array('AccountEntry' => 'account_entry_id',
                                   'AccountEntryDefault' => 'entry_default_id',
                                 'AccountRecurringEntry' => 'recurring_entry_id',
                               'AccountRecurringDefault' => 'recurring_default_id');
                $query = $database->query('SELECT entry_amount_id FROM "' . $account_entry_type
                        . '" WHERE ' . $idsForGivenTable[$account_entry_type] . ' = ?',
                        array($account_entry_id));
                if ($query->num_rows > 0) {
                    $entry_amount_id = $query->row()->entry_amount_id;
                    $this->counterpart->setField('entry_amount_id', $entry_amount_id);
                }
            }
        }
        #we check this a second time incase we found an entry_amount_id using account_entry_id
        if ($entry_amount_id == 0) {
            $database->query('INSERT INTO "AccountEntryAmount" '
                    . '(amount_type_id, amount, percentage, target, inflation) '
                    . 'VALUES (?, ?, ?, ?, ?) '
                    . 'RETURNING entry_amount_id;',
                    array(
                                $amount_type_id,
                                $amount,
                                $percentage,
                                $target,
                                $inflation
                    ));
            $entry_amount_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $this->counterpart->setField('entry_amount_id', $entry_amount_id);
        } else {
            $database->query('UPDATE "AccountEntryAmount" SET '
                    . '(amount_type_id, amount, percentage, target, inflation) '
                    . '= (?, ?, ?, ?, ?) '
                    . 'WHERE entry_amount_id = ?;',
                        array(
                                $amount_type_id,
                                $amount,
                                $percentage,
                                $target,
                                $inflation,
                                $entry_amount_id
                        ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $entry_amount_id) {
        $this->counterpart = new Account_Amount();
        $query = $database->query('SELECT amount_type_id, amount, '
                . 'percentage, target, inflation FROM "AccountEntryAmount" '
                . 'WHERE entry_amount_id = ?;', array($entry_amount_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('entry_amount_id', $entry_amount_id);
            $this->counterpart->setField('amount_type_id', $query->row()->amount_type_id);
            $this->counterpart->setField('amount', $query->row()->amount);
            $this->counterpart->setField('percentage', $query->row()->percentage);
            $this->counterpart->setField('target', $query->row()->target);
            $this->counterpart->setField('inflation', $query->row()->inflation);
        }
    }
}

/* End of file account_amount__persistence.php */
/* Location: ./application/libraries/account_amount__persistence.php */