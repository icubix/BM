<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financial_Account_Attribute extends Domain {
    function __construct() {
        $this->dict = array(
            'account_desc' => NULL,
            'institution' => NULL,
            'account_no' => NULL,
            'balance' => NULL,
            'attribute_id' => 0
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['institution'] && $this->dict['institution'] != '')
                || ($this->dict['account_no'] && $this->dict['account_no'] != '')
                || ($this->dict['balance'] && $this->dict['balance'] != '')
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file financial_account_attribute.php */
/* Location: ./application/libraries/financial_account_attribute.php */