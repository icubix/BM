<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Court_Presentation extends Organization_Presentation {
    public function echoFields($location) {
        parent::echoFields($location, 'court');
    }

    public function echoPhone($location) {
        parent::echoPhone($location, 'court');
    }
}

/* End of file court_presentation.php */
/* Location: ./application/libraries/court_presentation.php */