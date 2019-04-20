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
        $show_com = false;
        $visibility = '';

        if(!empty($tab) && isset($tab->visibility)) {
            switch($tab->visibility ) {
                case 1 : 
                    //comment for all user
                    $show_com = true;
                    $visibility = '<small>(Ogólny)</small>';
                break;
                case 2 :
                    //comment for group
                    $show_com = true;
                    $visibility = '<small>(Działowy)</small>';
                break;
                case 3 : 
                    //comment only for owner
                    if($tab->id_user === $user->data()->ID) {
                        $show_com = true;
                        $visibility = '<small>(Prywatny)</small>';
                    }
                break;            
            }

            //add comment to show
            if($show_com) {
                echo '<tr>
                        <td>'. $tab->user_name .'<br/>'. $visibility .'</td>
                        <td>'. $tab->comment .'</td>
                        <td>'. $tab->date_add .'</td>
                    </tr>';
            }
        }
    }//end foreach
    
    echo    '<tr>
                <td>'. $user->data()->name .' '. $user->data()->surname .'</td>
                <form method="POST">
                    <td><textarea name="comment" id="comment" cols="120" placeholder="Wpisz treść komentarza..." ></textarea></td>
                    <td>
                        <select name="visibility" id="visibility">
                            <option value="1" >Ogólny</option>
                            <option value="2" >Działowy</option>
                            <option value="3" >Prywatny</option>
                        </select>
                        <input type="hidden" name="user" id="user" value="'. $user->data()->ID .'" >
                        <input type="hidden" name="token" id="token" value="'. Token::generate() .'" >
                        <input type="hidden" name="ticket" id="ticket" value="'. Input::get('id') .'" >
                        <input type="button" onClick="addCommentAPI()" value="Dodaj">
                    </td>
                </form>
            </tr>
        </TABLE>';