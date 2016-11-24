<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'year_built' => NULL,
            'make' => NULL,
            'model' => NULL,
            'color' => NULL,
            'tag_number' => NULL,
            'state_id' => NULL,
            'amount_owed' => NULL,
            'lien_holder' => NULL,
            'insurer' => NULL,
            'vehicle_agent' => NULL,
            'attribute_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['year_built'] && $this->dict['year_built'] != '') 
                || ($this->dict['make'] && $this->dict['make'] != '')
                || ($this->dict['model'] && $this->dict['model'] != '')
                || ($this->dict['color'] && $this->dict['color'] != '')
                || ($this->dict['tag_number']
                        && $this->dict['tag_number'] != '')
                || ($this->dict['amount_owed']
                        && $this->dict['amount_owed'] != '')
                || ($this->dict['lien_holder']
                        && $this->dict['lien_holder'] != '')
                || ($this->dict['insurer'] && $this->dict['insurer'] != '')
                || ($this->dict['vehicle_agent'] && $this->dict['vehicle_agent'] != '')
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

/* End of file vehicle_attribute.php */
/* Location: ./application/libraries/vehicle_attribute.php */