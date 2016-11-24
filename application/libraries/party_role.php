<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Party_Role extends Party {
    protected $telecommunication_number, $postal_address;

    function __construct() {
        $this->dict['telecommunication_number'] = NULL;
        $this->dict['postal_address'] = NULL;
        $this->dict['party_role_id'] = 0;
        $this->dict['party_role_type_id'] = 0;
        $this->dict['party_role_desc'] = NULL; #for party role type description
        parent::__construct();
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        if (count($this->dict['telecommunication_number']) > 0) {
            foreach ($this->dict['telecommunication_number'] as $row) {
                if (!$row->isEmpty()) {
                    return FALSE;
                }
            }
        }
        return !($this->dict['postal_address']
                        && !$this->dict['postal_address']->isEmpty()
                );
    }

    protected function getLocalField($field) {
        if ($this->dict['telecommunication_number']
                && count($this->dict['telecommunication_number']) > 0) {
            $success = $this->dict['telecommunication_number'][0]->findField($field);
            if ($success) {
                return $this->dict['telecommunication_number'][0]->getField($success);
            }
        }
        if ($this->dict['postal_address']) {
            $success = $this->dict['postal_address']->findField($field);
            if ($success) {
                return $this->dict['postal_address']->getField($success);
            }
        }
        return parent::getLocalField($field);
    }

    protected function setLocalField($field, $value) {
        if ($this->dict['telecommunication_number']
                && count($this->dict['telecommunication_number']) > 0) {
            $success = $this->dict['telecommunication_number'][0]->findField($field);
            if ($success) {
                return $this->dict['telecommunication_number'][0]->getField($success, $value);
            }
        }
        if ($this->dict['postal_address']) {
            $success = $this->dict['postal_address']->findField($field);
            if ($success) {
                return $this->dict['postal_address']->getField($success, $value);
            }
        }
        return parent::setLocalField($field, $value);
    }
}

/* End of file party.php */
/* Location: ./application/libraries/party.php */