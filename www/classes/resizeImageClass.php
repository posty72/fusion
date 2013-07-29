<?php

class ResizeImage {
    
    private $imageName;
    private $dimension;
    private $desFolder;
    private $prefix;
    public $msg;
    
    public function __construct($imageName, $dimension, $destFolder, $prefix='') {
        
        $this -> imageName = $imageName;
        $this -> dimension = $dimension;
        $this -> destFolder = $destFolder;
        $this -> prefix = $prefix;
        
    }
    
    public function resize(){
        
        $this -> msg = '';
        
        if(!file_exists($this -> imageName)) {
            $this -> msg .= 'Sorry, source file not found';
            return false;
        }
        
        $imageInfo = getimagesize($this -> imageName);
        
        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];
        
        $newWidth = $this -> dimension;
        $newHeight = ($origHeight * $newWidth) / $origWidth;
        
        $fileName = basename($this -> imageName);
        $dFolder = $this -> destFolder ? $this -> destFolder.'/' : '';
        $dFile = $this -> prefix ? $this -> prefix.'_'.$fileName : $fileName;
        $fullPath = $dFolder.''.$dFile;
        
        if($imageInfo['mime'] == 'image/jpeg') {
            
            if($this -> resizeJpeg($newWidth, $newHeight, $origWidth, $origHeight, $fullPath)) {
                return $fullPath;
            } else {
                return false;
            }
            
        }
        
        if($imageInfo['mime'] == 'image/gif') {
            
            if($this -> resizeGif($newWidth, $newHeight, $origWidth, $origHeight, $fullPath)) {
                return $fullPath;
            } else {
                return false;
            }
            
        }
        
        if($imageInfo['mime'] == 'image/png') {
            
            if($this -> resizePng($newWidth, $newHeight, $origWidth, $origHeight, $fullPath)) {
                return $fullPath;
            } else {
                return false;
            }
            
        }
        
    }
	
    private function resizeJpeg($newW, $newH, $origW, $origH, $fullPath){
        
        $im = ImageCreateTrueColor($newW, $newH);
        $baseImage = ImageCreateFromJpeg($this -> imageName);
        if (imagecopyresampled($im, $baseImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH)) {
            
                imageJpeg($im, $fullPath);
                if (file_exists($fullPath)) {
                        $this -> msg .= 'Thumb file created<br />';
                        imagedestroy($im);
                        return true;
                }
                else {
                        $this -> msg .= 'Failure in creating thumb file<br />';
                }
        }	
        else {
            
                $this -> msg .= 'Unable to resize image <br />';
                return false;
        }
    }
	
    private function resizeGif($newW, $newH, $origW, $origH, $fullPath){
        
        $im = ImageCreateTrueColor($newW, $newH);
        $baseImage = ImageCreateFromGif($this -> imageName);
        if (imagecopyresampled($im, $baseImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH)) {
            
                imageGif($im, $fullPath);
                if (file_exists($fullPath)) {
                        $this -> msg .= 'Thumb file created<br />';
                        imagedestroy($im);
                        return true;
                }
                else {
                        $this -> msg .= 'Failure in creating thumb file<br />';
                }
        }	
        else {
            
                $this -> msg .= 'Unable to resize image <br />';
                return false;
        }
    }
	
    private function resizePng($newW, $newH, $origW, $origH, $fullPath){
        
        $im = ImageCreateTrueColor($newW, $newH);
        $baseImage = ImageCreateFromPng($this -> imageName);
        if (imagecopyresampled($im, $baseImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH)) {
            
                imagePng($im, $fullPath);
                if (file_exists($fullPath)) {
                        $this -> msg .= 'Thumb file created<br />';
                        imagedestroy($im);
                        return true;
                }
                else {
                        $this -> msg .= 'Failure in creating thumb file<br />';
                }
        }	
        else {
            
                $this -> msg .= 'Unable to resize image <br />';
                return false;
        }
    }
    
}











?>