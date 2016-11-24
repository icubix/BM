<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address extends Domain {
    function __construct() {
        $this->dict = array(
            'address_type' => NULL,
            'address_desc' => NULL,
            'address_1st_line' => NULL,
            'address_2nd_line' => NULL,
            'city' => NULL,
            'state_id' => NULL,
            'postal_code' => NULL,
            'contact_mechanism_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['address_desc']
                        && $this->dict['address_desc'] != '')
                || ($this->dict['address_1st_line']
                        && $this->dict['address_1st_line'] != '')
                || ($this->dict['address_2nd_line']
                        && $this->dict['address_2nd_line'] != '')
                || ($this->dict['city'] && $this->dict['city'] != '') 
                || ($this->dict['postal_code']
                        && $this->dict['postal_code'] != '')
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

/* End of file postal_address.php */
/* Location: ./application/libraries/postal_address.php */