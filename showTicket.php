<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_SESSION;
    require_once PATH_TO_CLASSES_TICKET;
    require_once PATH_TO_CLASSES_TOKEN;
    require_once PATH_TO_CLASSES_USER;

    $user = new User();
    // redirect if user is not login
    if(!$user->isLoggedIn()) {
         header('Location: index.php');
    }

    if(!is_numeric(Input::get('id'))) {
        header('Location: showTickets.php?row=10&page=1');
    }
        
    $ticket_ob = new Ticket();
    $ticket = $ticket_ob->showDataTickets(2, array('user_id' => Input::get('id')))[0];

?>
<HTML lang="pl=PL">
<HEAD>
    
    <?php   require_once PATH_TO_HEAD;  ?>
    <script src="JS/ajax.js"></script> 

</HEAD>
<BODY onLoad="showCommentsAPI(<?php echo (int)$ticket->id; ?>)">

    <?php   require_once PATH_TO_MENU;  ?>

    <!-- TABLE WITH TICKETS -->
    <div id="detailsTicket">
    <?php

        $query = 'SELECT  ID
                    ,name
                    ,path
                    ,date_added
                    ,id_ticket
                FROM updatedFiles
                WHERE id_ticket = '. $ticket->id;
        $file_from_db = $ticket_ob->showData($query);

        $file_name = isset($file_from_db[0]->name) ? $file_from_db[0]->name : '';
        $file_path = isset($file_from_db[0]->path) ? $file_from_db[0]->path : '';

        echo '<table border="1">
                <tr>   
                    <td>Numer zgłoszenia</td> 
                    <td colspan="3">#'.$ticket->id .'</td>
                </tr>
                <tr>
                    <td>Piorytet</td><td>'. $ticket->priority .'</td>
                    <td>Status</td><td>'. $ticket->status .'</td>
                </tr>
                <tr>
                    <td>Zgłoszenie od</td><td>'. $ticket->declarant .'</td>
                    <td>Data utworzenia</td><td>'. $ticket->date_create .'</td>
                </tr>
                <tr>
                    <td>Obsługujący</td><td>'. $ticket->operator .'</td>
                    <td>Data rozpoczęcia obsługi</td><td>'. $ticket->date_acceptance .'</td>
                </tr>
                <tr>
                    <td>Data planowanego zakończenia</td><td>'. $ticket->date_planned_end .'</td>
                    <td>Powiązane zgłoszenie</td><td>#'. $ticket->id_linked .'</td>
                </tr>
                    <td>Nazwa załącznika</td><td>'. $file_name .'</td>
                    <td>Załącznik</td><td><a href="'. $file_path .'">'. $file_name .'</a></td>
                </tr>
                <tr>
                    <td>Temat: </td>
                    <td colspan="3">'. $ticket->subject_ticket .'</td>
                </tr>
                <tr>
                    <td>Opis: </td>
                    <td colspan="3">'. $ticket->desc_ticket .'</td>
                </tr>
            </table>';
    ?>
    </div>
    <?php
    
    ?>
    <br /><br />

    <div id="comments">
    </div>


</BODY>
</HTML>