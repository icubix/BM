<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_Presentation extends Domain_Presentation {
    public function echoFields($location = 'document_instance') {
        $offset = '2';
        $readOnlyFieldsBackup = $this->readOnlyFields;
        $this->readOnlyFields = TRUE;
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Document ID</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'document_id', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Document Name</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'document_name', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Document Type</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'document_type', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Document Size</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'document_size', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
        $this->readOnlyFields = $readOnlyFieldsBackup;
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Description</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'description', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
        echo '              <div class="row" align="right">' . "\n";
        echo '                <div class="col-sm-2 col-md-' . $offset . '">Tags</div>' . "\n";
        echo '                ';
        $this->echoOneField($location, 'tags', 'col-sm-2 col-md-9');
        echo '              </div>' . "\n";
    }
}

/* End of file document_presentation.php */
/* Location: ./application/libraries/document_presentation.php */