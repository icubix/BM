<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financial_Account_Attribute_Persistence extends Domain_Persistence {
    public function saveToDatabase($database, $party_role_id = 0) {
        if (!$this->counterpart || $this->counterpart->isEmpty()) {
            return NULL;
        }
        $attribute_type_id = 0;
        if ($this->counterpart->getField('account_desc')
                && is_numeric($this->counterpart->getField('account_desc'))) {
                    $attribute_type_id = $this->counterpart->getField('account_desc');
        } else {
            $query = $database->query('SELECT attribute_type_id '
                    . 'FROM "AttributeType" '
                    . "WHERE description = 'Bank Account';");
                    $attribute_type_id = $query->row()->attribute_type_id;
        }
        $attribute_id = $this->counterpart->getField('attribute_id');
        if ($attribute_id == 0) {
            if ($party_role_id == 0) {
                return; #have nowhere to put attribute
            } #else
            $database->query('INSERT INTO "Attribute" '
                    . '(attribute_id, attribute_type_id) '
                    . 'VALUES (DEFAULT, ?) RETURNING attribute_id;',
                    array($attribute_type_id));
            $attribute_id = $database->query('SELECT LASTVAL()')->row()->lastval;
            $database->query('INSERT INTO "DefendantAttribute" '
                    . '(attribute_id, party_role_id, from_date) '
                    . 'VALUES (?, ?, DEFAULT)',
                    array($attribute_id, $party_role_id));
            $this->counterpart->setField('attribute_id', $attribute_id);
        } else { #update attribute_type_id
            $database->query('UPDATE "Attribute" SET '
                    . 'attribute_type_id = ? '
                    . 'WHERE attribute_id = ?;',
                    array($attribute_type_id, $attribute_id));
        }
        if ($party_role_id == 0) { #this should not happen here
            $query = $database->query('SELECT party_role_id FROM "DefendantAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
            $party_role_id = $query->row()->party_role_id;
        }
        $query = $database->query('SELECT attribute_id '
                . 'FROM "FinancialAccountAttribute" WHERE attribute_id = ?',
                array($attribute_id));
        if ($query->num_rows() > 0) {
            $database->query('UPDATE "FinancialAccountAttribute" SET '
                    . '(institution, account_no, balance) '
                    . '= (?, ?, ?) WHERE attribute_id = ?;',
                    array(
                            $this->counterpart->getField('institution'),
                            $this->counterpart->getField('account_no'),
                            $this->counterpart->getField('balance'),
                            $attribute_id
                    ));
        } else {
            $database->query('INSERT INTO "FinancialAccountAttribute" '
                    . '(attribute_id, institution, account_no, balance) '
                    . 'VALUES (?, ?, ?, ?);',
                    array(
                            $attribute_id,
                            $this->counterpart->getField('institution'),
                            $this->counterpart->getField('account_no'),
                            $this->counterpart->getField('balance')
                    ));
        }
        return NULL;
    }

    public function getFromDatabase($database, $party_role_id) {
        $attribute_id = $this->counterpart->getField('attribute_id');
        $query = NULL;
        if ($attribute_id == 0) {
            $query = $database->query('SELECT "FinancialAccountAttribute".attribute_id, '
                    . 'institution, account_no, balance '
                    . 'FROM "FinancialAccountAttribute" '
                    . 'INNER JOIN "DefendantAttribute" ON "FinancialAccountAttribute".attribute_id '
                    . '= "DefendantAttribute".attribute_id '
                    . 'WHERE party_role_id = ?;', array($party_role_id));
        } else {
            $query = $database->query('SELECT institution, account_no, balance '
                    . 'FROM "FinancialAccountAttribute" '
                    . 'WHERE attribute_id = ?;', array($attribute_id));
        }
        if ($query && $query->num_rows() > 0) {
            foreach ($query->row() as $key => $value) {
                $this->counterpart->setField($key, $value);
            }
        }
        #the field attribute_id should either have been there to start with
        #  or selected in the else clause above and added in the above setField
        $query = $database->query('SELECT "Attribute".attribute_type_id FROM "Attribute" '
                . 'INNER JOIN "AttributeType" ON "Attribute".attribute_type_id '
                . '= "AttributeType".attribute_type_id WHERE attribute_id = ?;',
                array($this->counterpart->getField('attribute_id')));
        if ($query->num_rows() > 0) {
            $this->counterpart->setField('account_desc', $query->row()->attribute_type_id);
        }
    }

    public static function getFullListFromDatabase($database, $party_role_id) {
        $query = $database->query('SELECT "DefendantAttribute".attribute_id '
                . 'FROM "DefendantAttribute" '
                . 'INNER JOIN "FinancialAccountAttribute" '
                . 'ON "DefendantAttribute".attribute_id '
                . '= "FinancialAccountAttribute".attribute_id '
                . 'WHERE party_role_id = ?;',
                array($party_role_id));
        $financial_account_attributes = array();
        foreach ($query->result() as $row) {
            $new_financial_account_attribute = new Financial_Account_Attribute();
            $new_financial_account_attribute->setField('attribute_id',
                    $row->attribute_id);
            $new_financial_account_attribute_persistenace
                    = new Financial_Account_Attribute_Persistence($new_financial_account_attribute);
            $new_financial_account_attribute_persistenace->getFromDatabase($database, $party_role_id);
            $financial_account_attributes[count($financial_account_attributes)]
                    = $new_financial_account_attribute_persistenace->getCounterpart();
        }
        #if there are no financial account attributes in the database return a blank one
        if (count($financial_account_attributes) == 0) {
            $financial_account_attributes[0] = new Financial_Account_Attribute();
        }
        return $financial_account_attributes;
    }
}

/* End of file financial_account_attribute_persistence.php */
/* Location: ./application/libraries/financial_account_attribute_persistence.php */