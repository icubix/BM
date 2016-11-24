<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class newcase extends CI_Controller {

	function __construct()
    {
        parent::__construct();
 
        /* Standard Libraries of codeigniter are required */
       // $this->load->database();
        $this->load->helper('url');
       
        
  }
	public function index()
	{
		 
		
		$page_data['page_name'] = 'app/newcase';
        $page_data['page_title'] = 'New Case';
        $this->load->view('includes/template', $page_data);
		 
	}
public function defendentdetails()
	{
		 
		
		$page_data['page_name'] = 'app/defendentdetails';
        $page_data['page_title'] = 'Defendent';
        $this->load->view('includes/template', $page_data);
		 
	}


   
}
