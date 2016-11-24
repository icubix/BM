<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person extends Party_Role {
    function __construct() {
        $this->dict = array(
            'first_name' => NULL,
            'middle_name' => NULL,
            'last_name' => NULL,
            'suffix_name' => NULL,
            'ssn' => NULL
        );
        parent::__construct();
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['first_name'] && $this->dict['first_name'] != '') 
                || ($this->dict['middle_name'] && $this->dict['middle_name'] != '')
                || ($this->dict['last_name'] && $this->dict['last_name'] != '')
                || ($this->dict['suffix_name'] && $this->dict['suffix_name'] != '')
                || ($this->dict['ssn'] && $this->dict['ssn'] != '')
                || !parent::isEmpty()
                );
    }

    protected function findField($field) {
        #converts phone field so it's found in person's telecommunication_number
        if ($field == 'person_phone_no') {
            $field = 'contact_number';
        }
        return parent::findField($field);
    }

    protected function getLocalField($field) {
        #converts phone field so it's found in person's telecommunication_number
        if ($field == 'person_phone_no') {
            $field = 'contact_number';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts phone field so it's found in person's telecommunication_number
        if ($field == 'person_phone_no') {
            $field = 'contact_number';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file person.php */
/* Location: ./application/libraries/person.php */