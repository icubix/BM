<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Indemnitor extends Person {
    function __construct() {
        parent::__construct();
        $this->dict['telecommunication_number'] = array(new Telecommunication_Number());
        $this->dict['postal_address'] = new Postal_Address();
    }
}

/* End of file indemnitor.php */
/* Location: ./application/libraries/indemnitor.php */