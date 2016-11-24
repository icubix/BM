<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charge_Presentation extends Domain_Presentation {
    public function echoFields($location = 'charge') {
        $offset = '6';
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row">Charge</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'charge_desc', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row">Charge Type</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'charge_type', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row">Court</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $court = NULL;
        if ($this->counterpart) {
            $court = $this->counterpart->getField('court');
        }
        $court = new Court_Presentation($court);
        $court->setReadOnlyFields($this->readOnlyFields);
        $court->echoFields($location . '-court');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
    }
}

/* End of file charge_presentation.php */
/* Location: ./application/libraries/charge_presentation.php */