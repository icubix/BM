<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employment_Attribute_Presentation extends Domain_Presentation {
    public function echoFields($location = 'employment_attribute') {
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Occupation</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'occupation', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-6">' . "\n";
        echo '              <div class="row">Employer</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $employer = NULL;
        if ($this->counterpart) {
            $employer = $this->counterpart->getField('employer');
        }
        $employer = new Employer_Presentation($employer);
        $employer->setReadOnlyFields($this->readOnlyFields);
        $employer->echoFields($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-4">' . "\n";
        echo '              <div class="row">Time with Employer</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'time_with_employer', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-6">' . "\n";
        echo '              <div class="row">Job Title</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'job_title', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-4">' . "\n";
        echo '              <div class="row">Shift</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'shift', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
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
        echo '          <div class="row">' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '            <div class="col-sm-2 col-md-12">' . "\n";
        echo '              <div class="row">Supervisor</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $supervisor = NULL;
        if ($this->counterpart) {
            $supervisor = $this->counterpart->getField('supervisor');
        }
        $supervisor = new Supervisor_Presentation($supervisor);
        $supervisor->setReadOnlyFields($this->readOnlyFields);
        $supervisor->echoFields($location . '-supervisor');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-6">' . "\n";
        echo '              <div class="row">Union</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $union = NULL;
        if ($this->counterpart) {
            $union = $this->counterpart->getField('union');
        }
        $union = new Union_Presentation($union);
        $union->setReadOnlyFields($this->readOnlyFields);
        $union->echoFields($location . '-union');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Union Local Phone Number</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $union->echoPhone($location . '-union');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
    }
}

/* End of file employment_attribute_presentation.php */
/* Location: ./application/libraries/employment_attribute_presentation.php */