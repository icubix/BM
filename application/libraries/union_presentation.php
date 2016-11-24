<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Union_Presentation extends Organization_Presentation {
    public function echoFields($location) {
        parent::echoFields($location, 'union');
    }

    public function echoPhone($location) {
        parent::echoPhone($location, 'union');
    }
}

/* End of file union_presentation.php */
/* Location: ./application/libraries/union_presentation.php */