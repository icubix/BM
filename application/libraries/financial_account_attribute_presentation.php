<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Financial_Account_Attribute_Presentation extends Domain_Presentation {
    function echoFields($location = 'financial_account-0') {
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        echo '<div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-2">' . "\n";
        echo '              <div class="row">Account Type</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'account_desc', $selectLists['financialType'], '2000', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Institution/Account Name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'institution', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-2">' . "\n";
        echo '              <div class="row">Account No.</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'account_no', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-2">' . "\n";
        echo '              <div class="row">Balance</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'balance', 'col-sm-2 col-md-9');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Account" value="popAccount-' . $location . '" style="float: right';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
    }
}

/* End of file financial_account_attribute_presentation.php */
/* Location: ./application/libraries/financial_account_attribute_presentation.php */