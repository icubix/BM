<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_Instance extends Domain {

    function __construct() {
        $this->dict = array(
            'document_id' => 0,
            'document_name' => NULL,
            'document_type' => NULL,
            'document_size' => NULL,
            'uploaded_by' => NULL,
            'description' => NULL,
            'path' => NULL,
            'tags' => NULL
        );
    }

    #checks to see if local fields are empty as well as fields of children
    public function isEmpty() {
        return !(
                   ($this->dict['document_id'] && $this->dict['document_id'] != 0)
                || ($this->dict['document_name']
                        && $this->dict['document_name'] != '')
                || ($this->dict['document_type']
                        && $this->dict['document_type'] != '')
                || ($this->dict['document_size']
                        && $this->dict['document_size'] != '')
                || ($this->dict['uploaded_by']
                        && $this->dict['uploaded_by'] != '')
                || ($this->dict['description']
                        && $this->dict['description'] != '')
                || ($this->dict['path'] && $this->dict['path'] != '')
                || ($this->dict['tags'] && $this->dict['tags'] != '')
                );
    }

    protected function getLocalField($field) {
        if (array_key_exists($field, $this->dict)) {
            return $this->dict[$field];
        }
        return NULL;
    }

    protected function setLocalField($field, $value) {
        if (array_key_exists($field, $this->dict)) {
            $this->dict[$field] = $value;
            return TRUE;
        }
        return FALSE;
    }
}

/* End of file document_instance.php */
/* Location: ./application/libraries/document_instance.php */