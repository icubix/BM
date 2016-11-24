<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spouse_Attribute_Presentation extends Domain_Presentation {
    public function echoFields($location = 'spouse_attribute') {
        echo '<div class="row">' . "\n";
        $spouse = NULL;
        if ($this->counterpart) {
            $spouse = $this->counterpart->getField('spouse');
        }
        $spouse = new Spouse_Presentation($spouse);
        $spouse->setReadOnlyFields($this->readOnlyFields);
        $spouse->echoFields($location . '-spouse');
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Date of Birth</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'date_of_birth', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '                ';
        $spouse->echoSSN($location . '-spouse');
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Maiden Name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'maiden_name', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Occupation</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'occupation', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Employer Name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        $employer = NULL;
        if ($this->counterpart) {
            $employer = $this->counterpart->getField('employer');
        }
        $employer = new Employer_Presentation($employer);
        $employer->setReadOnlyFields($this->readOnlyFields);
        $employer->echoFields($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Time with Empolyer</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'time_with_employer', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Job Title</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'job_title', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Shift</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'shift', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Employer Phone</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $employer->echoPhone($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        $employer->echoAddress($location . '-employer');
    }
}

/* End of file spouse_attribute_presentation.php */
/* Location: ./application/libraries/spouse_attribute_presentation.php */