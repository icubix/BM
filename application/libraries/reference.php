<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference extends Person {
    function __construct() {
        parent::__construct();
        $this->dict['telecommunication_number'] = array(new Telecommunication_Number());
        $this->dict['postal_address'] = new Postal_Address();
    }
}

/* End of file reference.php */
/* Location: ./application/libraries/reference.php */