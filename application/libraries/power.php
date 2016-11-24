<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power extends Domain {
    function __construct() {
        $this->dict = array(
            'power_id' => 0,
            'power_number' => NULL,
            'power_amount' => NULL,
            'surety' => new Surety(),
            'agent' => new Agent_Instance()
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['power_id'] && $this->dict['power_id'] != 0)
                || ($this->dict['power_number']
                        && $this->dict['power_number'] != '')
                || ($this->dict['power_amount']
                        && $this->dict['power_amount'] != '')
                || ($this->dict['surety']
                        && !$this->dict['surety']->isEmpty())
                || ($this->dict['agent']
                        && !$this->dict['agent']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['surety']->findField($field);
        if ($success) {
            return $this->dict['surety']->getField($success);
        }
        $success = $this->dict['agent']->findField($field);
        if ($success) {
            return $this->dict['agent']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['surety']->findField($field);
        if ($success) {
            return $this->dict['surety']->setField($success, $value);
        }
        $success = $this->dict['agent']->findField($field);
        if ($success) {
            return $this->dict['agent']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file power_instance.php */
/* Location: ./application/libraries/power_instance.php */