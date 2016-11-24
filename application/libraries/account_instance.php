<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Instance extends Domain {
    function __construct() {
        $this->dict = array(
                'account_id' => 0,
                'entry' => new Account_Entry(),
                'recurring' => new Account_Recurring(),
                'entryDefault' => new Account_Entry(),
                'recurringDefault' => new Account_Recurring()
            );
    }
    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['account_id'] && $this->dict['account_id'] != 0)
                || ($this->dict['entry'] && !$this->dict['entry']->isEmpty())
                || ($this->dict['recurring'] && !$this->dict['recurring']->isEmpty())
                || ($this->dict['entryDefault'] && !$this->dict['entryDefault']->isEmpty())
                || ($this->dict['recurringDefault'] && !$this->dict['recurringDefault']->isEmpty())
                );
    }
    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }
    protected function setLocalField($field, $value) {
        if ($field == 'account_id') {
            if (is_numeric($value) && $value > 0) {
                if (array_key_exists($field, $this->dict)) { #redudant but just to be on the safe side
                    $this->dict[$field] = $value;
                    return TRUE;
                }
            }
            return FALSE;
        } elseif (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file account_instance.php */
/* Location: ./application/libraries/account_instance.php */