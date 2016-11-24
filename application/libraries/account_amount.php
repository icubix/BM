<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Amount extends Account_Instance {
    function __construct() {
        $this->dict = array(
            'entry_amount_id' => 0,
            'amount_type_id' => NULL,
            'amount' => NULL,
            'percentage' => NULL,
            'target' => NULL,
            'inflation' => NULL
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['entry_amount_id'] && $this->dict['entry_amount_id'] != 0
                && $this->dict['entry_amount_id'] && $this->dict['entry_amount_id'] != 0)
                || ($this->dict['amount']
                        && $this->dict['amount'] != '')
                || ($this->dict['percentage']
                        && $this->dict['percentage'] != '')
                || ($this->dict['target']
                        && $this->dict['target'] != '')
                || ($this->dict['inflation']
                        && $this->dict['inflation'] != '')
                );
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file account_amount.php */
/* Location: ./application/libraries/account_amount.php */