<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Entry_Persistence extends Account_Persistence {
    public function saveToDatabase($database, $account_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return 'Empty';
        }
        if ($account_id == 0) {
            return 'Invalid Account ID: Select Defendant again.';
        }
        $effective_date = $this->counterpart->getField('effective_date');
        if ($effective_date) {
            $effective_date = strtotime($effective_date);
            if ($effective_date) {
                $effective_date = date('Y-m-d', $effective_date);
            } else {
                return 'Invalid effective date format. Use YYYY-MM-DD';
            }
        } else {
            $effective_date = NULL;
        }
        $account_amount = $this->counterpart->getField('account_amount');
        $account_entry_id = $this->counterpart->getField('account_entry_id');
        if ($account_amount && !$account_amount->isEmpty()) {
            $account_amount_persistence = new Account_Amount_Persistence(
                        $account_amount);
            $error = $account_amount_persistence->saveToDatabase($database, 'AccountEntry', $account_entry_id);
            $account_amount = $account_amount_persistence->getCounterpart();
            $this->counterpart->setField('account_amount', $account_amount);
            if ($error) {
                return $error;
            }
            $entry_amount_id = $account_amount->getField('entry_amount_id');
        } else {
            return 'Enter an Amount or Percentage.';
        }
        if ($account_entry_id == 0) {
            $database->query('INSERT INTO "AccountEntry" '
                    . '(account_id, entry_type_id, entry_amount_id, description, effective_date) '
                    . 'VALUES (?, ?, ?, ?, ?) '
                    . 'RETURNING account_entry_id;',
                    array(
                            $account_id,
                            $this->counterpart->getField('entry_type_id'),
                            $entry_amount_id,
                            $this->counterpart->getField('description'),
                            $this->counterpart->getField('effective_date'),
                            $this->counterpart->getField('inflation')
                    ));
            $account_entry_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $this->counterpart->setField('account_entry_id', $account_entry_id);
        } else {
            $database->query('UPDATE "AccountEntry" SET '
                    . '(account_id, entry_type_id, entry_amount_id, description, effective_date) '
                    . '= (?, ?, ?, ?, ?) '
                    . 'WHERE account_entry_id = ?;',
                        array(
                                $account_id,
                                $this->counterpart->getField('entry_type_id'),
                                $entry_amount_id,
                                $this->counterpart->getField('description'),
                                $this->counterpart->getField('effective_date'),
                                $account_entry_id
                        ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $account_entry_id) {
        $this->counterpart = new Account_Entry();
        $query = $database->query('SELECT entry_type_id, entry_amount_id, '
                . 'description, effective_date FROM "AccountEntry" '
                . 'WHERE account_entry_id = ?;', array($account_entry_id));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('account_entry_id', $account_entry_id);
            $this->counterpart->setField('entry_type_id', $query->row()->entry_type_id);
            $account_amount = new Account_Amount();
            $account_amount_persistence = new Account_Amount_Persistence($account_amount);
            $account_amount_persistence->getFromDatabase($database, $query->row()->entry_amount_id);
            $account_amount = $account_amount_persistence->getCounterpart();
            $this->counterpart->setField('account_amount', $account_amount);
            $this->counterpart->setField('description', $query->row()->description);
            $this->counterpart->setField('effective_date', $query->row()->effective_date);
        }
    }
}

/* End of file account_entry__persistence.php */
/* Location: ./application/libraries/account_entry__persistence.php */