<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surety extends Organization {
    protected function findField($field) {
        #converts name field so it's found in surety's orginization
        if ($field == 'surety_name') {
            $field = 'organization_name';
        }
        return parent::findField($field);
    }
        
    protected function getLocalField($field) {
        #converts name field so it's found in surety's orginization
        if ($field == 'surety_name') {
            $field = 'organization_name';
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        #converts name field so it's found in surety's orginization
        if ($field == 'surety_name') {
            $field = 'organization_name';
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file surety.php */
/* Location: ./application/libraries/surety.php */