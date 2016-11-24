<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surety_Presentation extends Organization_Presentation {
    public function echoFields($location) {
        parent::echoFields($location, 'surety');
    }

    public function echoPhone($location) {
        parent::echoPhone($location, 'surety');
    }
}

/* End of file surety_presentation.php */
/* Location: ./application/libraries/surety_presentation.php */