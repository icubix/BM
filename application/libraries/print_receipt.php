<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Receipt extends Domain_Presentation {
    private function makeCurrency($string) {
        $newstring = "";

        $array = str_split($string);
        foreach($array as $char) {
            if (($char >= '0' && $char <= '9') || $char == '.') {
                $newstring .= $char;
            }
        }

        return $newstring;
    }

    function echoFields($location = 'print_receipt', $accountEntries) {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        $this->readOnlyFields = true;
        echo '<div style="padding-right: 15px; padding-left: 15px">' . "\n";
        echo "<br>\n" . '<div class="row" style="text-align: center">' . "\n";
        echo '<h2>' . "\n";
        echo 'GUILLORY BONDING COMPANY OF LA, INC<br>' . "\n";
        echo 'P.O. Box 1672<br>' . "\n";
        echo 'Alexandria, LA 71309<br>' . "\n";
        echo '318-449-9500<br><br>' . "\n";
        echo 'Statement of Account</h2>' . "\n";
        echo '<h3>' . date('F j\, Y') . '</h3>' . "\n";
        echo "</div>\n<br>\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-2">' . "\n";
        echo 'To:' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-9"><b>' . "\n";
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name'));
        echo '</b></div><br><br><br>' . "\n";
        echo '<div class="col-md-2">' . "\n";
        echo 'Defendant' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-9"><b>' . "\n";
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name'));
        echo '</b></div>' . "\n";
        echo '<div class="col-md-2">' . "\n";
        echo 'Posted Date' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-9"><b>' . "\n";
        echo $this->counterpart->getField(array('bailbond_instance', 'bond_date'));
        echo '</b></div>' . "\n";
        echo '<div class="col-md-2">' . "\n";
        echo 'Bond Amount' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-9"><b>' . "\n";
        $bond_amount = $this->counterpart->getField(array('bailbond_instance', 'bond_amount'));
        if (is_numeric($bond_amount)) {
            $bond_amount = '$' . number_format($bond_amount, 2, '.', ',');
        }
        echo $bond_amount;
        echo '</b></div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<br><div class="col-md-6 col-md-offset-3">' . "\n";
        echo '<h3>Account Activity:</h3>' .  "\n";
        echo '<hr class="hr-line"></hr>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-6 col-md-offset-3">' . "\n";
        echo '<h3>Charges</h3>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        $paymentEntries = array();
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        $payment_id = 0;
        foreach($selectLists['entryType'] as $value) {
            if ($value['name'] == 'Payment') {
                $payment_id = $value['key'];
            }
        }
        $runningTotal = 0;
        foreach ($accountEntries as $value) {
            if ($value->getField('entry_type_id') == $payment_id) {
                array_push($paymentEntries, $value);
            } else {
        echo '<div class="row" style="text-align:right">' . "\n";
        echo '<div class="col-md-3 col-md-offset-3">' . "\n";
        echo $value->getField('description') . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-3">' . "\n";
                $entryAmount = $value->getField(array('account_amount', 'amount'));
                if (is_numeric($entryAmount)) {
                    $runningTotal += $entryAmount;
                    $entryAmount = '$' . number_format($entryAmount, 2, '.', ',');
                } else {
                    $runningTotal += makeCurrency($entryAmount);
                }
        echo $entryAmount . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
            }
        }
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-3 col-md-offset-6">' . "\n";
        echo '<hr class="hr-line"></hr>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row" style="text-align:right">' . "\n";
        echo '<div class="col-md-3 col-md-offset-3">' . "\n";
        echo 'Total Fees' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-3">' . "\n";
        echo '$' . number_format($runningTotal, 2, '.', ',') . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-3 col-md-offset-3">' . "\n";
        echo '<h3>Payments</h3>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        $paymentsTotal = 0;
        foreach ($paymentEntries as $value) {
        echo '<div class="row" style="text-align:right">' . "\n";
        echo '<div class="col-md-3 col-md-offset-3">' . "\n";
        echo $value->getField('description') . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-3">' . "\n";
            $entryAmount = $value->getField(array('account_amount', 'amount'));
            if (is_numeric($entryAmount)) {
                $paymentsTotal += $entryAmount;
                $entryAmount = '$' . number_format($entryAmount, 2, '.', ',');
            } else {
                $paymentsTotal += makeCurrency($entryAmount);
            }
        echo $entryAmount . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
            
        }
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-3 col-md-offset-6">' . "\n";
        echo '<hr class="hr-line"></hr>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row" style="text-align:right">' . "\n";
        echo '<div class="col-md-3 col-md-offset-3">' . "\n";
        echo 'Total Payments' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-3">' . "\n";
        echo '$' . number_format($paymentsTotal, 2, '.', ',') . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-6 col-md-offset-3">' . "\n";
        echo '<h3>Invoice Balance' . "\n";
        echo '<span style="float:right">' . "\n";
        $runningTotal += $paymentsTotal;
        echo '$' . number_format($runningTotal, 2, '.', ',') . "</span></h3>\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-6 col-md-offset-3">' . "\n";
        echo '<h3>Total Overdue Balance' . "\n";
        echo '<span style="float:right">' . "\n";
        echo '$' . number_format($runningTotal, 2, '.', ',') . "</span></h3>\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-6 col-md-offset-3" style="border-style:solid">' . "\n";
        echo '<h3>Next Payment Due<br>' . "\n";
        echo '<span style="float:right">' . "\n";
        echo '$' . number_format($runningTotal, 2, '.', ',') . "</span</h3>\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row"><br><br>' . "\n";
        echo 'Please remit any overdue amount on receipt of this notice.   If you have any questions, please contact our office at the number above.' . "\n";
        echo '</div><br>' . "\n";
        echo '<div class="row">' . "\n";
        echo 'Sincerely,' . "\n";
        echo '</div><br><br><br>' . "\n";
        echo '<div class="row">' . "\n";
        echo 'Guillotine, Lyle' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }
}

/* End of file print_receipt.php */
/* Location: ./application/libraries/print_receipt.php */
