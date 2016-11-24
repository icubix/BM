<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indemnitor_Presentation extends Person_Presentation {
    #this overrides the spacing of person_presentation so the remove indemnitor button fits
    public function echoFields($location = 'indemnitor') {
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">First name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'first_name', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Middle Name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'middle_name', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-3">' . "\n";
        echo '              <div class="row">Last name</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'last_name', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-1 col-md-2">' . "\n";
        echo '              <div class="row">Suffix</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'suffix_name', 'col-sm-1 col-md-9');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
    }
}

/* End of file indemnitor_presentation.php */
/* Location: ./application/libraries/indemnitor_presentation.php */