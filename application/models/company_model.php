<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class company_model extends CI_Model{

	function addNewCompany()
	{
		 #check to see that we arived from the correct page
        $currentPage = $this->input->post('form_source');
        // $surety = new surety();
        // #$this->input->post can not be iterated on, so we use $_POST directly
        // foreach($_POST as $key => $value) {
        //     #do not store emtpy values or hidden fields
        //         $explodedKey = explode('-', $key);
        //             $power->setField(array_slice($explodedKey,0), $value);
        // }
         $organization = new Organization();
         foreach($_POST as $key => $value) {
            #do not store emtpy values or hidden fields
                $explodedKey = explode('-', $key);
                    $organization->setField(array_slice($explodedKey,0), $value);
         }
         //session_start();
         
         $party_role_id = $_SESSION["party_role_id"];
         // $organization['party_role_id'] = $party_role_id;
         // print_r($organization);
         // echo "kali";
		 $organization_persistence = new Organization_Persistence($organization);
         $error = $organization_persistence->saveToDatabase($this->db, $party_role_id);
         print_r($error);
         return $error;
	}

    function getAllOrganizations()
    {
        $organization = new Organization();
         $organization_persistence = new Organization_Persistence($organization);
        $result = $organization_persistence->getAllOrganizations($this->db);
        return $result;

    }

}