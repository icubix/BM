<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Presentation extends Domain_Presentation {
    public function echoFields($location = 'user_instance', $isSelf = FALSE) {
        $offset = '6';
        echo '   <div class="row">' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '       <div class="row">User ID</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $tempReadOnlyFields = $this->readOnlyFields;
        $this->readOnlyFields = TRUE;
        $this->echoOneField($location, 'party_role_id', 'col-sm-2 col-md-9');
        $this->readOnlyFields = $tempReadOnlyFields;
        echo '       </div>' . "\n";
        echo '     </div>' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '       <div class="row">User Level</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        if ($isSelf) {
            $this->echoSelect($location, 'user_level', $selectLists['selfLevel'], 0, 'col-sm-2 col-md-9');
        } else {
            $this->echoSelect($location, 'user_level', $selectLists['userLevels'], 0, 'col-sm-2 col-md-9');
        }
        echo '       </div>' . "\n";
        echo '     </div>' . "\n";
        echo '   </div>' . "\n";
        echo '   <div class="row">' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '       <div class="row">Party Type</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $this->echoSelect($location, 'description', $selectLists['partyType'], 'Person', 'col-sm-2 col-md-9');
        echo '       </div>' . "\n";
        echo '     </div>' . "\n";
        echo '   </div>' . "\n";
        echo '<script>' . "\n";
        echo '    document.getElementsByName("' . $location . '-description")[0].onchange = function() {' . "\n";
        echo "         this.form.submit();" , "\n";
        echo '    };' . "\n";
        echo '</script>' . "\n";
        echo '   <div class="row">' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '       <div class="row">Name</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $party = NULL;
        if ($this->counterpart) {
            if ($this->counterpart->getField('description') == 'Organization') {
                $party = $this->counterpart->getField('organization');
                $party = new Organization_Presentation($party);
                $party->setReadOnlyFields($this->readOnlyFields);
                $party->echoFields($location . '-organization');
            } else {
                $party = $this->counterpart->getField('person');
                $party = new Person_Presentation($party);
                $party->setReadOnlyFields($this->readOnlyFields);
                $party->echoFields($location . '-person');
            }
        } else {
            $party = new Person_Presentation($party);
            $party->setReadOnlyFields($this->readOnlyFields);
            $party->echoFields($location . '-person');
        }
        echo '       </div>' . "\n";
        echo '     </div>' . "\n";
        echo '   </div>' . "\n";
        #can't change your own experation date
        if (!$isSelf) {
            echo '   <div class="row">' . "\n";
            echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
            echo '       <div class="row">Account Expires</div>' . "\n";
            echo '       <div class="row">' . "\n";
            echo '         ';
            $this->echoSelect($location, 'expires', $selectLists['yesno'], 0, 'col-sm-2 col-md-9');
            echo '       </div>' . "\n";
            echo '     </div>' . "\n";
            echo '   </div>' . "\n";
            echo '   <div class="row">' . "\n";
            echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
            echo '       <div class="row">Expiration</div>' . "\n";
            echo '       <div class="row">' . "\n";
            echo '         ';
            $this->echoOneField($location, 'thru_date', 'col-sm-2 col-md-9');
            echo '       </div>' . "\n";
            echo '     </div>' . "\n";
            echo '   </div>' . "\n";
        }
        echo '   <hr class="hr-line"></hr>' . "\n";
        echo '   <div class="row">' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '       <div class="row"><h4 class="text-left defendant-title">' . "\n";
        echo '         Contacts' . "\n";
        echo '       </h4></div>' . "\n";
        echo '         ';
        $contacts = NULL;
        if ($this->counterpart) {
            $contacts = $this->counterpart->getField('contacts');
            $index = 0;
            foreach ($contacts as $row) {
                if (is_a($row, 'Postal_Address')) {
                    $row = new Postal_Address_Presentation($row);
                } elseif (is_a($row, 'Electronic_Address')) {
                    $row = new Electronic_Address_Presentation($row);
                } elseif (is_a($row, 'Telecommunication_Number')) {
                    $row = new Telecommunication_Number_Presentation($row);
                } else {
                    $row = new Telecommunication_Number_Presentation(NULL);
                }
                $row->setReadOnlyFields($this->readOnlyFields);
                $row->echoFields($location . '-contacts-' . $index);
                $index += 1;
                echo '         <div class="row">&nbsp;</div>' . "\n";
            }
        } else {
            $contacts = new Electronic_Address_Presentation(new Electronic_Address());
            $contacts->setReadOnlyFields($this->readOnlyFields);
            $contacts->echoFields($location . '-contacts-0');
        }
        echo '     </div>' . "\n";
        echo '   </div>' . "\n";
    }

    public function echoPassword($isSelf = FALSE) {
        $offset = '5';
        if ($isSelf) {
            echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
            echo '       <div class="row">Current Password</div>' . "\n";
            echo '       <div class="row">' . "\n";
            echo '         ';
            $this->echoOneField('na', 'current_password', 'col-sm-2 col-md-9', 'password');
            echo '       </div>' . "\n";
            echo '     </div>' . "\n";
        }
        echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '       <div class="row">New Password</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $this->echoOneField('na', 'new_password', 'col-sm-2 col-md-9', 'password');
        echo '       </div>' . "\n";
        echo '     </div>' . "\n";
        echo '     <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '       <div class="row">Confirm New Password</div>' . "\n";
        echo '       <div class="row">' . "\n";
        echo '         ';
        $this->echoOneField('na', 'new_password_confirm', 'col-sm-2 col-md-9', 'password');
        echo '        </div>' . "\n";
        echo '        <div class="row" style="padding-left: 20px;">' . "\n";
        echo '          *Minimum 6 characters' . "\n";
        echo '        </div>' . "\n";
        echo '      </div>' . "\n";
        echo '      <input type="hidden" name="passwordBoxShown" value="1" />' . "\n";
    }

    public function echoWait($location = 'user') {
        echo '    <div class="row" style="padding-left: 20px;">' . "\n";
        if ($this->counterpart->getField('failed_login_wait') == 'None') {
            echo '    No wait time.' . "\n";
        } else {
            echo '    After '
                    . $this->counterpart->getField('failed_attempts')
                    . ' failed attempts since ' . $this->counterpart->getField('failed_login_first')
                    . ' the wait between attempts is '
                    . $this->counterpart->getField('failed_login_wait') . "\n";
        }
        echo '    </div>' . "\n";
    }
}

/* End of file user_presentation.php */
/* Location: ./application/libraries/user_presentation.php */