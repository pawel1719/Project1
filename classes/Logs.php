<?php
    require_once 'config.php';
    require_once PATH_TO_DB;
    require_once PATH_TO_FILE;
    
class Logs {
    private $_db;
    
    public static function log($user, $action, $type) {
        $db = DBB::getInstance();

        $db->insert('logs', array(
            'name_user' => $user,
            'action_log'    => $action,
            'type_log'      => $type,
            'link_on_app'   => $_SERVER['PHP_SELF'],
            'address_ip'    => $_SERVER['REMOTE_ADDR'],
            'port'          => $_SERVER['REMOTE_PORT'],
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'date_action'   => date('Y-m-d H:i:s')
        ));
    }

    public static function logsToFile($text_to_file, $path = '') {
        //default path for file
        $path = (strlen($path) === 0 ) ? ('files/logs/'. date('Y-m') .'_logi.txt') : $path;
        $ref = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'nono';
        
        $text_to_file = '['. date('Y-m-d H:i:s') .'] '. 
                        $_SERVER['REMOTE_ADDR'] .':'. $_SERVER['REMOTE_PORT'] 
                        .' -- '. $text_to_file
                        .' -- '. $_SERVER['REQUEST_METHOD'] .' '. $_SERVER['SERVER_PROTOCOL']
                        .' FILE '. $_SERVER['PHP_SELF'] 
                        .' PHP_SESSION_ID '. session_id()
                        .' HTTP_REFERER -> '. $ref
                        .' REQUEST_URI ->'. $_SERVER['REQUEST_URI']
                        .' USER-AGENT -> '. $_SERVER['HTTP_USER_AGENT'] 
                        .' PROTOCOL -> '. $_SERVER['SERVER_SOFTWARE'];
        
        // Filee::textFile($text_to_file, $path);
    }
    


    /*
    echo $_SERVER['PHP_SELF'] . '<br/>';
    echo $_SERVER['SERVER_ADDR'] . '<br/>';
    echo $_SERVER['SERVER_NAME'] . '<br/>';
    echo $_SERVER['SERVER_SOFTWARE'] . '<br/>';
    echo $_SERVER['SERVER_PROTOCOL'] . '<br/>';
    echo $_SERVER['HTTP_HOST'] . '<br/>';
    echo $_SERVER['HTTP_REFERER'] . '<br/>';
    echo $_SERVER['HTTP_USER_AGENT'] . '<br/>';
    echo $_SERVER['REMOTE_ADDR'] . '<br/>';
    echo $_SERVER['REMOTE_PORT'] . '<br/>';
    echo $_SERVER['SERVER_PORT'] . '<br/>';
    */
}

?>