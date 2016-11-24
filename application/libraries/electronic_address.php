<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Electronic_Address extends Domain {
    function __construct() {
        $this->dict = array(
            'email_type' => NULL,
            'email_desc' => NULL,
            'electronic_address' => NULL,
            'contact_mechanism_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['email_desc'] && $this->dict['email_desc'] != '')
                || ($this->dict['electronic_address']
                        && $this->dict['electronic_address'] != '')
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file electronic_address.php */
/* Location: ./application/libraries/electronic_address.php */