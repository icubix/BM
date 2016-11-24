<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indemnitor_Attribute_Presentation extends Domain_Presentation {
    function echoFields($location = 'indemnitor_attribute-0') {
        echo '          <div class="row">' . "\n";
        $indemnitor = NULL;
        if ($this->counterpart) {
            $indemnitor = $this->counterpart->getField('indemnitor');
        }
        $indemnitor = new Indemnitor_Presentation($indemnitor);
        $indemnitor->setReadOnlyFields($this->readOnlyFields);
        $indemnitor->echoFields($location . '-indemnitor');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Indemnitor" value="popIndemnitor-' . $location . '" style="float: right';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '                ';
        $indemnitor->echoSSN($location . '-indemnitor');
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Date of Birth</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'date_of_birth', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Driver License #</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'driver_license_no', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Driver License State</div>' . "\n";
        echo '              <div class="row">' . "\n";
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        echo '                ';
        $this->echoSelect($location, 'driver_license_state_id', $selectLists['state'], 200014, 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Phone No.</div>' . "\n";
        echo '              <div class="row">' . "\n";
        $indemnitor->echoPhone($location . '-indemnitor');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        $indemnitor->echoAddress($location . '-indemnitor');
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-8">' . "\n";
        echo '              <div class="row">Employer</div>' . "\n";
        echo '              <div class="row">' . "\n";
        $employer = NULL;
        if ($this->counterpart) {
            $employer = $this->counterpart->getField('employer');
        }
        $employer = new Employer_Presentation($employer);
        $employer->setReadOnlyFields($this->readOnlyFields);
        echo '                ';
        $employer->echoFields($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Employer Phone</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $employer->echoPhone($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Employer Address</div>' . "\n";
        echo '                ';
        $employer->echoAddress($location . '-employer');
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <br><br><br>' . "\n";
    }
}

/* End of file indemnitor_attribute_presentation.php */
/* Location: ./application/libraries/indemnitor_attribute_presentation.php */