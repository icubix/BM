<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organization extends Party_Role {
    function __construct() {
        $this->dict = array(
            'organization_name' => NULL
        );
        parent::__construct();
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['organization_name']
                        && $this->dict['organization_name'] != '')
                || !parent::isEmpty()
                );
    }

    protected function findField($field) {
        #converts phone field so it's found in organization's telecommunication_number
        if ($field == 'organization_phone_no') {
            $field = 'contact_number';
        }
        return parent::findField($field);
    }

    protected function getLocalField($field) {
        #converts phone field so it's found in organization's telecommunication_number
        if ($field == 'organization_phone_no') {
            $field = 'contact_number';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts phone field so it's found in organization's telecommunication_number
        if ($field == 'organization_phone_no') {
            $field = 'contact_number';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file organization.php */
/* Location: ./application/libraries/organization.php */