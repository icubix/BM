<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Recurring extends Account_Instance {
    function __construct() {
        $this->dict = array(
            'recurring_entry_id' => 0,
            'entry_type_id' => NULL,
            'account_amount' => new Account_Amount(),
            'description' => NULL,
            'last_apply_date' => NULL,
            'interval_number' => NULL,
            'interval_scale' => NULL,
            'start_date' => NULL,
            'end_date' => NULL
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['recurring_entry_id'] && $this->dict['recurring_entry_id'] != 0
                && $this->dict['entry_type_id'] && $this->dict['entry_type_id'] != 0)
                || ($this->dict['description']
                        && $this->dict['description'] != '')
                || ($this->dict['last_apply_date']
                        && $this->dict['last_apply_date'] != '')
                || ($this->dict['interval_number']
                        && $this->dict['interval_number'] != '')
                || ($this->dict['start_date']
                        && $this->dict['start_date'] != '')
                || ($this->dict['end_date']
                        && $this->dict['end_date'] != '')
                || ($this->dict['account_amount'] && !$this->dict['account_amount']->isEmpty())
                );
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['account_amount']->findField($field);
        if ($success) {
            return $this->dict['account_amount']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file account_recurring.php */
/* Location: ./application/libraries/account_recurring.php */