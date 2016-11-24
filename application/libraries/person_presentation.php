<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person_Presentation extends Party_Role_Presentation {
    public function echoFields($page = 'readonly') {
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">First name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($page, 'first_name', 'col-sm-2 col-md-11','text');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Middle Name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($page, 'middle_name', 'col-sm-2 col-md-11','text');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Last name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($page, 'last_name', 'col-sm-2 col-md-11','text');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-1 col-md-3">' . "\n";
        echo '              <div class="row">Suffix</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($page, 'suffix_name', 'col-sm-1 col-md-6','text');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
    }

    public function echoSSN($page) {
        echo '              <div class="row">Social Security No</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($page, 'ssn', 'col-sm-2 col-md-11','text');
        echo '              </div>' . "\n";
    }

    public function echoDescription($page) {
        echo '          <div class="row">' . "\n";
        echo '            ';
        $localReadOnlyFields = $this->readOnlyFields;
        $this->readOnlyFields = TRUE;
        $this->echoOneField($page, 'party_role_desc', 'col-sm-2 col-md-2','text');
        $this->readOnlyFields = $localReadOnlyFields;
        echo '          </div>' . "\n";
    }

    public function echoPhone($page,$phoneType) {
        parent::echoPhone($page, 'person');
    }
}

/* End of file person_presentation.php */
/* Location: ./application/libraries/person_presentation.php */