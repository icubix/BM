<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard extends CI_Controller {

	function __construct()
    {
        parent::__construct();
 
        /* Standard Libraries of codeigniter are required */
       // $this->load->database();
        $this->load->helper('url');
       
        
  }
	public function index()
	{
		$page_data['page_name'] = 'app/dashboard';
        $page_data['page_title'] = 'Dashboard';
        $this->load->view('includes/template', $page_data);
		 
	}


   
}
