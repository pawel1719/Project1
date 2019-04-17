<?php
require_once 'config.php';
require_once PATH_TO_FILE;
require_once PATH_TO_DB;

class Filee {
    public static function createFolder($path) {
        $folders = explode('/', $path);
        $currently_path = '';

        //created all folder from path which not exist
        for($i=0; $i<count($folders); $i++) {
            $currently_path .= $folders[$i] . '/';

            //if not exist directory, creat
            if(!file_exists($currently_path)) {
                if(!mkdir($currently_path, 0777)) {
                    echo "$currently_path folder cant be created!";
                }
            }
        }//end loop

        //true when all be added
        if(file_exists($path)) {
            return true;
        }

        return false;
    }//end function

    public static function uploadFile($path, $file_name, $upload_all_flies = true) {    
        $name =  $path . '/'.  $_FILES[$file_name]['name'];
        
        //check that file was uploaded on server
        if(is_uploaded_file($_FILES[$file_name]['tmp_name'])) {
            $counter = 1;
            $new = '';
            $end = false;
            $info = pathinfo($name);
            $extension =  '.' . $info['extension'];

            do {
                if(file_exists($name) && $upload_all_flies === true) {
                    $name = str_replace($new, $extension, $name);
                    $new = '('. $counter .')' . $extension;
                    $name = str_replace($extension, $new, $name);
                    $counter++;
                } else {
                    $end = true;
                }
            }while(!$end);
            //check that file exist

            if(!file_exists($name)) {
                
                //uploading
                if(move_uploaded_file($_FILES[$file_name]['tmp_name'], $name)) {
                    return true;    //added file
                } else {
                    return false;   //error adding file
                }
                
            } else {
                echo 'File was added!';
            }
        } else {
            echo '#808325 Error: not found file';
        }
    }//end function

    public static function infoToDB($fields = array()) {
        $db = DBB::getInstance();
        if(!$db->insert('updatedFiles', $fields)) {
            throw new Exception('#404876 There was a problem with save information with database!');
        }
    }//end function

}//end class

?>