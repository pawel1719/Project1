<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_ACTION;
    require_once PATH_TO_UP_UP_CLASSES_INPUT;
    require_once PATH_TO_UP_UP_CLASSES_LOGS;
    require_once PATH_TO_UP_UP_CLASSES_TICKET;
    require_once PATH_TO_UP_UP_CLASSES_USER;

    $user = new User();
    // redirect if user is not login
    if(!$user->isLoggedIn()) {
        Logs::log('unknow', 'Unauthorized access to app', 'Alert security');
        header('Location: index.php');
    }
    
    if(Input::exists('GET')) {
        //number of row on the page
        switch(Input::get('row')) {
            case 5  : Input::set('row', 5);     break;
            case 10 : Input::set('row', 10);    break;
            case 15 : Input::set('row', 15);    break;
            case 20 : Input::set('row', 20);    break;
            case 25 : Input::set('row', 25);    break;
            case 50 : Input::set('row', 50);    break;
            default:  Input::set('row', 15);    break;
        }

        //data to showTickets
        $data           = (preg_match("/^[a-z_]{1,}$/", trim(Input::get('data'))) === 1) ? trim(Input::get('data')) : '';
        $number_page    = is_numeric(Input::get('page')) ? Input::get('page') : 1;
        $result_on_page = is_numeric(Input::get('row'))  ? Input::get('row') : 10;

        
        // //PERMISSION TO SEE TICKETS
        if($user->hasPermission('tickets', 'self')) {
            //ticket create from logged user
            $permission = ' AND t.id_declarant = '. $user->data()->ID;
            
        }else if($user->hasPermission('tickets', 'group')) {
            //ticket for group where user was added
            $permission = ' AND u.group = '. $user->data()->group;
        
        }else if($user->hasPermission('tickets', 'all')) {
            //all ticket
            $permission = ''; 
        
        }else {
            //return infromation about error
            Logs::logsToFile('#23150 Undefined permission');
            Logs::log($user->data()->ID, '#23150 Undefined permission', 'Error security');
            throw new Exception('#23150 Error permision');
        }


        switch($data) {
            case 'tickets' :
                //return all tickets
                $syntax = 'WHERE t.id <= ((SELECT MAX(ID) max_row FROM ticket) - '. (($number_page-1) * $result_on_page) .')'. $permission .'
                           ORDER BY id DESC
                           LIMIT '. $result_on_page;
            break;
            case 'new_tickets' :
                //return all new tickets
                $syntax = 'WHERE t.ID <= ((SELECT MAX(ID) max_row FROM ticket) - '. (($number_page-1) * $result_on_page) .') AND t.id_operator_ticket IS NULL'. $permission .'
                           ORDER BY t.ID DESC
                           LIMIT '. $result_on_page;
            break;
            default: throw new Exception('Unknow data ');
        }   
        
        //structure table to html
        echo '<table class="table table-sm table-hover table-dark">
            <thead>
                <tr>
                    <th scope="col">Nr.</th>
                    <th scope="col">Temat</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Piorytet</th>
                    <th scope="col">Kolejka</th>
                    <th scope="col">Właściciel</th>
                    <th scope="col">Data dodania</th>
                    <th scope="col">Obsługujący</th>
                    <th scope="col">Wiek zgłoszenia</th>
                    <th scope="col">Czas realizacji</th>
                </tr>
            </thead><tbody>';
            
        $tickets = new Ticket();
        $data_tickets = $tickets->showDataTickets($syntax);
        
        for($i=0; $i<count($data_tickets); $i++) {
            echo '  <tr>
                        <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'">'. $data_tickets[$i]->id .'</a></td>
                        <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'">'. $data_tickets[$i]->subject_ticket .'</a></td>
                        <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'" title="'. $data_tickets[$i]->desc_ticket .'">'. Action::partText($data_tickets[$i]->desc_ticket, 100) .'</a></td>            
                        <td>'. $data_tickets[$i]->priority .'</td>
                        <td>'. $data_tickets[$i]->queue .'</td>
                        <td>'. $data_tickets[$i]->declarant .'</td>
                        <td>'. $data_tickets[$i]->date_create .'</td>
                        <td>'. $data_tickets[$i]->operator .'</td>
                        <td>'. Action::timeToNow($data_tickets[$i]->date_create) .'</td>
                        <td>'. Action::timeToNow($data_tickets[$i]->date_acceptance) .'</td>
                    </tr>';
        }

        echo '</tbody></table><div>';
        
        //pages to table
        $link = 'showTickets.php?data='. $data .'&row='. $result_on_page .'&page=';
        echo $tickets->numberPages($link, $number_page, $result_on_page, $syntax, 5);
        
        
        //LIMIT ROW ON THE PAGE
        echo '<label for="row">Pokaż:</label>
                <select name="row" class="form-control form-control-sm size_for_option_list btn-secondary btn-link" id="ticket_row" onchange="showTable(\'tickets\', this.value)" onload="selectValue(\'ticket_row\', this.value)">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select> </div>';
    }else{
        Logs::log($user->data()->username, 'Unauthorized access to page', 'Alert security');
        header("HTTP/1.0 404 Not Found");
        echo "<h2>Error 404 </h2> <br/> Page not found";
        die();
    }

    ?>