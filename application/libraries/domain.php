<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

#Class to store and interface with the data in a Defendant Application
class Domain {
    protected $dict;

    function __construct() {
        #should not be called by any children
        #document_instance is ment to hold one document when passing
        #       between upload or document detail page and
        #       the list of documents page
        #bailbond_instance is like document_instance
        #contact section has been broken into
        #       telecommunication_number and postal_address_attribute
        #credit section has been broken up into
        #       vehicle_attribute and financial_account
        #family section has been broken up into
        #       spouse_attribute and child_attribute
        #pages that are not assigned to an array only have one set of data
        #the other members are arrays that are for parts of sections that can
        #       be expanded to include multible instances of the data type
        #       these are the Telecommunication_Number, Postal_Address_Attribute,
        #               Financial_Account, Indemnitor_Attribute,
        #               Reference_Attribute, and Child_Attribute
        $this->dict = array(
                'party_role_id' => 0,
                'telecommunication_number' => array(new Telecommunication_Number()),
                'electronic_address' => array(new Electronic_Address()),
                'postal_address_attribute' => array(new Postal_Address_Attribute()),
                'vehicle_attribute' => new Vehicle_Attribute(),
                'financial_account' => array(new Financial_Account_Attribute()),
                'document_instance' => new Document_Instance(),
                'employment_attribute' => new Employment_Attribute(),
                'indemnitor_attribute' => array(new Indemnitor_Attribute()),
                'personal_attribute' => new Personal_Attribute(),
                'reference_attribute' => array(new Reference_Attribute()),
                'child_attribute' => array(new Child_Attribute()),
                'spouse_attribute' => new Spouse_Attribute(),
                'bailbond_instance' => new Bailbond_Instance()
            );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        foreach($this->dict as $row) {
            if (is_array($row)) {
                foreach($row as $innerRow) {
                    if (!$innerRow->isEmpty()) {
                        return FALSE;
                    }
                }
            } elseif (is_a($row, 'Domain')) {
                if (!$row->isEmpty()) {
                    return FALSE;
                }
            } #don't check 'party_role_id'
        }
        return TRUE;
    }

    #check to see if it's safe to insert into database
    #returns NULL if successfully verified or error otherwise
    public function verify() {
        $error = NULL;
        foreach(array('telecommunication_number', 'electronic_address',
                'postal_address_attribute', 'vehicle_attribute',
                'financial_account', 'document_instance',
                'employment_attribute', 'indemnitor_attribute',
                'personal_attribute', 'reference_attribute',
                'child_attribute', 'spouse_attribute',
                'bailbond_instance') as $value) {
            $error = $this->dict($value)->verify();
            if ($error) {
                return $error;
            }
        }
        return NULL;
    }

