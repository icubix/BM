<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Entry_Presentation extends Account_Presentation {
    public function echoFields($location = 'entry') {
        $offset = '3';
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Type of Entry</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'entry_type_id', $selectLists['entryType'], '0', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        $amount = NULL;
        if ($this->counterpart) {
            $amount = $this->counterpart->getField('account_amount');
        }
        $amount = new Account_Amount_Presentation($amount);
        $amount->setReadOnlyFields($this->readOnlyFields);
        $amount->echoAmountField($location . '-account_amount');
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Description</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'description', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Date</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'effective_date', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }

    public function echoSimplePayment($location = 'entry') {
        $offset = '3';
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        echo '                  <input type="hidden" name="'. $location . '-entry_type_id" value="';
        foreach($selectLists['entryType'] as $value) {
            if ($value['name'] == 'Payment') {
                echo $value['key'];
            }
        }
        echo '" style="position:absoulte"/>' . "\n";
        echo '                  <input type="hidden" name="'. $location . '-effective_date" value="today" style="position:absoulte"/>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row text-right defendant-title"><b>Upfront Payment:&nbsp;&nbsp;</b></div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        $amount = NULL;
        if ($this->counterpart) {
            $amount = $this->counterpart->getField('account_amount');
        }
        $amount = new Account_Amount_Presentation($amount);
        $amount->setReadOnlyFields($this->readOnlyFields);
        $amount->echoAmountField($location . '-account_amount');
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Payment Type</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'description', $selectLists['simplePayment'], 'Cash', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }
}

/* End of file account_entry_presentation.php */
/* Location: ./application/libraries/account_entry_presentation.php */