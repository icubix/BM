<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Party_Role_Presentation extends Party_Presentation {
    protected function echoPhone($page, $phoneType) {
        $telecommunication_numbers = NULL;
        if ($this->counterpart) {
            $telecommunication_numbers = $this->counterpart->getField('telecommunication_number');
        }
        echo '        <div id="addPhone-' . $page .'-telecommunication_number" class="col-sm-2 col-md-11">';
        $fieldCount = 0;
        if ($telecommunication_numbers) {
            $fieldCount = count($telecommunication_numbers);
        }
        if ($fieldCount == 0) {
            $telecommunication_number_presentation =
                    new Telecommunication_Number_Presentation(NULL);
            $telecommunication_number_presentation->setReadOnlyFields($this->readOnlyFields);
            $telecommunication_number_presentation->echoFields($page . '-telecommunication_number-0');
        } #else
        $i = 0;
        while($i < $fieldCount) {
            $telecommunication_number_presentation =
            new Telecommunication_Number_Presentation($telecommunication_numbers[$i]);
            $telecommunication_number_presentation->setReadOnlyFields($this->readOnlyFields);
            $telecommunication_number_presentation->echoFields($page . '-telecommunication_number-' . $i);
            $i += 1;
        }
        echo '              </div>';
        echo '              <div id="popPhone-' . $page .'-telecommunication_number" class="col-sm-2 col-md-11">';
        echo '                <div class="row">&nbsp;</div>';
        echo '                <button type="submit" class="btn btn-default btn-xs" ';
        echo 'name="submitForm" value="addPhone-' . $page .'-telecommunication_number"';
        if ($this->readOnlyFields) {
            echo 'style="display: none"';
        }
        echo '>';
        echo '                  <img src="' . base_url();
        echo 'images/glyphicons_190_circle_plus.png" alt="">';
        echo '                  &nbsp;&nbsp;&nbsp;&nbsp;Add Phone No.';
        echo '&nbsp;&nbsp;&nbsp;&nbsp;</button>';
        echo '              </div>';
    }
    
    public function echoAddress($page) {
        $postal_address = NULL;
        if ($this->counterpart) {
            $postal_address = $this->counterpart->getField('postal_address');
        }
        $postal_address = new Postal_Address_Presentation($postal_address);
        $postal_address->setReadOnlyFields($this->readOnlyFields);
        $postal_address->echoFields($page . '-postal_address', FALSE);
    }
}

/* End of file party_presentation.php */
/* Location: ./application/libraries/party_presentation.php */