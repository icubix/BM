<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address_Presentation extends Domain_Presentation {
    function echoFields($location, $removeButton = TRUE) {
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-10">' . "\n";
        echo '              <div class="row">Address Line 1</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'address_1st_line', 'col-sm-2 col-md-11');
        if ($removeButton) {
            echo '              <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
            echo 'title="Remove Address" value="popAddress-' . $location . '" style="float: right';
            if ($this->readOnlyFields) {
                echo '; display: none';
            }
            echo "\">\n";
            echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        }
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-10">' . "\n";
        echo '              <div class="row">Address Line 2</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'address_2nd_line', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">City</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'city', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">State</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $this->echoSelect($location, 'state_id', $selectLists['state'], 200014, 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Zip Code</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'postal_code', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
    }
}

/* End of file postal_address_presentation.php */
/* Location: ./application/libraries/postal_address_presentation.php */