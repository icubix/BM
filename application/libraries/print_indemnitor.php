<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Indemnitor extends Domain_Presentation {
    function echoFields($location = 'print_indemnitor', $indemnitor_id) {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        $this->readOnlyFields = true;
        echo "<br>\n" . '<div class="row">' . "\n";
        echo '<h2 style="text-decoration: underline; text-align: center">' . "\n";
        echo 'Bail Bond INDEMNITOR\'S PROMISES</h2>' . "\n";
        echo "</div>\n";
        echo '<div class="row">' . "\n";
        echo '<h3 style="font-style: italic; text-align: center">' . "\n";
        echo '***** Read Carefully, You Are Assuming Specific Obligations !!! *****</h3>' . "\n";
        echo "</div>\n<br>\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Defendant\'s name: <b>';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name'));
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Court: <b>' . '' . "</b>\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Indemnitor\'s Name: <b>';
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
        echo $matchedIndemnitor->getField(array('indemnitor', 'suffix_name')) . ' </b>';
        echo '</div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'SS#: <b>' . $matchedIndemnitor->getField('ssn') . "</b>\n";
        echo '</div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Home address: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'address_1st_line')) . ' ';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'address_2nd_line')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Home Phone: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'telecommunication_number', 0, 'contact_number')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'City: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'city')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'State: <b>';
        $state =  $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'state_id'));
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
        echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'Zip Code: <b>';
        echo $matchedIndemnitor->getField(array('indemnitor', 'postal_address', 'postal_code')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'DL#: <b>';
        echo $matchedIndemnitor->getField('driver_license_no') . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Employer: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'organization_name'));
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Work Phone: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'organization_name'));
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-8">' . "\n";
        echo 'Work address: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'postal_address', 'address_1st_line')) . ' ';
        echo $matchedIndemnitor->getField(array('employer', 'postal_address', 'address_2nd_line')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-4 col-md-4">' . "\n";
        echo 'Work Phone: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'telecommunication_number', 0, 'contact_number')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'City: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'postal_address', 'city')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '<div class="col-xs-3 col-md-3">' . "\n";
        echo 'State: <b>';
        $state =  $matchedIndemnitor->getField(array('employer', 'postal_address', 'state_id'));
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
        echo '<div class="col-xs-3 col-md-2">' . "\n";
        echo 'Zip Code: <b>';
        echo $matchedIndemnitor->getField(array('employer', 'postal_address', 'postal_code')) . "\n";
        echo ' </b> </div>' . "\n";
        echo '</div><br><br><br>' . "\n";
        echo '<div class="col-md-11">' . "\n";
        echo '<b> 1. Consideraation. </b> The consideration or cause of this agreement is the posting of a bail bond by surety on behalf of defendant in the above named court.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11">' . "\n";
        echo '<b> 2. Indemnification. </b> I, the undersigned hereby agree to save and hold the surety and its agents and/or assigns from any loss whatsoever resulting from the failure of the above named defendant to appear in court as ordered. I, the undersigned, hereby agree to pay all costs (<b><u>$500.00 minimum charge</u></b> associated with the failure of the above named defendant to appear in court as ordered, in U.S. Currency to surety, its agents and/or assigns upon the failure of the above named defendant ot appear in court as ordered.  A copy of a judgement of bond forteiture naming the above named defendant shall be prime facie evidence of loss sustained by surety and its agents and/or assigns.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11">' . "\n";
        echo '<b> 3. Jurisdiction and Venue. </b> I, the undersigned, hereby agree and stipulate that any Court of proper jurisdiction within the <b>County of Harris, State of Texas</b> is convenient and proper form to litigate any dispute under this agreement.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11">' . "\n";
        echo '<b> 4. Waiver and Authorization. </b> I, the undersigned hereby waive any and all rights, benefits and protection provided to me pursuant to the Fair Dept Collection Act and any other similar state and/or local statute. Additionally, I hereby authorize the holder of this instrument to utilize any information given above to pursue the collection of any dept that may be owed.' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11">' . "\n";
        echo '<b> 5. Severability. </b> The Provisions of this agreement are severable and if for any reason any provision of this agreement shall be declared invalid or unenforceable, then such provision or provisions shall be considered as not written and the remainder of this agreement shall remain valid and enforceable.' . "\n";
        echo '</div><br><br><br><br>' . "\n";
        echo '<div class="col-md-11" style="text-align:center">' . "\n";
        echo '___________________________________' . "\n";
        echo '</div>' . "\n";
        echo '<div class="col-md-11" style="text-align:center">' . "\n";
        echo 'Indemnitor\'s Signature' . "\n";
        echo '</div><br><br>' . "\n";
        #Promissory Note
        echo '<div class="col-md-11" style="text-align:center">' . "\n";
        echo '<hr class="hr-line">' . "\n";
        echo '</div>' . "\n";
        echo '<div class="row">' . "\n";
        echo '<h3 style="text-align: center">' . "\n";
        echo 'Promissory Note</h2>' . "\n";
        echo "</div><br>\n";
        echo '<div class="row">' . "\n";
        echo '<div class="col-xs-8 col-md-9">' . "\n";
        echo '$' . "\n";
        echo "</div>\n";
        echo '<div class="col-xs-8 col-md-3">' . "\n";
        echo 'Date' . "\n";
        echo "</div>\n";
        echo "</div>\n";
        echo '<div class="col-md-11">' . "\n";
        echo 'For value received, I the undersigned, unconditionally promise to pay to bearer on demand the amount of ___________________, with interest after demand in the amount of 12%.  The maker of this note and endorsers, guarantors and sureties hereon, hereby severally waive presentment for payment, notice of non-payment, protest, notice of protest, citation and service of petition, all legal delays and confess judgment in favor of any legal holder, and all pleas of division and discussion, and agree that the time of payment hereof may be extended from time to time, one or more times, without notice of extension or extensions and without previous consent hereby binding themselves in solido, unconditionally, and original promissors, for the payment thereof in principal, interest, costs and attorney fees. No delay on the part of the holder hereof and exercising any rights hereunder shall operate as a waiver of such rights.' . "\n";
        echo "</div><br>\n";
        echo '<div class="col-md-11">' . "\n";
        echo 'Should this note not be paid at maturity of when due or demandable, as herein provided, or should this note be placed in the hands of an attorney for any reason the makers, endorsers, guarantors and sureties and each of them hereby agree to pay the fees of such attorney, which are hereby fixed at 331/3% on the amount due on the note together with interest and all costs (<b>$500.00 minimum charge.</b>)' . "\n";
        echo "</div>\n";
    }
}

/* End of file print_indemnitor.php */
/* Location: ./application/libraries/print_indemnitor.php */