<?php

class Upload {
    
    private $fileName;
    private $fileTypes = array();
    private $folderPath;
    public $msg;
    
    public function __construct($fileName, $fileTypes, $folderPath) {
        
        $this -> fileName = $fileName;
        $this -> fileTypes = $fileTypes;
        $this -> folderPath = $folderPath;
        
    }
    
    public function isUploaded() {
        
        $this -> msg = '';
        
        if(!$_FILES[$this -> fileName]['name']) {
            $this -> msg .= 'File name not available';
            return false;
        }
        
        if($_FILES[$this -> fileName]['error']) {
            switch($_FILES[$this -> fileName]['error']) {
                case 1: $this -> msg .= 'File exceeds PHP\'s maximum upload size<br />';
                    return false;
                case 2: $this -> msg .= 'File exceeds maximum upload file set in the form<br />';
                    return false;
                case 3: $this -> msg .= 'File partially uploaded<br />';
                    return false;
                case 4: $this -> msg .= 'No file uploaded<br />';
                    return false;
            }
            
        }
        
        $type = $_FILES[$this -> fileName]['type'];
        
        if(!in_array($type, $this -> fileTypes)) {
            $this -> msg .= 'Wrong file type.<br />';
            return false;
        }
        
        if(!is_uploaded_file($_FILES[$this -> fileName]['tmp_name'])) {
            $this -> msg .= 'File did not reach the temporary file location on the server<br />';
            return false;
        }
        
        $fileName = $_FILES[$this -> fileName]['name'];
        $filePath = $this -> folderPath ? $this -> folderPath.'/'.$fileName : $fileName;
        
        if(file_exists($filePath)) {
            $newName = uniqid('V').$fileName;
            $this -> msg .= 'File '.$fileName.' already exists, renamed to '.$newName.'<br />';
            $filePath = $this -> folderPath ? $this -> folderPath.'/'.$newName : $newName;
        }
        
        if(!move_uploaded_file($_FILES[$this -> fileName]['tmp_name'], $filePath)) {
            $this -> msg .= 'Error moving the file to specified location<br />';
            return false;
        }
        if(!file_exists($filePath)) {
            $this -> msg .= 'File didn\'t reach destination folder<br />';
            return false;
        }
        
        $this -> msg .= 'File '.$_FILES[$this -> fileName]['name'].' uploaded successfully<br />';
        return $filePath;
        
    }
    
    
    
}





?>