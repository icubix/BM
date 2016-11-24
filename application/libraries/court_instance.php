<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Court_Instance extends Organization {
    protected function findField($field) {
        #converts name field so it's found in court's orginization
        if ($field == 'court_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in court's telecommunication_number
        if ($field == 'court_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::findField($field);
    }
        
    protected function getLocalField($field) {
        #converts name field so it's found in court's orginization
        if ($field == 'court_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in court's telecommunication_number
        if ($field == 'court_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts name field so it's found in court's orginization
        if ($field == 'court_name') {
            $field = 'organization_name';
        }
        #converts phone field so it's found in court's telecommunication_number
        if ($field == 'court_phone_no') {
            $field = 'organization_phone_no';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file court_instance.php */
/* Location: ./application/libraries/court_instance.php */