<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class workflows extends CI_Controller {

	function __construct()
    {
        parent::__construct();
 
        /* Standard Libraries of codeigniter are required */
       // $this->load->database();
        $this->load->helper('url');
       
        
  }
	public function index()
	{
		 
		
		$page_data['page_name'] = 'app/workflows';
        $page_data['page_title'] = 'Work Flow';
        $this->load->view('includes/template', $page_data);
		 
	}


   
}
