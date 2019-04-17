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
        
?>
<HTML lang="pl=PL">
<HEAD>
    
    <?php   require_once PATH_TO_HEAD;  ?>
    <script src="JS/ajax.js"></script> 

</HEAD>
<BODY>

    <?php   require_once PATH_TO_MENU;  ?>

    <!-- TABLE WITH TICKETS -->
    <div id="detailsTicket">
    <?php
        $ticket_ob = new Ticket();
        $ticket = $ticket_ob->showDataTickets(2, array('user_id' => Input::get('id')))[0];
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

    <br /><br />

    <div id="comments">
        <TABLE border="1">
            <tr>    
                <th>User</th>
                <th>Content</th>
                <th>Date</th>
            </tr>
            <?php
                $comment = $ticket_ob->getCommentsForTicket($ticket->id);
                
                foreach($comment as $tab) {
                    echo '<tr>
                            <td>'. $tab->user_name .'</td>
                            <td>'. $tab->comment .'</td>
                            <td>'. $tab->date_add .'</td>
                         </tr>';
                }
            ?>
            <tr>
                <td><?php echo $user->data()->name .' '. $user->data()->surname; ?></td>
                <form method="POST">
                    <td><textarea name="comment" id="comment" cols="120" placeholder="Wpisz treść komentarza..." ></textarea></td>
                    <td>
                        <input type="hidden" name="user" id="user" value="<?php echo $user->data()->ID ?>" >
                        <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" >
                        <input type="hidden" name="ticket" id="ticket" value="<?php echo $ticket->id; ?>" >
                        <input type="button" onClick="addComment()" value="Dodaj">
                    </td>
                </form>
            </tr>
        </TABLE>
    </div>


</BODY>
</HTML>