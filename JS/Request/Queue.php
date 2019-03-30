<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_DB;

    $db = DB::getInstance();
    $result = $db->get('ticketQueue', array('ID', '>', 0));
    $array = $db->results();
    
    echo $JSON = json_encode($array);
?>