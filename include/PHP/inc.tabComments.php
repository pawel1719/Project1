<?php
    require_once '../../classes/config.php';
    require_once PATH_TO_UP_UP_CLASSES_ACTION;
    require_once PATH_TO_UP_UP_CLASSES_INPUT;
    require_once PATH_TO_UP_UP_CLASSES_TICKET;
    require_once PATH_TO_UP_UP_CLASSES_TOKEN;
    require_once PATH_TO_UP_UP_CLASSES_USER;

    $user = new User();
    // redirect if user is not login
    if(!$user->isLoggedIn()) {
         header('Location: index.php');
    }

    echo '<TABLE border="1">
            <tr>    
                <th>User</th>
                <th>Content</th>
                <th>Date</th>
            </tr>';
    
    $ticket_ob = new Ticket();
    $comment = $ticket_ob->getCommentsForTicket(Input::get('id'));
                
    foreach($comment as $tab) {
        echo '<tr>
                <td>'. $tab->user_name .'</td>
                <td>'. $tab->comment .'</td>
                <td>'. $tab->date_add .'</td>
             </tr>';
    }
    
    echo    '<tr>
                <td>'. $user->data()->name .' '. $user->data()->surname .'</td>
                <form method="POST">
                    <td><textarea name="comment" id="comment" cols="120" placeholder="Wpisz treść komentarza..." ></textarea></td>
                    <td>
                        <input type="hidden" name="user" id="user" value="'. $user->data()->ID .'" >
                        <input type="hidden" name="token" id="token" value="'. Token::generate() .'" >
                        <input type="hidden" name="ticket" id="ticket" value="'. Input::get('id') .'" >
                        <input type="button" onClick="addCommentAPI()" value="Dodaj">
                    </td>
                </form>
            </tr>
        </TABLE>';