<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Telecommunication_Number_Presentation extends Domain_Presentation {
    function echoFields($location = 'telecommunication_number-1') {
        echo '<div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">&nbsp;</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $this->echoSelect($location, 'phone_type', $selectLists['telcomno'], 1101, 'col-sm-2 col-md-12');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3 col-md-offset-1">' . "\n";
        echo '              <div class="row">Number</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'contact_number', 'col-sm-2 col-md-12');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3 col-md-offset-1">' . "\n";
        echo '              <div class="row">Note</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'phone_desc', 'col-sm-2 col-md-10');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Phone" value="popPhone-' . $location . '" style="float: center';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '           </div>' . "\n";
    }

    function echoPhone($location, $numberType) {
        echo '            ';
        $this->echoOneField($location, 'phone_no', 'col-sm-2 col-md-11');
    }
}

/* End of file telecommunication_number_presentation.php */
/* Location: ./application/libraries/telecommunication_number_presentation.php */