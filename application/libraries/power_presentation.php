<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power_Presentation extends Domain_Presentation {
    public function echoFields($location = 'power') {
        $offset = '6';
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row" style="font-size:115%;">Power Number</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'power_number', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row" style="font-size:115%;">Power Amount</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'power_amount', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row" style="font-size:115%;">Surety</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $surety = NULL;
        if ($this->counterpart) {
            $surety = $this->counterpart->getField('surety');
        }
        $surety = new Surety_Presentation($surety);
        $surety->setReadOnlyFields($this->readOnlyFields);
        $surety->echoFields($location . '-surety');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row" style="font-size:115%;">Executing Agent</div>' . "\n";
        echo '                  <div class="row" style="font-size:95%;">' . "\n";
        echo '                ';
        $agent = NULL;
        if ($this->counterpart) {
            $agent = $this->counterpart->getField('agent');
        }
        $agent = new Agent_Presentation($agent);
        $agent->setReadOnlyFields($this->readOnlyFields);
        $agent->echoFields($location . '-agent');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }
}

/* End of file power_presentation.php */
/* Location: ./application/libraries/power_presentation.php */