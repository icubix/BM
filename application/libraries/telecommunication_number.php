<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telecommunication_Number extends Domain {
    function __construct() {
        $this->dict = array(
            'phone_type' => NULL,
            'phone_desc' => NULL,
            'contact_number' => NULL,
            'contact_mechanism_id' => 0
        );
    }
    
    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['phone_desc'] && $this->dict['phone_desc'] != '')
                || ($this->dict['contact_number']
                        && $this->dict['contact_number'] != '')
                );
    }

    #check to see if it's safe to insert into database
    #returns NULL if successfully verified or error otherwise
    public function verify() {
        if ($this->dict['phone_type'] && !is_numeric($this->dict['phone_type'])) {
            return 'Invalid phone type';
        }
        if ($this->dict['phone_desc'] && strlen($this->dict['phone_desc']) > 48) {
            return 'Phone description too long';
        }
        if ($this->dict['phone_desc'] && strlen($this->dict['phone_desc']) > 22) {
            return 'Phone number too long';
        }
        return NULL;
    }

    protected function getLocalField($field) {
        #this conversion is nessicary for when domain tries to
        #  get the default value for a field named something different
        if ($field == 'person_phone_no'
                || $field == 'organization_phone_no'
                || $field == 'employer_phone_no'
                || $field == 'union_phone_no') {
            $field = 'contact_number';
        }
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'person_phone_no'
                || $field == 'organization_phone_no'
                || $field == 'employer_phone_no'
                || $field == 'union_phone_no') {
            $field = 'contact_number';
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file telecommunication_number.php */
/* Location: ./application/libraries/telecommunication_number.php */