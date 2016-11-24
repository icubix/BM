<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Recurring_Presentation extends Account_Presentation {
    public function echoFields($location = 'recurring') {
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
        echo '                  <div class="row">Description</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'description', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        $amount = NULL;
        if ($this->counterpart) {
            $amount = $this->counterpart->getField('account_amount');
        }
        $amount = new Account_Amount_Presentation($amount);
        $amount->setReadOnlyFields($this->readOnlyFields);
        $amount->echoFields($location . '-account_amount');
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Appy Interval</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'interval_number', 'col-sm-2 col-md-3');
        echo '                ';
        $this->echoSelect($location, 'interval_scale', $selectLists['interval'], 'MONTH', 'col-sm-2 col-md-6');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Start Date</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'start_date', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">End Date</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'end_date', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }

    public function echoSimplePayment($location = 'recurring') {
        $offset = '3';
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        echo '                  <input type="hidden" name="'. $location . '-entry_type_id" value="';
        foreach($selectLists['entryType'] as $value) {
            if ($value['name'] == 'Payment') {
                echo $value['key'];
            }
        }
        echo '" style="position:absoulte"/>' . "\n";
        echo '                  <input type="hidden" name="'. $location . '-description" value="Payment Plan" style="position:absoulte"/>' . "\n";
        echo '                  <input type="hidden" name="'. $location . '-start_date" value="today" style="position:absoulte"/>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row text-right defendant-title"><b>Payment Plan:&nbsp;&nbsp;</b></div>' . "\n";
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
        echo '                  <div class="row">Appy Interval</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'interval_number', 'col-sm-2 col-md-3" value="1');
        echo '                ';
        $this->echoSelect($location, 'interval_scale', $selectLists['interval'], 'MONTH', 'col-sm-2 col-md-6');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }
}

/* End of file account_recurring_presentation.php */
/* Location: ./application/libraries/account_recurring_presentation.php */