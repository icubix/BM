<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domain_Persistence {
    protected $counterpart;

    function __construct($counterpart) {
        $this->counterpart = $counterpart;
    }

    public function getCounterpart() {
        return $this->counterpart;
    }

    #party_role_id is ignored here but used in some of the children classes
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return 'Application Empty';
        }
        $party_role_id = $this->counterpart->getField('party_role_id');
        $personal_attribute_persistence = new Personal_Attribute_Persistence(
                $this->counterpart->getField('personal_attribute'));
        $error = $personal_attribute_persistence->saveToDatabase($database, $party_role_id);
        $this->counterpart->setField('personal_attribute',
                $personal_attribute_persistence->getCounterpart());
        if ($error) {
            return $error;
        }
        if ($party_role_id == 0) {
            $party_role_id = $this->counterpart->getField('personal_attribute')
                    ->getField('defendant')->getField('party_role_id');
            $this->counterpart->setField('party_role_id', $party_role_id);
        }
        $telecommunication_number = $this->counterpart->getField('telecommunication_number');
        $query = $database->query('SELECT "PartyContactMechanism".contact_mechanism_id '
                . 'FROM "TelecommunicationNumber" INNER JOIN "PartyContactMechanism" '
                . 'ON telecom_no_id = "PartyContactMechanism".contact_mechanism_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($telecommunication_number as $innerRow) {
                if ($row->contact_mechanism_id
                        == $innerRow->getField('contact_mechanism_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "ContactMechanism" '
                        . 'WHERE contact_mechanism_id = ?;',
                        array($row->contact_mechanism_id));
            }
        }
        foreach ($telecommunication_number as $row) {
            $error = (new Telecommunication_Number_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $electronic_address = $this->counterpart->getField('electronic_address');
        $query = $database->query('SELECT "PartyContactMechanism".contact_mechanism_id '
                . 'FROM "ElectronicAddress" INNER JOIN "PartyContactMechanism" '
                . 'ON electronic_address_id = "PartyContactMechanism".contact_mechanism_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($electronic_address as $innerRow) {
                if ($row->contact_mechanism_id
                        == $innerRow->getField('contact_mechanism_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "ContactMechanism" '
                        . 'WHERE contact_mechanism_id = ?;',
                        array($row->contact_mechanism_id));
            }
        }
        foreach ($electronic_address as $row) {
            $error = (new Electronic_Address_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $postal_address_attributes = $this->counterpart->getField('postal_address_attribute');
        $query = $database->query('SELECT "PartyContactMechanism".contact_mechanism_id '
                . 'FROM "PostalAddress" INNER JOIN "PartyContactMechanism" '
                . 'ON postal_address_id = "PartyContactMechanism".contact_mechanism_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($postal_address_attributes as $innerRow) {
                if ($row->contact_mechanism_id
                        == $innerRow->getField('contact_mechanism_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "ContactMechanism" '
                        . 'WHERE contact_mechanism_id = ?;',
                        array($row->contact_mechanism_id));
            }
        }
        foreach ($postal_address_attributes as $row) {
            $error = (new Postal_Address_Attribute_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $error = (new Employment_Attribute_Persistence(
                $this->counterpart->getField('employment_attribute')
                ))->saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $error = (new Spouse_Attribute_Persistence(
                $this->counterpart->getField('spouse_attribute')
                ))->saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $child_attributes = $this->counterpart->getField('child_attribute');
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" INNER JOIN "ChildAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "ChildAttribute".attribute_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($child_attributes as $innerRow) {
                if ($row->attribute_id
                        == $innerRow->getField('attribute_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "Attribute" '
                        . 'WHERE attribute_id = ?;',
                        array($row->attribute_id));
            }
        }
        foreach ($child_attributes as $row) {
            $error = (new Child_Attribute_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $reference_attributes = $this->counterpart->getField('reference_attribute');
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" INNER JOIN "ReferenceAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "ReferenceAttribute".attribute_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($reference_attributes as $innerRow) {
                if ($row->attribute_id
                        == $innerRow->getField('attribute_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "Attribute" '
                        . 'WHERE attribute_id = ?;',
                        array($row->attribute_id));
            }
        }
        foreach ($reference_attributes as $row) {
            $error = (new Reference_Attribute_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $error = (new Vehicle_Attribute_Persistence(
                $this->counterpart->getField('vehicle_attribute')
                ))->saveToDatabase($database, $party_role_id);
        if ($error) {
            return $error;
        }
        $financial_accounts = $this->counterpart->getField('financial_account');
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" INNER JOIN "FinancialAccountAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "FinancialAccountAttribute".attribute_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($financial_accounts as $innerRow) {
                if ($row->attribute_id
                        == $innerRow->getField('attribute_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "Attribute" '
                        . 'WHERE attribute_id = ?;',
                        array($row->attribute_id));
            }
        }
        foreach ($financial_accounts as $row) {
            $error = (new Financial_Account_Attribute_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        $indemnitor_attributes = $this->counterpart->getField('indemnitor_attribute');
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" INNER JOIN "IndemnitorAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "IndemnitorAttribute".attribute_id '
                . 'WHERE party_role_id = ?;', array($party_role_id));
        #this foreach will cycle through items in database and delete ones that are not
        #  matches for items to be saved
        foreach($query->result() as $row) {
            $noMatchFound = TRUE;
            foreach($indemnitor_attributes as $innerRow) {
                if ($row->attribute_id
                        == $innerRow->getField('attribute_id')) {
                    $noMatchFound = FALSE;
                    break;
                }
            }
            if ($noMatchFound) {
                $database->query('DELETE FROM "Attribute" '
                        . 'WHERE attribute_id = ?;',
                        array($row->attribute_id));
            }
        }
        foreach ($indemnitor_attributes as $row) {
            $error = (new Indemnitor_Attribute_Persistence($row)
                    )->saveToDatabase($database, $party_role_id);
            if ($error) {
                return $error;
            }
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        #verify party_role_id is valid and of type defendant
        $query = $database->query('SELECT party_role_type_id from "PartyRole" '
                . 'WHERE party_role_id = ?', array($party_role_id));
        if ($query->num_rows() > 0) {
            $party_role_type_id = $query->row()->party_role_type_id;
            $query = $database->query('SELECT description FROM "PartyRoleType" '
                    . 'WHERE party_role_type_id = ?;', array($party_role_type_id));
            if ($query->row()->description != 'Defendant') {
                return; #party_role_id is of wrong type
            }
        } else {
            error_log('party_role_id is not in "PartyRole" table: ' . serialize($query->result()));
            return; #party_role_id is not in "PartyRole" table
        }
        $this->counterpart->setField('party_role_id', $party_role_id);
        $personal = new Personal_Attribute_Persistence(
                $this->counterpart->getField('personal_attribute'));
        $personal->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('personal_attribute', $personal->getCounterpart());
        $this->counterpart->setField('telecommunication_number',
                Telecommunication_Number_Persistence::getFullListFromDatabase($database, $party_role_id));
        $this->counterpart->setField('electronic_address',
                Electronic_Address_Persistence::getFullListFromDatabase($database, $party_role_id));
        $this->counterpart->setField('postal_address_attribute',
                Postal_Address_Attribute_Persistence::getFullListFromDatabase($database, $party_role_id));
        $employment = new Employment_Attribute_Persistence(
                $this->counterpart->getField('employment_attribute'));
        $employment->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('employment_attribute', $employment->getCounterpart());
        $spouse = new Spouse_Attribute_Persistence(
                $this->counterpart->getField('spouse_attribute'));
        $spouse->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('spouse_attribute', $spouse->getCounterpart());
        $this->counterpart->setField('child_attribute',
                Child_Attribute_Persistence::getFullListFromDatabase($database, $party_role_id));
        $this->counterpart->setField('reference_attribute',
                Reference_Attribute_Persistence::getFullListFromDatabase($database, $party_role_id));
        $vehicle = new Vehicle_Attribute_Persistence(
                $this->counterpart->getField('vehicle_attribute'));
        $vehicle->getFromDatabase($database, $party_role_id);
        $this->counterpart->setField('vehicle_attribute', $vehicle->getCounterpart());
        $this->counterpart->setField('financial_account',
                Financial_Account_Attribute_Persistence::getFullListFromDatabase($database, $party_role_id));
        $this->counterpart->setField('indemnitor_attribute',
                Indemnitor_Attribute_Persistence::getFullListFromDatabase($database, $party_role_id));
        /* load highest id bailbond */
        $query = $database->query('SELECT bailbond_id FROM "BailBond" WHERE defendant_id = ? '
                . 'ORDER BY bailbond_id DESC LIMIT 1;', array($party_role_id));
        if ($query->num_rows() > 0) {
            $bailbond_id = $query->row()->bailbond_id;
            $bailbond_persistence = new BailBond_Persistence($this->counterpart->getField('bailbond_instance'));
            $bailbond_persistence->getFromDatabase($database, $bailbond_id);
            $this->counterpart->setField('bailbond_instance', $bailbond_persistence->getCounterpart());
        }
    }
}

/* End of file domain_persistence.php */
/* Location: ./application/libraries/domain_persistence.php */