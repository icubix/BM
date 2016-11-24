<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Party extends Domain {
    function __construct() {
        $this->dict['party_id'] = 0;
        $this->dict['party_type_id'] = 0;
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return TRUE;
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'party_id') {
            if (is_numeric($value) && $value > 0) {
                $this->dict['party_id'] = $value;
                return TRUE;
            }
            return FALSE;
        }
        if ($field == 'party_type_id') {
            if (is_numeric($value) && $value > 0) {
                $this->dict['party_type_id'] = $value;
                return TRUE;
            }
            return FALSE;
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file party_role.php */
/* Location: ./application/libraries/party_role.php */