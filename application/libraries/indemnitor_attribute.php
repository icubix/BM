<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indemnitor_Attribute extends Domain {
    
    function __construct() {
        $this->dict = array(
            'date_of_birth' => NULL,
            'driver_license_no' => NULL,
            'driver_license_state_id' => NULL,
            'indemnitor' => new Indemnitor(),
            'employer' => new Employer(),
            'attribute_id' => 0
        );
        $this->dict['employer']->setField('telecommunication_number', array(new Telecommunication_Number()));
        $this->dict['employer']->setField('postal_address', new Postal_Address());
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['date_of_birth']
                        && $this->dict['date_of_birth'] != '')
                || ($this->dict['driver_license_no']
                        && $this->dict['driver_license_no'] != '')
                || ($this->dict['indemnitor'] && !$this->dict['indemnitor']->isEmpty())
                || ($this->dict['employer'] && !$this->dict['employer']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        #converts phone field so it's found in indemnitor's telecommunication_number
        if ($field == 'person_phone_no') {
            $field = 'contact_number';
        }
        $success = $this->dict['indemnitor']->findField($field);
        if ($success) {
            return $this->dict['indemnitor']->getField($success);
        }
        #converts phone field so it's found in employer's telecommunication_number
        if ($field == 'organization_phone_no') {
            $field = 'contact_number';
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        #converts phone field so it's found in indemnitor's telecommunication_number
        if ($field == 'person_phone_no') {
            $field = 'contact_number';
        }
        $success = $this->dict['indemnitor']->findField($field);
        if ($success) {
            return $this->dict['indemnitor']->setField($success, $value);
        }
        #converts phone field so it's found in employer's telecommunication_number
        if ($field == 'organization_phone_no') {
            $field = 'contact_number';
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file indemnitor_attribute.php */
/* Location: ./application/libraries/indemnitor_attribute.php */