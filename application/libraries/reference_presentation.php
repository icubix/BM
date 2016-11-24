<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference_Presentation extends Person_Presentation {
    public function echoDescription($location) {
        $selectLists = unserialize($_SESSION['defendantSelectLists']);
        echo '          <div class="col-sm-2 col-md-10">' . "\n";
        echo '            ';
        $this->echoSelect($location, 'party_role_desc', $selectLists['referenceType'], 'Friend', 'col-sm-2 col-md-2');
        echo '                <button type="submit" class="btn btn-default btn-xs" name="submitForm" ';
        #next line we remove the -reference from end of $location
        # because what we want to remove is the reference_attribute holding this
        echo 'title="Remove Reference" value="popReference-' . substr($location, 0, -10) . '" style="float: right';
        if ($this->readOnlyFields) {
            echo '; display: none';
        }
        echo "\">\n";
        echo '                <span class="glyphicon glyphicon-remove-sign"></span></button>' . "\n";
        echo '          </div>' . "\n";
    }
}

/* End of file reference_presentation.php */
/* Location: ./application/libraries/reference_presentation.php */