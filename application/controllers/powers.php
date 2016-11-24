<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Powers extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
       // $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
    }
	
	public function index()
	{
		$page_data['page_name'] = 'Power/power_list';
        $page_data['page_title'] = 'Power';
        $page_data['AllPowers'] = $this->getAllPowers();
        //getAvailablePowers();
        //getInUsePowers();
        $this->load->view('includes/template', $page_data);
		 
	}
    function getAllPowers()
    {
        $this->load->model('power_model');
        $powers = $this->power_model->getAllPowers();
        return $powers;
        
    }

	function addpower()
	{
		echo "saving";
        $this->load->model('power_model');
        $error = '';
        $formSubmit = $this->input->post('submitForm');
        //echo $this->input->post('submitForm');
		if ($formSubmit == 'formSave') { #if user wishes to fields to database
			echo "model calling";
            $error = $this->power_model->Save();
            if (!$error) {
                //redirect('powers/view/');
            }
            $formSubmit = $this->input->post('form_source');
        }
		redirect(site_url() . '/powers');
	}


   
}
