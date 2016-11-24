<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    
    function __construct()
    {
        parent::__construct();
        // $this->load->model('crud_model');
       // $this->load->database();
        $this->load->helper('url');
        $this->load->helper('form');
         
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
         $this->load->view('login');
    }

    
    
   
    /*******LOGOUT FUNCTION *******/
    function logout()
    {
        $this->session->unset_userdata();
        $this->session->sess_destroy();
        
        redirect(base_url() . 'index.php?login', 'refresh');
    }
    
}