    #find Field recursivly finds field and returns an array of it's
    #    location if the field is found
    #returns false if it can not find it
    protected function findField($field) {
        if (array_key_exists($field, $this->dict)) {
            return array($field);
        }
        foreach($this->dict as $key => $value) {
            if ($value && is_a($value, 'Domain')) {
                $success = $value->findField($field);
                if ($success) {
                    #return $key added to begining of $success
                    array_unshift($success, $key);
                    return $success;
                }
            }
        }
        return FALSE;
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    #unlike the child's use of this where $value is always text
    #this domain setLocalField expects an array for $value sometimes
    #when $field is increaseFieldCount or decreaseFieldCount $value is a location array
    #when $field is party_role_id $value should be numeric
    protected function setLocalField($field, $value) {
        if ($field == 'increaseFieldCount') {
            $explodedValue = explode('-', $value);
            if ($explodedValue) {
                $value = $explodedValue[0];  #set $value to add** string
                $explodedValue = array_slice($explodedValue, 1);  #remove add*** from location array
                $arrayToAddTo = $this->getField($explodedValue);
                switch ($value) {
                    case 'addPhone':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Telecommunication_Number();
                        break;
                    case 'addEmail':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Electronic_Address();
                        break;
                    case 'addAddress':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Postal_Address_Attribute();
                        break;
                    case 'addAccount':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Financial_Account_Attribute();
                        break;
                    case 'addIndemnitor':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Indemnitor_Attribute();
                        break;
                    case 'addReference':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Reference_Attribute();
                        break;
                    case 'addChild':
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Child_Attribute();
                        break;
                    case 'addBailbond': #decypricated
                        $arrayToAddTo[count($arrayToAddTo)] =
                                    new Bailbond_Instance();
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
                    #remove pop*** from location array and number from end
                    $explodedValue = array_slice($explodedValue, 1, $lastIndex - 1);
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
                        case 'popEmail':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Electronic_Address();
                            }
                            break;
                        case 'popAddress':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Postal_Address_Attribute();
                            }
                            break;
                        case 'popAccount':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Financial_Account_Attribute();
                            }
                            break;
                        case 'popIndemnitor':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Indemnitor_Attribute();
                            }
                            break;
                        case 'popReference':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Reference_Attribute();
                            }
                            break;
                        case 'popChild':
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Child_Attribute();
                            }
                            break;
                        case 'popBailbond': #decypricated
                            unset($arrayToRemoveFrom[$indexToDelete]);
                            $arrayToRemoveFrom =
                                    array_values($arrayToRemoveFrom);
                            if (count($arrayToRemoveFrom) == 0) {
                                $arrayToRemoveFrom[0] =
                                        new Bailbond_Instance();
                            }
                            break;
                    }
                    $this->setField($explodedValue, $arrayToRemoveFrom);
                }
            }
        } elseif ($field == 'party_role_id') {
            if (is_numeric($value) && $value > 0) {
                if (array_key_exists($field, $this->dict)) { #redudant but just to be on the safe side
                    $this->dict[$field] = $value;
                    return TRUE;
                }
            }
            return FALSE;
        } elseif (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }

    #field can be a single memeber or an array of submembers to also get
    #handling 3 primary cases, the $field requested is an array of domains, a single domain, or not a domain
    #if it's an array and field has a index into that array then use it
    #if it's a domain then use it's getField function
    #if it's not a domain and it's what $field asked for return it
    public function getField($field) {
        if (is_array($field)) {
            $count = count($field);
            if ($count > 1) {
                $intermediateField = $this->getLocalField($field[0]);
                #check to see if it's an array of objects with getField functions
                if (is_array($intermediateField)) {
                    #make sure field[1] is within the length of $intermediateField
                    if (count($intermediateField) > $field[1]) {
                        if ($count == 2) {
                            return $intermediateField[$field[1]];
                        } #else if ($count > 2) {
                        #check to see if it has getField as a function
                        if (is_a($intermediateField[$field[1]], 'Domain')) {
                            #recusivly call this function with the first two elements of $field chopped off
                            return $intermediateField[$field[1]]->getField(array_slice($field, 2));
                        }
                    } #else if (!is_a($intermediateField[$field[1]], 'Domain') || count($intermediateField) < $field[1]
                    return NULL;
                } #else (!is_array($intermediateField) so check to see if it has getField as a function
                if (is_a($intermediateField, 'Domain')) {
                    #recusivly call this function with the first element of $field chopped off
                    return $intermediateField->getField(array_slice($field, 1));
                } #else (count <1 && !is_a($intermediateField, 'Domain'))
                return NULL;
            } else if ($count < 1) {
                return NULL;
            } #else if (count == 1)
            return $this->getLocalField($field[0]);
        } #else not an array
        return $this->getLocalField($field);
    }

    #field can be a single memeber or an array of submembers to also navigate to then set
    #handling 3 primary cases, the $field requested is an array of domains, a single domain, or not a domain
    #if it's an array and field has a index into that array then use it
    #if it's a domain then use it's setField function
    #if it's not a domain and it's what $field asked for set it
    public function setField($field, $value) {
         print_r('expression');
        if (is_array($field)) {
            $count = count($field);
            if ($count > 1) {
                $intermediateField = $this->getLocalField($field[0]);
                #check to see if the local field is an array
                if (is_array($intermediateField)) {
                    #make sure field[1] is within the length of $intermediateField
                    if (count($intermediateField) > $field[1]) {
                        #count = 2 means we do not need a recursive call to get what as requested
                        if ($count == 2) {
                            $intermediateField[$field[1]] = $value;
                            $this->setLocalField($field[0], $intermediateField);
                        } #else if ($count > 2) {
                        #check to see if it has getField as a function
                        if (is_a($intermediateField[$field[1]], 'Domain')) {
                            #recusivly call this function with the first two elements of $field chopped off
                            $success = $intermediateField[$field[1]]->setField(array_slice($field, 2), $value);
                            $this->setLocalField($field[0], $intermediateField);
                            return $success; #if insersion succeded from two lines above
                        }
                    } #else if (!is_a($intermediateField[$field[1]], 'Domain') || count($intermediateField) < $field[1]
                    return NULL;
                } #else (!is_array($intermediateField) so check to see if it has getField as a function
                if (is_a($intermediateField, 'Domain')) {
                    #recusivly call this function with the first element of $field chopped off
                    $success = $intermediateField->setField(array_slice($field, 1), $value);
                    $this->setLocalField($field[0], $intermediateField);
                    return $success;
                } #else (count <1 && !is_a($intermediateField, 'Domain'))
                return NULL;
            } else if ($count < 1) {
                return NULL;
            } #else if (count == 1)
            return $this->setLocalField($field[0], $value);
        } #else not an array

        return $this->setLocalField($field, $value);
    }
}

/* End of file domain.php */
/* Location: ./application/libraries/domain.php */