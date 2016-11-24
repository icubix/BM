<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BailBond_Instance extends Domain {
    function __construct() {
        $this->dict = array(
            'defendant_id' => 0,
            'bailbond_id' => 0,
            'folio_number' => NULL,
            'bond_date' => NULL,
            'bond_time' => NULL,
            'bond_amount' => NULL,
            'case_number' => NULL,
            'spn' => NULL,
            'jail_location' => NULL,
            'indemnitor_id' => NULL,
            'charge' => new Charge(),
            'power' => new Power()
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['defendant_id'] && $this->dict['defendant_id'] != 0
                && $this->dict['bailbond_id'] && $this->dict['bailbond_id'] != 0)
                || ($this->dict['folio_number']
                        && $this->dict['folio_number'] != '')
                || ($this->dict['bond_date']
                        && $this->dict['bond_date'] != '')
                || ($this->dict['bond_time']
                        && $this->dict['bond_time'] != '')
                || ($this->dict['bond_amount']
                        && $this->dict['bond_amount'] != '')
                || ($this->dict['case_number']
                        && $this->dict['case_number'] != '')
                || ($this->dict['spn'] && $this->dict['spn'] != '')
                || ($this->dict['jail_location']
                        && $this->dict['jail_location'] != '')
                || ($this->dict['indemnitor_id']
                        && $this->dict['indemnitor_id'] != '')
                || ($this->dict['charge'] && !$this->dict['charge']->isEmpty())
                || ($this->dict['power'] && !$this->dict['power']->isEmpty())
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['charge']->findField($field);
        if ($success) {
            return $this->dict['charge']->getField($success);
        }
        $success = $this->dict['power']->findField($field);
        if ($success) {
            return $this->dict['power']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['charge']->findField($field);
        if ($success) {
            return $this->dict['charge']->setField($success, $value);
        }
        $success = $this->dict['power']->findField($field);
        if ($success) {
            return $this->dict['power']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file bailbond_instance.php */
/* Location: ./application/libraries/bailbond_instance.php */