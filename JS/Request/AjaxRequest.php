<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_DB;
    require_once PATH_TO_UP_UP_CLASSES_INPUT;
    require_once PATH_TO_UP_UP_CLASSES_TICKET;

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
                            'comment'   => Input::get('comment'),
                            'date_add'  => date('Y-m-d H:i:s') ,
                            'ip_address'=> $_SERVER['REMOTE_ADDR'],
                            'port'      => $_SERVER['REMOTE_PORT'],
                            'user_agent'=> $_SERVER['HTTP_USER_AGENT']
                        );
                        //date to DB
                        if($ticket->addComment($fields)) { 
                            echo 'Added comment!';
                        }else{
                            echo 'Error adding comment';
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
        }
    } else {
        //variable id not exist
        echo '<h2>Error 404</h2> <br/> <h4>Page not found!</h4>';
    }

?>