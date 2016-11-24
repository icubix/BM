<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_Attribute_Presentation extends Domain_Presentation {
    function echoFields($location = 'vehicle_attribute') {
        echo '<div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-1">' . "\n";
        echo '              <div class="row">Year</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'year_built', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-2">' . "\n";
        echo '              <div class="row">Make</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'make', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-2">' . "\n";
        echo '              <div class="row">Model</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'model', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-1 col-md-2">' . "\n";
        echo '              <div class="row">Color</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'color', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-1 col-md-2">' . "\n";
        echo '              <div class="row">Tag Number</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'tag_number', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-1 col-md-2">' . "\n";
        echo '              <div class="row">State</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $this->echoSelect($location, 'state_id', $selectLists['state'], 200014, 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Amount Owed</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'amount_owed', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Lien Holder</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'lien_holder', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Insurance Company</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'insurer', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Agent</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'vehicle_agent', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
    }
}

/* End of file vehicle_attribute_presentation.php */
/* Location: ./application/libraries/vehicle_attribute_presentation.php */