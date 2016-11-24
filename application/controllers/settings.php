<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        /* Standard Libraries of codeigniter are required */
        $this->load->helper('url');
    }

    public function index() {
        $this->load->model('limited_auth_model');
        if (!$this->limited_auth_model->authLevel('40')) {
            #this is not logged, as page is not hidden from logged out users
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        $page_data[ 'page_name' ] = 'settings';
        $this->load->view( 'includes/template', $page_data );
    }

    public function listUsers() {
        $this->load->model('limited_auth_model');
        if (!$this->limited_auth_model->authLevel('40')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        $page_data[ 'page_name' ] = 'settings/users_list';
        $this->load->view( 'includes/template', $page_data );
    }

    public function addNewUser() {
        #no user level restriction on registering
        redirect('auth/register');
    }

    public function loadUserToEdit($party_role_id = 0) {
        $this->load->model('limited_auth_model');
        #only require that user is logged in, as this is useless without
        #loadSingleUser which internally checks if level is high enough
        if (!$this->limited_auth_model->authLevel('0')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        session_start();
        $_SESSION['party_role_id'] = $party_role_id;
        $this->load->model('settings_model');
        $this->settings_model->clearUserdata(); #remove left over data
        if (!$this->settings_model->loadSingleUser($party_role_id)) { #checks internally if id is valid
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        //print_r($party_role_id);
        //exit();
        redirect(base_url() . 'index.php?/settings/editUser/');
        //redirect(base_url() . 'index.php?/dashboard');
    }


    public function editUser() {
        
        $this->load->model('limited_auth_model');
        #only require that user is logged in, as this is useless without
        #loadSingleUser which internally checks if level is high enough
        if (!$this->limited_auth_model->authLevel('0')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        //session_start();
        $this->load->model('settings_model');
        $formSubmit = $this->input->post('submitForm');
        $this->settings_model->setSingleUserData();
        $error = '';
        $editPassword = 'No';
        #if add or pop field button was pressed
        if (substr($formSubmit, 0, 3) == 'add') {
            $this->settings_model->increaseUserFieldCount($formSubmit);
        } elseif (substr($formSubmit, 0, 3) == 'pop') {
            $this->settings_model->decreaseUserFieldCount($formSubmit);
        } elseif ($formSubmit == 'formSave') { #if user wishes to push data to database
            $error = $this->settings_model->saveUser();
            if (!$error) {
                redirect('settings/viewUser/');
            }
        } elseif ($formSubmit == 'pwdEmail') { #send password reset email
        } elseif ($formSubmit == 'attemptsReset') { #lower login wait to 0
            $error = $this->settings_model->resetLoginAttempts();
        } elseif ($formSubmit == 'pwdChange') { #show password change boxes
            $editPassword = 'Yes';
        }
        $page_data['page_name'] = 'settings/users_detail';
        $page_data['page_title'] = 'settings/user_edit_title';
        $page_data['user_instance'] = $this->settings_model->getSingleUser();
        #notes if are you editing yourself
        $page_data['isSelf'] = $this->limited_auth_model->partyRoleMatches(
                $page_data['user_instance']->getField('party_role_id'));
        #if you aren't authLevel 60 and it's not you you can't edit the password
        if (!($page_data['isSelf'] || $this->limited_auth_model->authLevel('60'))) {
            $editPassword = 'Not Able';
        }
        $page_data['editPassword'] = $editPassword;
        $page_data['error'] = $error;
        $this->load->view( 'includes/template', $page_data );
    }

    public function loadUserToView($party_role_id = 0) {
        $this->load->model('limited_auth_model');
        #only require that user is logged in, as this is useless without
        #loadSingleUser which internally checks if level is high enough
        if (!$this->limited_auth_model->authLevel('0')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        session_start();
        $this->load->model('settings_model');
        $this->settings_model->clearUserdata(); #remove left over data
        if (!$this->settings_model->loadSingleUser($party_role_id)) { #checks internally if id is valid
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        redirect('settings/viewUser');
    }

    public function viewUser() {
        $this->load->model('limited_auth_model');
        #only require that user is logged in, as this is useless without
        #loadSingleUser which internally checks if level is high enough
        if (!$this->limited_auth_model->authLevel('0')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        session_start();
        $this->load->model('settings_model');
        $formSubmit = $this->input->post('submitForm');
        if ($formSubmit == 'formEdit') { #if user wishes to edit current user
            redirect('settings/editUser');
        } elseif ($formSubmit == 'attemptsReset') { #lower login wait to 0
            $error = $this->settings_model->resetLoginAttempts();
        }
        $page_data['page_name'] = 'settings/users_detail';
        $page_data['page_title'] = 'settings/user_view_title';
        $page_data['user_instance'] = $this->settings_model->getSingleUser();
        #notes if are you editing yourself
        $page_data['isSelf'] = $this->limited_auth_model->partyRoleMatches(
                $page_data['user_instance']->getField('party_role_id'));
        $page_data['editPassword'] = FALSE;
        $page_data['error'] = '';
        $this->load->view( 'includes/template', $page_data );
    }

    #users jqGrid function
    public function browseUsers() {
        $this->load->model('limited_auth_model');
        if (!$this->limited_auth_model->authLevel('40')) {
            $this->limited_auth_model->logAccessViolation(current_url());
            redirect('auth/login/redirect/' . preg_replace('#/|%2F#', '%26', uri_string()));
        }
        $this->load->model('settings_model');
        $page  = $this->input->get('page', TRUE);
        $req_param = array (
                "sort_by"            => $this->input->get('sidx', TRUE),
                "sort_direction"     => $this->input->get("sord", TRUE),
                "page"               => $page,
                "num_rows"           => $this->input->get("rows", TRUE),
                "search"             => $this->input->get("_search", TRUE),
                "search_field"       => $this->input->get("searchField", TRUE),
                "search_operator"    => $this->input->get("searchOper", TRUE),
                "search_str"         => $this->input->get("searchString", TRUE),
                "electronic_address" => $this->input->get('electronic_address'),
                "user_level"         => $this->input->get('user_level'),
                "user_role"          => $this->input->get('user_role'),
                "description"        => $this->input->get('description'),
                "name"               => $this->input->get('name'),
                "party_role_id"      => $this->input->get('party_role_id')
            );

            $page_data['page'] = $page;

        $limit = isset($_POST['rows']) ? $_POST['rows'] : 10; // get how many rows we want to have into the grid
        $page_data['records'] = count($this->settings_model->getUsersData($req_param, "all"));
        $page_data['total'] = ceil($page_data['records'] / $limit);
        $records = $this->settings_model->getUsersData($req_param, $page);
        $i=0;
        foreach($records as $row) {
            $page_data['rows'][$i]['id']=$row->party_role_id;
            $page_data['rows'][$i]['cell']=array(
                    $row->electronic_address,
                    $row->user_level,
                    $row->user_role,
                    $row->description,
                    $row->name,
                    $row->party_role_id
                );
            $i++;
        }
        echo json_encode($page_data);
    }
}

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */