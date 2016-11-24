<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Child_Attribute_Presentation extends Domain_Presentation {
    public function echoFields($location = 'child_attribute-0') {
        echo '<div class="row">' . "\n";
        $child = NULL;
        if ($this->counterpart) {
            $child = $this->counterpart->getField('child');
        }
        $child = new Child_Presentation($child);
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Child" value="popChild-' . $location . '" style="float: center';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        $child->setReadOnlyFields($this->readOnlyFields);
        $child->echoFields($location . '-child');
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-6">' . "\n";
        echo '              <div class="row">School or Employer</div>' . "\n";
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
        echo '              <div class="row">Date of Birth</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'date_of_birth', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">School or Employer Phone</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $employer->echoPhone($location . '-employer');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <br><br>' . "\n";
    }
}

/* End of file child_attribute_presentation.php */
/* Location: ./application/libraries/child_attribute_presentation.php */