<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_Application extends Domain_Presentation {
    function echoFields($location = 'print_application') {
        if (!$this->counterpart) {
            $this->counterpart = new Domain();
        }
        $this->readOnlyFields = true;
        echo "<br>\n" . '<div class="row">' . "\n";
        echo '<h2 style="font-style: italic; text-decoration: underline; text-align: center">' . "\n";
        echo 'Bail Bond Underwriting Application & Indemnity Contract</h2>' . "\n";
        echo "</div><br>\n";/*
        echo '<div class="row">' . "\n";
        echo 'Agent: <b>';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'first_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'middle_name')) . ' ';
        echo $this->counterpart->getField(array('personal_attribute', 'defendant', 'last_name')) . '</b> ';
        echo 'DATE OF APPLICATION: <b>' . date('m/d/Y') . "</b><br>\n";
        echo 'Warrent #';*/
    }
}

/* End of file print_application.php */
/* Location: ./application/libraries/print_application.php */