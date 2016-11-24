<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'aliases' => NULL,
            'date_of_birth' => NULL,
            'place_of_birth' => NULL,
            'height' => NULL,
            'weight' => NULL,
            'eyes' => NULL,
            'hair' => NULL,
            'race' => NULL,
            'id_marks' => NULL,
            'driver_license_no' => NULL,
            'driver_license_state_id' => NULL,
            'driver_license_exp' => NULL,
            'passport_no' => NULL,
            'passport_country' => NULL,
            'passport_issued' => NULL,
            'passport_expires' => NULL,
            'arrests_for' => NULL,
            'arrest_loc' => NULL,
            'on_probation' => NULL,
            'probation_location' => NULL,
            'probation_officer' => NULL,
            'defendant' => new Defendant_Instance(),
            'attribute_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['aliases'] && $this->dict['aliases'] != '')
                || ($this->dict['date_of_birth']
                        && $this->dict['date_of_birth'] != '')
                || ($this->dict['place_of_birth']
                        && $this->dict['place_of_birth'] != '')
                || ($this->dict['height'] && $this->dict['height'] != '')
                || ($this->dict['weight'] && $this->dict['weight'] != '')
                || ($this->dict['eyes'] && $this->dict['eyes'] != '')
                || ($this->dict['hair'] && $this->dict['hair'] != '')
                || ($this->dict['race'] && $this->dict['race'] != '')
                || ($this->dict['id_marks'] && $this->dict['id_marks'] != '')
                || ($this->dict['driver_license_no']
                        && $this->dict['driver_license_no'] != '')
                || ($this->dict['driver_license_exp']
                        && $this->dict['driver_license_exp'] != '')
                || ($this->dict['passport_no']
                        && $this->dict['passport_country'] != '')
                || ($this->dict['passport_issued']
                        && $this->dict['passport_issued'] != '')
                || ($this->dict['passport_expires']
                        && $this->dict['passport_expires'] != '')
                || ($this->dict['arrests_for']
                        && $this->dict['arrests_for'] != '')
                || ($this->dict['arrest_loc']
                        && $this->dict['arrest_loc'] != '')
                || ($this->dict['probation_location']
                        && $this->dict['probation_location'] != '')
                || ($this->dict['probation_officer']
                        && $this->dict['probation_officer'] != '')
                || ($this->dict['defendant'] && !$this->dict['defendant']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['defendant']->findField($field);
        if ($success) {
            return $this->dict['defendant']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'defendant') {
            $this->defendant = $value;
            return TRUE;
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['defendant']->findField($field);
        if ($success) {
            return $this->dict['defendant']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file personal_attribute.php */
/* Location: ./application/libraries/personal_attribute.php */