<?php
require_once PATH_TO_FILE;
require_once PATH_TO_DB;

class File {
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

    public static function uploadFile($path, $file_name, $type = array('application/pdf', 'image/png', 'image/jpeg', 'text/csv')) {
        $correct_type = false;

        //check that type file is correct
        for($i=0; $i<count($type); $i++) {
            if($type[$i] === $_FILES[$file_name]['type']) {
                $correct_type = true;
            }
        }

        if($correct_type) {
            $validate = new Validate();
            $validate->check($_FILES[$file_name], array(
                'name' => array(
                    'min' => 5,
                    'max' => 50
                )
            ));
        
            if($validate->passed()) {
                $name =  $path . '/'.  $_FILES[$file_name]['name'];
                
                //check that file was uploaded on server
                if(is_uploaded_file($_FILES[$file_name]['tmp_name'])) {
                    
                    //check that file exist
                    if(!file_exists($name)) {
                        
                        if(move_uploaded_file($_FILES[$file_name]['tmp_name'], $name)) {
                            return true;    //added file
                        } else {
                            return false;   //error adding file
                        }

                    } else {
                        echo 'File was added!';
                    }
                } else {
                    echo 'Error: not found file';
                }
            } else {
                //errors validation
                foreach($validate->errors() as $error) {
                    echo $error . '<br/>';
                }
            }
        } else {
            echo 'Invalid type of file';
        }
    }//end function

    public static function infoToDB($fields = array()) {
        $db = DB::getInstance();
        if(!$db->insert('updatedFiles', $fields)) {
            throw new Exception('#404876 There was a problem with save information with database!');
        }
    }//end function

}//end class

?>