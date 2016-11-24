<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_Attribute extends Domain {
    
    function __construct() {
        $this->dict = array(
            'occupation' => NULL,
            'time_with_employer' => NULL,
            'job_title' => NULL,
            'shift' => NULL,
            'employer' => new Employer(),
            'supervisor' => new Supervisor(),
            'union' => new Union(),
            'attribute_id' => 0
        );
        $this->dict['employer']->setField('telecommunication_number', array(new Telecommunication_Number()));
        $this->dict['employer']->setField('postal_address', new Postal_Address());
        $this->dict['union']->setField('telecommunication_number', array(new Telecommunication_Number()));
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['occupation'] && $this->dict['occupation'] != '')
                || ($this->dict['time_with_employer']
                        && $this->dict['time_with_employer'] != '')
                || ($this->dict['job_title'] && $this->dict['job_title'] != '')
                || ($this->dict['shift'] && $this->dict['shift'] != '')
                || ($this->dict['employer'] && !$this->dict['employer']->isEmpty())
                || ($this->dict['supervisor'] && !$this->dict['supervisor']->isEmpty())
                || ($this->dict['union'] && !$this->dict['union']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->getField($success);
        }
        $success = $this->dict['supervisor']->findField($field);
        if ($success) {
            return $this->dict['supervisor']->getField($success);
        }
        $success = $this->dict['union']->findField($field);
        if ($success) {
            return $this->dict['union']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->setField($success, $value);
        }
        $success = $this->dict['supervisor']->findField($field);
        if ($success) {
            return $this->dict['supervisor']->setField($success, $value);
        }
        $success = $this->dict['union']->findField($field);
        if ($success) {
            return $this->dict['union']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file employment_attribute.php */
/* Location: ./application/libraries/employment_attribute.php */