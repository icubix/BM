<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Instance extends Domain {
    function __construct() {
        $this->dict = array(
            'party_role_id' => 0,
            'user_level' => NULL,
            'expires' => NULL, #expires is 0 when thru_date should be null, 1 if otherwise
            'thru_date' => NULL,
            'description' => NULL, #PartyType description
            'person' => new Person(),
            'organization' => new Organization(),
            'contacts' => array(),
            'failed_login_wait' => NULL,
            'failed_attempts' => NULL,
            'failed_login_first' => NULL,
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        $contactsEmpty = TRUE;
        if ($this->dict['contacts'] && count($this->dict['contacts']) > 0) {
            foreach($this->dict['contacts'] as $row) {
                if (is_a($row, 'Domain') && !is_a($row, 'User_Instance')) {
                    $contactsEmpty = $row->isEmpty();
                }
                if (!$contactsEmpty) {
                    break;
                }
            }
        }
        return !(
                   !$contactsEmpty
                || ($this->dict['party_role_id'] && $this->dict['party_role_id'] != 0)
                || ($this->dict['user_level'] && $this->dict['user_level'] != '')
                || ($this->dict['description']
                        && $this->dict['description'] != ''
               /* if */ && (($this->dict['description'] == 'Person') ?
                            ($this->dict['person']
                                && !$this->dict['person']->isEmpty()
              /* else */    ) : (
                                $this->dict['description'] == 'Organization'
                                    && $this->dict['organization']
                                    && !$this->dict['organization']->isEmpty()
                            )
              /* end */ )
                    )
                || ($this->dict['thru_date'] && $this->dict['thru_date'] != '')
                );
        #failed_login_wait, failed_login_first, and failed_login_wait
        #  are never saved, so not really relavent to isEmpty
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        $success = $this->dict['person']->findField($field);
        if ($success) {
            return $this->dict['person']->getField($success);
        }
        $success = $this->dict['organization']->findField($field);
        if ($success) {
            return $this->dict['organization']->getField($success);
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if ($field == 'increaseFieldCount') {
            $explodedValue = explode('-', $value);
            if ($explodedValue) {
                $value = $explodedValue[0];  #set $value to add** string
                #remove add***-user_instance from location array
                $explodedValue = array_slice($explodedValue, 2);
                $arrayToAddTo = $this->getField($explodedValue);
                switch ($value) {
                    case 'addPhone':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Telecommunication_Number();
                        break;
                    case 'addAddress':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Postal_Address();
                        break;
                    case 'addEmail':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Electronic_Address();
                        break;
                }
                $this->setField($explodedValue, $arrayToAddTo);
            }
        } elseif ($field == 'decreaseFieldCount') {
            $indexToDelete = 0;
            $arrayToRemoveFrom = array(0);
            $explodedValue = explode('-', $value);
            if ($explodedValue) {
                $lastIndex = count($explodedValue)-1;
                #if $explodedValue has a number at the end of array,
                #that end is the index into the array in domain
                if ($lastIndex > 1 && is_numeric($explodedValue[$lastIndex])) {
                    $value = $explodedValue[0];  #set $value to pop** string
                    $indexToDelete = $explodedValue[$lastIndex];
                    #remove pop***-user_instance from location array and number from end
                    $explodedValue = array_slice($explodedValue, 2, $lastIndex - 2);
                    $arrayToRemoveFrom = $this->getField($explodedValue);
                    switch ($value) {
                        case 'popPhone':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Telecommunication_Number();
                            }
                            break;
                        case 'popAddress':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Postal_Address();
                            }
                            break;
                        case 'popEmail':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Electronic_Address();
                            }
                            break;
                    }
                    $this->setField($explodedValue, $arrayToRemoveFrom);
                }
            }
        }
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        $success = $this->dict['person']->findField($field);
        if ($success) {
            return $this->dict['person']->setField($success, $value);
        }
        $success = $this->dict['organization']->findField($field);
        if ($success) {
            return $this->dict['organization']->setField($success, $value);
        }
        return FALSE;
    }
}

/* End of file user_instance.php */
/* Location: ./application/libraries/user_instance.php */