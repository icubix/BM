<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School_Presentation extends Organization_Presentation {
    public function echoFields($page, $i = 0) {
        parent::echoFields($page, 'school', $i);
    }

    public function echoPhone($page, $i = 0) {
        parent::echoPhone($page, 'school', $i);
    }
}

/* End of file school_presentation.php */
/* Location: ./application/libraries/school_presentation.php */