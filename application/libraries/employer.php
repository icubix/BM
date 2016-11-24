<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employer extends Organization {
    protected function findField($field) {
        #converts name field so it's found in employer's orginization
        if ($field == 'employer_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in employer's telecommunication_number
        if ($field == 'employer_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::findField($field);
    }
        
    protected function getLocalField($field) {
        #converts name field so it's found in employer's orginization
        if ($field == 'employer_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in employer's telecommunication_number
        if ($field == 'employer_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts name field so it's found in employer's orginization
        if ($field == 'employer_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in employer's telecommunication_number
        if ($field == 'employer_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file employer.php */
/* Location: ./application/libraries/employer.php */