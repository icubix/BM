<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BailBond_Presentation extends Domain_Presentation {
    public function echoFields($location = 'bailbond_instance') {
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $offset = '6';
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2/3 . '">' . "\n";
        echo '                  <div class="row">Folio Number</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'folio_number', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2/3 . '">' . "\n";
        echo '                  <div class="row">Date</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'bond_date', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2/3 . '">' . "\n";
        echo '                  <div class="row">Time</div>' . "\n";
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove BailBond" value="popBailbond-' . $location . '" style="float: right';
        if ($this->readOnlyFields) {
            echo '; display: none';
            }
            echo "\">\n";
            echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'bond_time', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Bond Amount</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'bond_amount', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">Case Number</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'case_number', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">' . "\n";
        echo '                  <div class="row">SPN</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'spn', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        $charge = NULL;
        if ($this->counterpart) {
            $charge = $this->counterpart->getField('charge');
        }
        $charge = new Charge_Presentation($charge);
        $charge->setReadOnlyFields($this->readOnlyFields);
        $charge->echoFields($location . '-charge');
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row">Jail Location</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'jail_location', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset*2 . '">' . "\n";
        echo '                  <div class="row">Indemnitor</div>' . "\n";
        echo '                  <div class="row">' . "\n";
        echo '                ';
        $this->echoSelect($location, 'indemnitor_id', $selectLists['indemnitorList'], '0', 'col-sm-2 col-md-9');
        echo '                  </div>' . "\n";
        echo '                </div>' . "\n";
        echo '              </div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                  <h1 class="text-left defendant-title">' . "\n";
        echo '                          <b>Powers</b></h1>' . "\n";
        echo '              </div>' . "\n";
        $power = NULL;
        if ($this->counterpart) {
            $power = $this->counterpart->getField('power');
        }
        $power = new Power_Presentation($power);
        $power->setReadOnlyFields($this->readOnlyFields);
        $power->echoFields($location . '-power');
        echo '              <br><br><br>' . "\n";
    }
}

/* End of file bailbond_presentation.php */
/* Location: ./application/libraries/bailbond_presentation.php */