<?php

class Validate {

    //Required field
    public function checkRequired($field) {
    
        if (!$field) {
        
            $msg = 'Required field';
        
        }
        
        return $msg;
    }
        
        //Name
    public function checkName($name) { 
        
        if(!preg_match('/^[[:alpha:][:blank:]\']+$/',$name)) {
        
            $msg = "Please enter a valid name";	
        
        }
        
        return $msg;
    }
        
        //Email
    public function checkEmail($email) {
        
        if(!preg_match('/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-\.]+\.[a-zA-Z0-9_\-]+$/',$email)) {
        
            $msg = "Please enter a valid email address";	
        
        }
        
        return $msg;
        
    }
        
    public function checkPassword($pass, $confPass) {
        
        if(strlen($pass) < 6) {
        
            $msg = 'Your password must be at least 6 characters long';
        
        }	
        
        if($pass != $confPass) {
        
            $msg = 'Your passwords need to match';
        
        }
        
        return $msg;
        
    }
        
    public function checkNumeric($field) {
        
        if (!is_numeric($field)) {
        
            $msg = 'Please make sure you have entered the number correctly';
        
        }
        
        return $msg;
        
    }
        
        
    public function checkDateField($month, $day, $year) {
        
        if (!is_numeric($month) || !is_numeric($day) || !is_numeric($year) || !checkdate($month, $day, $year)) {
        
            $msg = 'Please enter a valid date';
        
        }
        
        return $msg;
        
    }
        
    public function checkSelectField($option) {
        
        if($option == 'null') {
        
            $msg = 'Please make sure you select an option';
        
        return $msg;
        
        }
        
        return false;
        
    }
        
    public function checkErrorMessages($result) {
        
        foreach($result as $errorMsg) {
        
            if (strlen($errorMsg) > 0 && $errorMsg != 'ok') {
            
                return false;
            
            }
        }
        
        return true;
        
        }
    
    }
?>