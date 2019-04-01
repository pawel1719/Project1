<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_DB;
    require_once PATH_TO_UP_UP_CLASSES_INPUT;

    if(Input::exists('GET')) {
        if(is_numeric(Input::get('id'))) {
            $db = DB::getInstance();

            //values for request ajax
            switch(Input::get('id')) {
                case '1' :
                    echo $db->toJSON('ticketQueue');
                break;
                case 2 :
                    echo $db->toJSON('ticketPriority');
                break;
                default:
                    echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
                break;                    
            }
        } else {
            echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
        }
    } else {
        echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
    }

?>