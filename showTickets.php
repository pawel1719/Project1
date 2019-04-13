<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_TICKET;
    require_once PATH_TO_CLASSES_USER;
?>

<!DOCTYPE HTML lang="pl=PL">
<HEAD>
    
    <?php   require_once PATH_TO_HEAD;  ?>

<script>
    function showHint(str) {
        if (str.length == 0) { 
            document.getElementById("listElements").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // document.getElementById("listElements").innerHTML = this.responseText;
                    console.log('Success');
                }
            };
            xmlhttp.open("GET", "http://localhost/quiz2/JS/Request/AjaxRequest.php?id=3&row=" + str, true);
            xmlhttp.send();
        }
    }
</script>

</HEAD>
<BODY>
    <?php

        require_once PATH_TO_MENU;

        $user = new User();
        // redirect if user is not login
        if(!$user->isLoggedIn()) {
            header('Location: index.php');
        }


        //set number page
        if(Input::exists('GET')) {
            if(is_numeric(Input::get('page'))) {
                $val = Input::get('page');
                Input::set('page', $val);
            }else {
                Input::set('page', 1);
            }
            
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

        }else{
            header('location: showTickets.php?row=10&page=1');
        }

    ?>

    <table>
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
            </tr>
        <?php
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
        
        ?>
    </table>

    <!-- NUMBERS PAGES -->
    <br />
    <br />

    <center>
    <?php
        $link = 'showTickets.php?row='.Input::get('row').'&page=';
        echo $tickets->numberPages($link, Input::get('page'), Input::get('row'), 5);
    ?>

    <!-- LIMIT ROW ON THE PAGE -->
    Pokaż: 
    <select name="row" onchange="showHint(this.value)">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
    </select>

    </center>

</BODY>
</HTML>