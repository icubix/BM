<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Child_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'date_of_birth' => NULL,
            'employer' => new Employer(),
            'child' => new Child_Instance(),
            'attribute_id' => 0
            );
            $this->dict['employer']->setField('telecommunication_number', array(new Telecommunication_Number()));
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['date_of_birth']
                        && $this->dict['date_of_birth'] != '')
                || ($this->dict['employer'] && !$this->dict['employer']->isEmpty())
                || ($this->dict['child'] && !$this->dict['child']->isEmpty())
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
        $success = $this->dict['child']->findField($field);
        if ($success) {
            return $this->dict['child']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'employer') {
            $this->employer = $value;
            return TRUE;
        }
        if ($field == 'child') {
            $this->child = $value;
            return TRUE;
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->setField($success, $value);
        }
        $success = $this->dict['child']->findField($field);
        if ($success) {
            return $this->dict['child']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file child_attribute.php */
/* Location: ./application/libraries/child_attribute.php */