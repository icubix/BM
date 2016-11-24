<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employer_Presentation extends Organization_Presentation {
    public function echoFields($location) {
        parent::echoFields($location, 'employer');
    }

    public function echoPhone($location) {
        parent::echoPhone($location, 'employer');
    }
}

/* End of file employer_presentation.php */
/* Location: ./application/libraries/employer_presentation.php */