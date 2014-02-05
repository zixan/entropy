<?php                                 

require_once 'SystemComponent.php';

class Validator extends SystemComponent {

        var $errors;

        function validateGeneral($theinput,$description = ''){
                if (trim($theinput) != "") {
                        return true;
                }else{
                        $this->errors[] = $description;
                        return false;
                }
        }

        function validateTextOnly($theinput,$description = ''){
                $result = ereg ("^[A-Za-z0-9\ ]+$", $theinput );
                if ($result){
                        return true;
                }else{
                        $this->errors[] = $description;
                        return false;
                }
        }

        function validateTextOnlyNoSpaces($theinput,$description = ''){
                $result = ereg ("^[A-Za-z0-9]+$", $theinput );
                if ($result){
                        return true;
                }else{
                        $this->errors[] = $description;
                        return false;
                }
        }

        function validateEmail($themail,$description = ''){
                $result = ereg ("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $themail );
                if ($result){
                        return true;
                }else{
                        $this->errors[] = $description;
                        return false;
                }

        }

        function validateNumber($theinput,$description = ''){
                if (is_numeric($theinput)) {
                        return true;
                }else{
                        $this->errors[] = $description;
                        return false;
                }
        }

        function validateDate($thedate,$description = ''){

                if (strtotime($thedate) === -1 || $thedate == '') {
                        $this->errors[] = $description;
                        return false;
                }else{
                        return true;
                }
        }

        function foundErrors() {
                if (count($this->errors) > 0){
                        return true;
                }else{
                        return false;
                }
        }

        function listErrors($delim = ' '){
                return implode($delim,$this->errors);
        }

        function addError($description){
                $this->errors[] = $description;
        }

}
?>