<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'reference' => new Reference(),
            'employer' => new Employer(),
            'attribute_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['reference'] && !$this->dict['reference']->isEmpty())
                || ($this->dict['employer'] && !$this->dict['employer']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['reference']->findField($field);
        if ($success) {
            return $this->dict['reference']->getField($success);
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
        $success = $this->dict['reference']->findField($field);
        if ($success) {
            return $this->dict['reference']->setField($success, $value);
        }
        $success = $this->dict['employer']->findField($field);
        if ($success) {
            return $this->dict['employer']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file reference_attribute.php */
/* Location: ./application/libraries/reference_attribute.php */