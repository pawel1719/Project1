<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_INPUT;
    require_once PATH_TO_UP_UP_CLASSES_TICKET;
    
    if(Input::exists('GET')) {
        //number of row on the page
        switch(Input::get('row')) {
            case 5 :
                Input::set('row', 5);
            break;
            case 10 :
                Input::set('row', 10);
            break;
            case 15 :
                Input::set('row', 15);
            break;
            case 20 :
                Input::set('row', 20);
            break;
            case 25 :
                Input::set('row', 25);
            break;
            case 50 :
                Input::set('row', 50);
            break;
            default: 
                Input::set('row', 15);
            break;
        }
        
        //set number page
        if(is_numeric(Input::get('page'))) {
            Input::set('page', Input::get('page'));
        }else{
            Input::set('page', 10);
        }
        
        
        echo '<table>
        <tr>
        <td>Nr.</td>
        <td>Temat</td>
        <td>Opis</td>
        <td>Piorytet</td>
        <td>Kolejka</td>
        <td>Właściciel</td>
                <td>Data dodania</td>
                <td>Obsługujący</td>
                <td>Wiek zgłoszenia</td>
                <td>Czas realizacji</td>
            </tr>';
            
            $tickets = new Ticket();
            $data_tickets = $tickets->showDataTickets(Input::get('page')-1, Input::get('row'));
            
            for($i=0; $i<count($data_tickets); $i++) {
                echo '<tr>
                <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'">'. $data_tickets[$i]->id .'</a></td>
                <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'">'. $data_tickets[$i]->subject_ticket .'</a></td>
                <td><a href="showTicket.php?id='. $data_tickets[$i]->id .'">'. $data_tickets[$i]->desc_ticket .'</a></td>            
                <td>'. $data_tickets[$i]->priority .'</td>
                <td>'. $data_tickets[$i]->queue .'</td>
                <td>'. $data_tickets[$i]->declarant .'</td>
                <td>'. $data_tickets[$i]->date_create .'</td>
                </tr>';
            }
            
            echo '</table>';
            
            //pages to table
            $link = 'showTickets.php?row='.Input::get('row').'&page=';
            echo $tickets->numberPages($link, (int)Input::get('page'), (int)Input::get('row'), 5);
            
            
            //LIMIT ROW ON THE PAGE
            echo 'Pokaż: 
            <select name="row" id="ticket_row" onchange="showTable(this.value)" onload="selectValue(\'ticket_row\', this.value)">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>';

        }else{
            header("HTTP/1.0 404 Not Found");
            echo "<h2>Error 404 </h2> <br/> Page not found";
            die();
        }

    ?>