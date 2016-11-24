<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'rent_not_own' => NULL,
            'landlord_mortgage' => NULL,
            'postal_address' => new Postal_Address(),
            'attribute_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                ($this->dict['landlord_mortgage']
                        && $this->dict['landlord_mortgage'] != '')
                || ($this->dict['postal_address'] && !$this->dict['postal_address']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['postal_address']->findField($field);
        if ($success) {
            return $this->dict['postal_address']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['postal_address']->findField($field);
        if ($success) {
            return $this->dict['postal_address']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file postal_address_attribute.php */
/* Location: ./application/libraries/postal_address_attribute.php */