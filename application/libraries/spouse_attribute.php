<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spouse_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'date_of_birth' => NULL,
            'maiden_name' => NULL,
            'occupation' => NULL,
            'shift' => NULL,
            'time_with_employer' => NULL,
            'job_title' => NULL,
            'employer' => new Employer(),
            'spouse' => new Spouse(),
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
                || ($this->dict['maiden_name']
                        && $this->dict['maiden_name'] != '')
                || ($this->dict['occupation']
                        && $this->dict['occupation'] != '')
                || ($this->dict['shift'] && $this->dict['shift'] != '')
                || ($this->dict['time_with_employer']
                        && $this->dict['time_with_employer'] != '')
                || ($this->dict['job_title'] && $this->dict['job_title'] != '')
                || ($this->dict['employer'] && !$this->dict['employer']->isEmpty())
                || ($this->dict['spouse'] && !$this->dict['spouse']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['spouse']->findField($field);
        if ($success) {
            return $this->dict['spouse']->getField($success);
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'spouse') {
            $this->spouse = $value;
            return TRUE;
        }
        if ($field == 'employer') {
            $this->spouse = $value;
            return TRUE;
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['spouse']->findField($field);
        if ($success) {
            return $this->dict['spouse']->setField($success, $value);
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file spouse_attribute.php */
/* Location: ./application/libraries/spouse_attribute.php */