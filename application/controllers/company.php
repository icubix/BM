<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class company extends CI_Controller {

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
		$page_data['AllOrganization'] = $this->getAllOrganizations();
		$page_data['page_name'] = 'company/company';
        $page_data['page_title'] = 'Company';
        $this->load->view('includes/template', $page_data);
		 
	}

	public function getAllOrganizations()
	{
		$this->load->model('company_model');
        $orgs = $this->company_model->getAllOrganizations();
        return $orgs;
	}

	public function addcompany()
	{
		$this->load->model('company_model');
		$error = '';
		$formsubmit = $this->input->post('submitForm');

		if ($formsubmit == 'formSave') { #if user wishes to fields to database
			
			echo "model calling";
            $error = $this->company_model->addNewCompany();
            if (!$error) {
                //redirect('powers/view/');
            }
            $formSubmit = $this->input->post('form_source');
        }
		redirect(site_url() . '/company');
	}

   
}
