<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Payment_Plan extends Domain_Presentation {
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

    function echoFields($location = 'print_receipt', $accountEntries, $indemnitor_id) {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        $this->readOnlyFields = true;
        $selectLists = unserialize($_SESSION['accountSelectLists']);
        $surety_name = 'GUILLORY BONDING COMPANY OF LA, INC';
        $surety_street = 'P.O. Box 1672';
        $surety_city = 'Alexandria, LA 71309';
        $surety_phone = '318-449-9500';
        echo '<div style="padding-right: 15px; padding-left: 15px">' . "\n";
        echo "<br>\n" . '<div class="row" style="text-align: center">' . "\n";
        echo '<div class="col-md-6">' . "\n";
        echo '<h3>' . "\n";
        echo $surety_name . '<br>' . "\n";
        echo '</h3>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-6">' . "\n";
        echo $surety_street . '<br>' . "\n";
        echo $surety_city . '<br>' . "\n";
        echo $surety_phone . '<br><br>' . "\n";
        echo '</div>' . "\n";
        echo "</div>\n<br>\n";
        echo "<br>\n" . '<div class="row" style="text-align: center">' . "\n";
        echo '<h1>' . "\n";
        echo 'Payment Plan Agreement<br>' . "\n";
        echo '</h1>' . "\n";
        echo "</div>\n<br>\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-md-4 col-md-offset-1">' . "\n";
        echo 'Amount Due:' . "\n";
        $payment_id = 0;
        foreach($selectLists['entryType'] as $value) {
            if ($value['name'] == 'Payment') {
                $payment_id = $value['key'];
            }
        }
        $runningTotal = 0;
        foreach ($accountEntries as $value) {
            if ($value->getField('entry_type_id') != $payment_id) {
                $entryAmount = $value->getField(array('account_amount', 'amount'));
                if (is_numeric($entryAmount)) {
                    $runningTotal += $entryAmount;
                } else {
                    $runningTotal += makeCurrency($entryAmount);
                }
            }
        }
        echo number_format($runningTotal, 2, '.', ',') . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-4 col-md-offset-2">' . "\n";
        echo 'Date: ' . date('F j\, Y') . '' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row"><br><br>' . "\n";
        echo '<b style="font-size:130%">&emsp;&emsp;FOR VALUABLE CONSIDERATION,</b> the receipt and sufficency of which is hereby' . "\n";
        echo ' acknowledged, we or either of us, as principals, promis to pay to the order of ' . "\n";
        echo $surety_name . "\n";
        echo ' at ' . "\n";
        echo $surety_street . ', ' . "\n";
        echo $surety_city . "\n";
        echo ' in ' . "\n";
        echo 'Louisiana' . "\n";
        echo ', in the manner, on the dates, and in the amounts set herein, stipulated, the undersigned,' . "\n";
        echo '</div><br>' . "\n";
        echo '<div class="col-md-9 col-md-offset-1"><b>DEFENDANT:</b> ' . "\n";
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'suffix_name'));
        echo ', and</div><br><br><br>' . "\n";
        echo '<div class="col-md-9 col-md-offset-1"><b>INDEMNITOR:</b> ' . "\n";
        $indemnitorList = $this->counterpart->getField('indemnitor_attribute');
        $matchedIndemnitor = NULL;
        foreach ($indemnitorList as $value) {
            if ($value->getField(array('indemnitor', 'party_role_id')) == $indemnitor_id) {
                $matchedIndemnitor = $value;
                break;
            }
        }
        if (!$matchedIndemnitor) {
            $matchedIndemnitor = new Indemnitor();
        }
        echo $matchedIndemnitor->getField(array('indemnitor', 'first_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'middle_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'last_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'suffix_name'));
        echo ',</div><br><br><br>' . "\n";
        echo '<div class="row">' . "\n";
        echo 'promis to pay to the order of ' . "\n";
        echo $surety_name . "\n";
        echo ' at ' . "\n";
        echo $surety_street . ', ' . "\n";
        echo $surety_city . "\n";
        echo ' in ' . "\n";
        echo 'Louisiana' . "\n";
        echo ' the sum set out above, in lawful money of the United States of America, which shall be' . "\n";
        echo '' . "\n";
        echo ' legal tender, in payment of all debts and dues public and private, at the time of payment' . "\n";
        echo ' and to pay intrest thereon from this date until maturity at the rate of' . "\n";
        echo '10% per annum' . "\n";
        echo ', payable as stipulated here.  This note is payable as follows to wit.  The principal and' . "\n";
        echo ' interest on this note is payable in:' . "\n";
        echo '</div><br><br>' . "\n";
        echo '<div class="col-md-6 col-md-offset-1">' . "\n";
        echo '________installments of $____________each.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row"><br><br>' . "\n";
        echo 'The first installment being due and payable on or before the ' . "\n";
        echo '_________' . "\n";
        echo ' day of ' . "\n";
        echo '_________' . "\n";
        echo ' and on installment to become due and payable on or before each succeeding ' . "\n";
        echo '_________' . "\n";
        echo ' thereafter until the whole principal and interest has been paid.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row"><br><br>' . "\n";
        echo '<b style="font-size:130%">&emsp;&emsp;REMEDIES.</b> It is agreed that time is of the essence of ' . "\n";
        echo 'this agreement, and that in the event of default in the payment of any installment of' . "\n";
        echo ' principal or interest when due, the holder of this note may declare the entirety of' . "\n";
        echo ' the note evidence hereby, immediately due and payble without notice, and failure to' . "\n";
        echo ' exercise option shall not ocnstitute a waviver on the part of' . "\n";
        echo $surety_name . "\n";
        echo '. The undersigned hereby agrees to pay all expenses incurred,' . "\n";
        echo ' including an additional forty five (45%) percent of the amount of principal and' . "\n";
        echo ' interest herein is this matter has to be submitted to an attorney for collection' . "\n";
        echo ' or if collected by suit or through any probate, bankruptcy or any other legal' . "\n";
        echo ' proceeding.  Each maker waives demand, grace, notice presentment for payment and' . "\n";
        echo ' protest and grees and consents that this note and the liens insuring its payment, may' . "\n";
        echo ' be renewed and the time of payment extended without notice, and without releasing any' . "\n";
        echo ' of the parties.' . "\n";
        echo '<br><br><br><br><br></div>' . "\n";
        echo '<div class="col-md-4 col-md-offset-1">' . "\n";
        echo '<div class="row">' . "\n";
        echo '<hr class="hr-line"></hr>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo 'Defendant Signature' . "\n";
        echo '<br><br></div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'suffix_name'));
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        $contact_array = $this->counterpart->getField('postal_address_attribute');
        $primary_contact_found = FALSE;
        foreach($contact_array as $value) {
            if ($value->getField(array('postal_address', 'address_type')) == 1001) {
                $primary_contact_found = $value;
            }
        }
        if (!$primary_contact_found) {
            if (count($contact_array) < 1) {
                $primary_contact_found = new Postal_Address_Attribute();
            } else {
                $primary_contact_found = $contact_array[0];
            }
        }
        echo $primary_contact_found->getField('address_1st_line') . ' ';
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $primary_contact_found->getField('address_2nd_line') . ' ';
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $primary_contact_found->getField('city') . ', ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $stateId = $primary_contact_found->getField('state_id');
        foreach($selectLists['state'] as $value) {
            if ($value['key'] == $stateId) {
                echo $value['name'] . ' ';
                break;
            }
        }
        echo $primary_contact_found->getField('postal_code') . ' ';
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-4 col-md-offset-2">' . "\n";
        echo '<div class="row">' . "\n";
        echo '<hr class="hr-line"></hr>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo 'Indemnitor Signature' . "\n";
        echo '<br><br></div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $matchedIndemnitor->getField(array('indemnitor', 'first_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'middle_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'last_name')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'suffix_name'));
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        $primary_contact_found = $matchedIndemnitor->getField(array('indemnitor', 'postal_address'));
        if (!$primary_contact_found) {
            $primary_contact_found = new Postal_Address();
        }
        /*
        $contact_array = $this->counterpart->getField(array('indemnitor', 'postal_address'));
        $primary_contact_found = FALSE;
        foreach($contact_array as $value) {
            if ($value->getField(array('postal_address', 'address_type')) == 1001) {
                $primary_contact_found = $value;
            }
        }
        if (!$primary_contact_found) {
            if (count($contact_array) < 1) {
                $primary_contact_found = new Postal_Address_Attribute();
            } else {
                $primary_contact_found = $contact_array[0];
            }
        }*/
        echo $primary_contact_found->getField('address_1st_line') . ' ';
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $primary_contact_found->getField('address_2nd_line') . ' ';
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo $primary_contact_found->getField('city') . ', ';
        $stateId = $primary_contact_found->getField('state_id');
        foreach($selectLists['state'] as $value) {
            if ($value['key'] == $stateId) {
                echo $value['name'] . ' ';
                break;
            }
        }
        echo $primary_contact_found->getField('postal_code') . ' ';
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
    }
}

/* End of file print_payment_plan.php */
/* Location: ./application/libraries/print_payment_plan.php */
