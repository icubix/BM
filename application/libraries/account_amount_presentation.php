<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_Amount_Presentation extends Account_Presentation {
    public function echoAmountField($location = 'account_amount') {
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        echo '                  <input type="hidden" name="'. $location . '-amount_type_id" value="';
        foreach($selectLists['amountType'] as $value) {
            if ($value['name'] == 'Amount: Static Amount') {
                echo $value['key'];
            }
        }
        echo '" style="position:absoulte"/>' . "\n";
        echo '                  <div class="row">Amount</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'amount', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
    }

    public function echoFields($location = 'account_amount') {
        $offset = '3';
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Type of Amount</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'amount_type_id', $selectLists['amountType'], '0', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '" id="amountDiv">' . "\n";
        echo '                  <div class="row">Amount</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'amount', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '" id="percentDiv" style="visibility:hidden; position:absolute">' . "\n";
        echo '                  <div class="row">Percent</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'percent', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '" id="targetDiv" style="visibility:hidden; position:absolute">' . "\n";
        echo '                  <div class="row">Percent of</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'target', $selectLists['target'], '0', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '" id="inflationDiv" style="visibility:hidden; position:absolute">' . "\n";
        echo '                  <div class="row">Inflation</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'inflation', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo "                <script type=\"text/javascript\">
                function selectAmountType() {
                    var type = document.getElementsByName('$location-amount_type_id')[0];
                    var selectedText = type.options[type.selectedIndex].text;
                    switch (selectedText) {
                        case 'Amount: Static Amount':
                            var visiblityGroup = [true, false, false, false];
                        break;
                        case 'Percent: Percent of Specified':
                            var visiblityGroup = [false, true, true, false];
                        break;
                        case 'Interest: Simple':
                            var visiblityGroup = [false, true, false, true];
                        break;
                        case 'Interest: Compound':
                            var visiblityGroup = [false, true, false, true];
                        break;
                        case 'Interest: Continuous Compound':
                            var visiblityGroup = [false, true, false, true];
                        break;
                        default:
                            var visiblityGroup = [true, false, false, false];
                        break;
                    }
                    if (visiblityGroup[0]) {
                        document.getElementById('amountDiv').style.visibility = 'visible';
                        document.getElementById('amountDiv').style.position = 'relative';
                    } else {
                        document.getElementById('amountDiv').style.visibility = 'hidden';
                        document.getElementById('amountDiv').style.position = 'absolute';
                    }
                    if (visiblityGroup[1]) {
                        document.getElementById('percentDiv').style.visibility = 'visible';
                        document.getElementById('percentDiv').style.position = 'relative';
                    } else {
                        document.getElementById('percentDiv').style.visibility = 'hidden';
                        document.getElementById('percentDiv').style.position = 'absolute';
                    }
                    if (visiblityGroup[2]) {
                        document.getElementById('targetDiv').style.visibility = 'visible';
                        document.getElementById('targetDiv').style.position = 'relative';
                    } else {
                        document.getElementById('targetDiv').style.visibility = 'hidden';
                        document.getElementById('targetDiv').style.position = 'absolute';
                    }
                    if (visiblityGroup[3]) {
                        document.getElementById('inflationDiv').style.visibility = 'visible';
                        document.getElementById('inflationDiv').style.position = 'relative';
                    } else {
                        document.getElementById('inflationDiv').style.visibility = 'hidden';
                        document.getElementById('inflationDiv').style.position = 'absolute';
                    }
                }
                function addAmountTypeListener() {
                    var object = document.getElementsByName('$location-amount_type_id')[0];
                    object.addEventListener(\"change\", selectAmountType);
                }
                window.onload = addAmountTypeListener();
            </script>";
    }
}

/* End of file account_amount_presentation.php */
/* Location: ./application/libraries/account_amount_presentation.php */