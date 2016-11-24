<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Electronic_Address_Presentation extends Domain_Presentation {
    function echoFields($location = 'electronic_address') {
        echo '<div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">&nbsp;</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $this->echoSelect($location, 'email_type', $selectLists['email'], 1202, 'col-sm-2 col-md-12');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3 col-md-offset-1">' . "\n";
        echo '              <div class="row">Email</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'electronic_address', 'col-sm-2 col-md-12');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3 col-md-offset-1">' . "\n";
        echo '              <div class="row">Note</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'email_desc', 'col-sm-2 col-md-10');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Email" value="popEmail-' . $location . '" style="float: center';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '           </div>' . "\n";
    }
}

/* End of file electronic_address_presentation.php */
/* Location: ./application/libraries/electronic_address_presentation.php */