<?php
    require_once 'config.php';
    require_once PATH_TO_DB;
    
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