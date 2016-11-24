<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Power extends Domain_Presentation {
    function echoFields($location = 'print_indemnitor') {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        $this->readOnlyFields = true;
        echo "<br>\n" . '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo '<div style="font-size:x-large">' . "\n";
        echo 'POWER AMOUNT' . "\n";
        echo "</div>\n";
        echo '<div style="font-size:xx-large">&nbsp&nbsp' . "\n";
        $power_amount = $this->counterpart->getField(array('bailbond_instance', 'power', 'power_amount'));
        echo $power_amount . "\n";
        echo "</div>\n";
        echo "</div>\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo '<div>' . "\n";
        echo 'POWER NO.' . "\n";
        echo "</div>\n";
        echo '<div style="font-size:x-large">' . "\n";
        echo $this->counterpart->getField(array('bailbond_instance', 'power', 'power_number')) . ' ';
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n<br>\n";

        echo '<div class="col-xs-10 col-md-10 col-md-offset-1">' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-12 col-md-12">' . "\n";
        echo 'Defendant Address: <b>' . "\n";
        $postalList = $this->counterpart->getField('postal_address_attribute');
        $bestAddress = NULL;
        foreach ($postalList as $value) {
            $value = $value->getField('postal_address'); #get object inside attribute
            if ($value->getField('postal_type') == 1001) { #hard coded primary postal address value
                $bestAddress = $value;
                break;
            } elseif (!$bestAddress) { #hold first value if there is one
                $bestAddress = $value;
            }
        }
        if ($bestAddress) {
            echo $bestAddress->getField('address_1st_line') . ' ';
            echo $bestAddress->getField('address_2nd_line') . "\n";
        }
        echo ' </b> </div>' . "\n";
        echo "</div>\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-3 col-md-5">' . "\n";
        echo 'City: <b>';
        if ($bestAddress) {
            echo $bestAddress->getField('city') . "\n";
        }
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-5">' . "\n";
        echo 'State: <b>';
        $state = $bestAddress->getField('state_id');
        $defendantSelectLists = unserialize($_SESSION['defendantSelectLists']);
        $stateFound = NULL;
        foreach ($defendantSelectLists['state'] as $value) {
            if ($value['key'] == $state) {
                $stateFound = $value['name'];
                break;
            }
        }
        if ($stateFound) {
            echo $stateFound . "\n";
        }
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-3 col-md-5">' . "\n";
        echo 'DOB: <b>';
        echo $this->counterpart->getField(array('personal_attribute', 'date_of_birth')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-5">' . "\n";
        echo 'SS#: <b>';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'ssn')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11" style="font-size:x-large; text-align:center; color:LightGray">' . "\n";
        if ($power_amount) {
            if (preg_match('#\${0,1}([0-9][0-9]*)[,\.]([0-9][0-9]*)\b#', $power_amount, $matches)) {
                $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                echo strtoupper($formatter->format($matches[1]) . ' dollars and ' . $formatter->format($matches[2]) . ' cents' . "\n");
            } elseif (preg_match('#\${0,1}([0-9][0-9]*)[,\.]{0,1}\b#', $power_amount, $matches)) {
                $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                echo strtoupper($formatter->format($matches[1]) . ' dollars' . "\n");
            } else {
                echo $power_amount . "\n";
            }
        } else {
            echo "&nbsp\n";
        }
        echo '</div>' . "\n";
        $indemnitor_id = $this->counterpart->getField(array('bailbond_instance', 'indemnitor_id'));
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
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-11 col-md-11">' . "\n";
        echo 'Indemnitor address: <b>';
        echo $matchedIndemnitor->getField(array('postal_address', 'address_1st_line')) . ' ';
        echo $matchedIndemnitor->getField(array('postal_address', 'address_2nd_line')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-6 col-md-5">' . "\n";
        echo 'City: <b>';
        echo $matchedIndemnitor->getField(array('postal_address', 'city')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'State: <b>';
        $state =  $matchedIndemnitor->getField(array('postal_address', 'state_id'));
        #list done above
        #$defendantSelectLists = unserialize($_SESSION['defendantSelectLists']);
        $stateFound = NULL;
        foreach ($defendantSelectLists['state'] as $value) {
            if ($value['key'] == $state) {
                $stateFound = $value['name'];
                break;
            }
        }
        if ($stateFound) {
            echo $stateFound . "\n";
        }
        echo ' </b> </div>' . "\n";
        /*echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'Zip Code: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'postal_code')) . "\n";
        echo ' </b> </div>' . "\n";*/
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-11 col-md-11">' . "\n";
        echo 'Phone: <b>';
        echo $matchedIndemnitor->getField(array('telecommunication_number', 0, 'contact_number')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-11 col-md-11">' . "\n";
        echo 'Attorney: <b>';
        #echo $matchedIndemnitor->getField(array('indemnitor', 'telecommunication_number', 0, 'contact_number')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        #end offset-1 back to full left



        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Bond Amount: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'bond_amount')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Appearance Date: <b>';
        #echo $matchedIndemnitor->getField(array('bailbond_instance', 'bond_date')) . "\n";
        echo ' </b> </div>' . "\n";
        /*echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'Zip Code: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'postal_code')) . "\n";
        echo ' </b> </div>' . "\n";*/
        echo '</div>' . "\n";
        
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Defendant: <b>';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'Premium: $<b>';
        echo ' </b> </div>' . "\n";
        /*echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'Zip Code: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'postal_code')) . "\n";
        echo ' </b> </div>' . "\n";*/
        echo '</div>' . "\n";

        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Court: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'charge', 'court', 'organization_name')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'City: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'charge', 'court', 'postal_address', 'city')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-1 col-md-2">' . "\n";
        echo 'State: <b>';
        $state =  $this->counterpart->getField(array('bailbond_instance', 'charge', 'court', 'postal_address', 'state_id'));
        #list done above
        #$defendantSelectLists = unserialize($_SESSION['defendantSelectLists']);
        $stateFound = NULL;
        foreach ($defendantSelectLists['state'] as $value) {
            if ($value['key'] == $state) {
                $stateFound = $value['name'];
                break;
            }
        }
        if ($stateFound) {
            echo $stateFound . "\n";
        }
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Case #: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'case_number'));
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";

        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Offense: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'charge', 'charge_desc'));
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'If REWRITE Original No.: <b>';
        #echo $this->counterpart->getField(array('bailbond_instance', 'charge', 'charge_desc'));
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Executing Agent: <b>';
        echo $this->counterpart->getField(array('bailbond_instance', 'power', 'agent', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('bailbond_instance', 'power', 'agent', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('bailbond_instance', 'power', 'agent', 'last_name')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'Original Amount: $ <b>';
        #echo $this->counterpart->getField(array('bailbond_instance', 'charge', 'charge_desc'));
        echo ' </b> </div>' . "\n";
        echo '</div> <br>' . "\n";

        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-2 col-md-2 col-md-offset-9" style="font-size:large; float:right"><b>' . "\n";
        echo 'AGENT\'S COPY';
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";

    }
}

/* End of file print_power.php */
/* Location: ./application/libraries/print_power.php */