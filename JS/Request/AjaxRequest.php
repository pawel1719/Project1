<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_DB;
    require_once PATH_TO_UP_UP_CLASSES_INPUT;
    require_once PATH_TO_UP_UP_CLASSES_LOGS;
    require_once PATH_TO_UP_UP_CLASSES_TICKET;
    require_once PATH_TO_UP_UP_CLASSES_USER;

    //save information about visit to file
    $path = '../../files/logs/'. date('Y-m') .'_logi.txt';
	Logs::logsToFile('Visited on page', $path);

    $user = new User();
    // redirect if user is not login
    if(!$user->isLoggedIn()) {
        Logs::log('unknow', 'Unauthorized access to app', 'Alert security');
        header('Location: index.php');
    }
    
    if(Input::exists('GET')) {
        if(is_numeric(Input::get('id'))) {
            $db = DBB::getInstance();
            $ticket = new Ticket();

            //values for request ajax
            switch(Input::get('id')) {
                case '1' :
                    echo $db->toJSON('ticketQueue');
                break;
                case 2 :
                    echo $db->toJSON('ticketPriority');
                break;
                case '3' :
                    Input::set('row', Input::get('row'));
                    header('Location: ../../showTickets.php?row='. Input::get('row'));
                break;
                case '4' :
                    if(Input::get('token')){
                        $fields = array(
                            //names columns on table in database
                            'id_user'   => Input::get('user'),
                            'id_ticket' => Input::get('ticket'),
                            'visibility'=> Input::get('visibility'),
                            'comment'   => Input::get('comment'),
                            'date_add'  => date('Y-m-d H:i:s') ,
                            'ip_address'=> $_SERVER['REMOTE_ADDR'],
                            'port'      => $_SERVER['REMOTE_PORT'],
                            'user_agent'=> $_SERVER['HTTP_USER_AGENT']
                        );
                        //date to DB
                        if($ticket->addComment($fields)) { 
                            echo 'Added comment!';
                            Logs::log( 'ID ' .Input::get('user'), 'Added comment: '.Input::get('comment') .' for ticket #'. Input::get('ticket'), 'Success');
                        }else{
                            echo 'Error adding comment';
                            Logs::log('ID '. Input::get('user'), 'Error added comment: '.Input::get('comment') .' for ticket #'. Input::get('ticket'), 'Error');
                        }
                    }
                break;
                default:
                    echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
                break;                    
            }
        } else {
            //variable id in not numeric
            echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
            Logs::log(NULL, 'Unauthorized access', 'Error' );
        }
    } else {
        //variable id not exist
        echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
        Logs::log(NULL, 'Unauthorized access', 'Error' );
    }

?>