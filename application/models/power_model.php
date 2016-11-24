<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class power_model extends CI_Model{

	function clearPowerData() {
        $this->load->model('defendant_model');
        $defendant = $this->defendant_model->getUserdata();
        print_r($defendant);
        $value = 'dasu';
        $defendant->setField('bailbond_instance', new Bailbond_Instance());
        //$this->setField('kali',$value);

        $_SESSION['defendantData'] = serialize($defendant);
    }



	function Save()
	{
		
        #check to see that we arived from the correct page
        $currentPage = $this->input->post('form_source');
        $power = new Power();
        #$this->input->post can not be iterated on, so we use $_POST directly
        foreach($_POST as $key => $value) {
            #do not store emtpy values or hidden fields
                $explodedKey = explode('-', $key);
                    $power->setField(array_slice($explodedKey,0), $value);
        }
		 $power_persistence =
                new Power_Persistence($power);
         $error = $power_persistence->saveToDatabase(
                $this->db, $power->getField('power_id'));
         print_r($error);
         return $error;
	}

    function getAllPowers()
    {
        $power = new Power();
        $power_persistence = new Power_Persistence($power);
        $result = $power_persistence->getPowers($this->db);
        return $result;

    }
}