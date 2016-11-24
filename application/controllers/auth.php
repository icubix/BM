<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller{

	function __construct()
    {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
        $this->load->helper('url');
    }

    public function login($error = NULL, $url = NULL) {
        $data['main_content'] = 'settings/login';
        if ($error == 'redirect') {
            $error = NULL;
        }
        $data['error'] = $error;
        $data['url'] = $url;
        $this->load->view('includes/template', $data);
    }


	public function index(){
		$this->load->helper('form');
		$this->load->view('settings/register');
	}

    function validate_credentials()
    {
         // echo "testing";
         // alert("kali");
         $this->load->model('auth_model');

        $userDataOrError = $this->auth_model->validate();
        echo($userDataOrError);
        //print_r($userDataOrError);
        //exit();
         if(is_array($userDataOrError)) { 
             $this->session->set_userdata($userDataOrError);
             if ($userDataOrError['user_level'] >= 40) {
                //redirect(base_url() . 'index.php?/dashboard');
                redirect('settings/loadUserToEdit/' . $userDataOrError['party_role_id']);
              }
              else
              {
                $this->login($userDataOrError,$url);
              }
         }
    }

    function logout()
    {
        
        $this->session->sess_destroy();
        session_unset();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        session_regenerate_id(true);
        $this->login();
    } 

	 function register_credentials() {
        $this->load->model('auth_model');
        
        $userData = $this->auth_model->register();
        
        if($userData) { // if the user's credentials validated...
            $this->session->set_userdata($userData);
            redirect('settings/loadUserToEdit/' . $userData['party_role_id']);
        } else { // incorrect username or password
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
    }
}