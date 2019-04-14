<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_SESSION;
    require_once PATH_TO_CLASSES_TICKET;
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
                <td>User</td>
                <td>Content</td>
                <td>Date</td>
            </tr>
            <tr>    
                <td>Adam Kowalski</td>
                <td>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Unde nisi harum praesentium velit natus optio illo dolorum eligendi ut quia, delectus maiores tenetur atque dicta fugiat doloribus aspernatur rerum officia!</td>
                <td>2019-04-12 14:55:21</td>
            </tr>
            <tr>    
                <td>Mariusz Krogul</td>
                <td>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae similique illo veritatis libero aliquam animi ullam quos maxime blanditiis laborum, corporis facilis quam qui dolorem in. Rem pariatur odit vel. Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint nesciunt soluta molestiae ad. Perspiciatis explicabo facilis consequatur nisi, non optio laborum et!</td>
                <td>2019-04-12 15:12:11</td>
            </tr>
            <tr>    
                <td>Adam Kowalski</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit sint perspiciatis quaerat delectus, rem officia quasi neque iure eos. Ullam architecto facere similique quasi sunt nisi officia modi, sint accusantium. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis fugit, aperiam esse nisi dicta commodi quod perferendis consequuntur dolorum placeat eius unde, et fuga molestias labore? Perspiciatis dolore animi enim.</td>
                <td>2019-04-12 15:35:29</td>
            </tr>
        </TABLE>
    </div>


</BODY>
</HTML>