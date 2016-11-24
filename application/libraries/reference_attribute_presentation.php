<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_Attribute_Presentation extends Domain_Presentation {
    function echoFields($location = 'reference_attribute-0') {
        $reference = NULL;
        if ($this->counterpart) {
            $reference = $this->counterpart->getField('reference');
        }
        $reference = new Reference_Presentation($reference);
        $reference->setReadOnlyFields($this->readOnlyFields);
        $reference->echoDescription($location . '-reference');
        echo '          <div class="row">' . "\n";
        $reference->echoFields($location . '-reference');
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        $reference->echoAddress($location . '-reference');
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-11">' . "\n";
        echo '              <div class="row">Phone Number</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $reference->echoPhone($location . '-reference');
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
        echo '          </div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">&nbsp;</div>' . "\n";
    }
}

/* End of file reference_attribute_presentation.php */
/* Location: ./application/libraries/reference_attribute_presentation.php */