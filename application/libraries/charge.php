<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charge extends Domain {
    function __construct() {
        $this->dict = array(
            'charge_id' => 0,
            'charge_desc' => NULL,
            'charge_type' => NULL,
            'court' => new Court_Instance()
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['charge_id'] && $this->dict['charge_id'] != 0)
                || ($this->dict['charge_desc']
                        && $this->dict['charge_desc'] != '')
                || ($this->dict['charge_type']
                        && $this->dict['charge_type'] != '')
                || ($this->dict['court']
                        && !$this->dict['court']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['court']->findField($field);
        if ($success) {
            return $this->dict['court']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['court']->findField($field);
        if ($success) {
            return $this->dict['court']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file charge.php */
/* Location: ./application/libraries/charge.php */