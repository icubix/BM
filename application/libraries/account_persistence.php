<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $entry_type) {
        if (!$this->counterpart) {
            return 'Empty';
        }
        $relavent_entry = $this->counterpart->getField($entry_type);
        if (!$relavent_entry || $relavent_entry->isEmpty()) {
            return 'Empty';
        }
        $relavent_entry_persistence = NULL;
        if ($entry_type == 'entry') {
            $relavent_entry_persistence = new Account_Entry_Persistence(
                        $relavent_entry);
        } elseif ($entry_type == 'recurring') {
            $relavent_entry_persistence = new Account_Recurring_Persistence(
                        $relavent_entry);
        } elseif ($entry_type == 'entryDefault') {
            $relavent_entry_persistence = new Account_Entry_Persistence(
                        $relavent_entry);
        } elseif ($entry_type == 'recurringDefault') {
            $relavent_entry_persistence = new Account_Recurring_Persistence(
                        $relavent_entry);
        } else {
            return 'Error: Invalid Entry Type';
        }
        $error = $relavent_entry_persistence->saveToDatabase($database, $this->counterpart->getField('account_id'));
        $relavent_entry = $relavent_entry_persistence->getCounterpart();
        $this->counterpart->setField($entry_type, $relavent_entry);
        if ($error) {
            return $error;
        }
        return NULL;
    }

    public function getFromDatabase($database, $account_id = 0) {
        $this->counterpart->setField('account_id', $account_id);
    }
}

/* End of file account__persistence.php */
/* Location: ./application/libraries/account__persistence.php */