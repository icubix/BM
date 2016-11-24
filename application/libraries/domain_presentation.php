<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domain_Presentation {
    protected $counterpart;
    protected $readOnlyFields;

    function __construct($counterpart) {
        $this->counterpart = $counterpart;
        $this->readOnlyFields = FALSE;
    }

    public function setReadOnlyFields($new_bool) {
        $this->readOnlyFields = (bool)$new_bool;
    }

    protected function echoSelect($location, $field, $elements, $default, $class = NULL) {
        echo '<select name="' . $location . '-' . $field . '" ';
        if ($class) {
            echo 'class="' . $class . '"';
        }
        echo ">\n";
        $fieldValue = NULL;
        if ($this->counterpart) {
            $fieldValue = $this->counterpart->getField($field);
        }
        if ($fieldValue == NULL) {
            $fieldValue = $default;
        }
        if ($this->readOnlyFields) {
            foreach ($elements as $row) {
                if (count($row) > 1 && $fieldValue == $row['key']) {
                    echo '              <option value="' . $row['key'] . '"';
                    echo ' selected';
                    echo '>' . $row['name'] . "</option>\n";
                }
            }
        } else {
            foreach ($elements as $row) {
                if (count($row) > 1) {
                    echo '              <option value="' . $row['key'] . '"';
                    if ($fieldValue == $row['key']) {
                        echo ' selected';
                    }
                    echo '>' . $row['name'] . "</option>\n";
                }
            }
        }
        echo "            </select>\n";
    }

    protected function echoOneField($location, $field, $class = NULL, $type = 'text') {
        echo '<input ';
        if ($class) {
            echo 'class="' . $class . '" ';
        }
        echo 'name="' . $location . '-' . $field . '" ';
        echo 'type="' . $type . '" '; #TODO: handle other types
        if ($this->counterpart) {
            $fieldValue = $this->counterpart->getField($field);
            if ($fieldValue != NULL) {
                echo 'value="' . $fieldValue . '" ';
            }
        }
        if ($this->readOnlyFields) {
            echo 'readonly';
        }
        echo "/>\n";
    }

    public function echoFields($page = 'readonly') {
        echo '<input name="' . $page . '_defendant_name" class="col-sm-2 col-md-12" ';
        if ($this->counterpart) {
            $person = $this->counterpart->getField('personal_attribute');
            if ($person && is_a($person, 'Domain')) {
                $person = $person->getField('defendant');
                if ($person && is_a($person, 'Domain')) {
                    $defendant_name  = $this->counterpart->getField('party_role_id') . ':  ';
                    $defendant_name .= $person->getField('first_name') . ' ';
                    $defendant_name .= $person->getField('middle_name') . ' ';
                    $defendant_name .= $person->getField('last_name') . ' ';
                    $defendant_name .= $person->getField('suffix_name');
                    echo 'value="' . $defendant_name . '" ';
                }
            }
        }
        echo "readonly/>\n";
    }
}

/* End of file domain_presentation.php */
/* Location: ./application/libraries/domain_presentation.php */