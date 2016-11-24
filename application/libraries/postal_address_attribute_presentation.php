<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postal_Address_Attribute_Presentation extends Domain_Presentation {
    function echoFields($location = 'postal_address_attribute-0') {
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Address Type</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        $this->echoSelect($location, 'address_type', $selectLists['postal'], 1001, 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Description</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                ';
        $this->echoOneField($location, 'address_desc', 'col-sm-2 col-md-11');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        echo 'title="Remove Address" value="popAddress-' . $location . '" style="float: right';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
        $postal_address = NULL;
        if ($this->counterpart) {
            $postal_address = $this->counterpart->getField('postal_address');
        }
        $postal_address = new Postal_Address_Presentation($postal_address);
        $postal_address->setReadOnlyFields($this->readOnlyFields);
        $postal_address->echoFields($location . '-postal_address', FALSE);
        echo '                <div class="row">&nbsp;</div>' . "\n";
        echo '          <div class="row">' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Rent or Own</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                  ';
        $this->echoSelect($location, 'rent_not_own', $selectLists['rentorown'], 1, 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '            <div class="col-sm-2 col-md-5">' . "\n";
        echo '              <div class="row">Landloard or Mortgage</div>' . "\n";
        echo '              <div class="row">' . "\n";
        echo '                  ';
        $this->echoOneField($location, 'landlord_mortgage', 'col-sm-2 col-md-11');
        echo '              </div>' . "\n";
        echo '            </div>' . "\n";
        echo '          </div>' . "\n";
    }
}

/* End of file postal_address_attribute_presentation.php */
/* Location: ./application/libraries/postal_address_attribute_presentation.php */