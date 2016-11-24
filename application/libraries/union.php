<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Union extends Organization {
    protected function findField($field) {
        #converts name field so it's found in union's orginization
        if ($field == 'union_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in union's telecommunication_number
        if ($field == 'union_phone_no') {
            $field = 'contact_number';
        }
        return parent::findField($field);
    }

    protected function getLocalField($field) {
        #converts name field so it's found in union's orginization
        if ($field == 'union_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in union's telecommunication_number
        if ($field == 'union_phone_no') {
            $field = 'contact_number';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts name field so it's found in union's orginization
        if ($field == 'union_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in union's telecommunication_number
        if ($field == 'union_phone_no') {
            $field = 'contact_number';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file union.php */
/* Location: ./application/libraries/union.php */