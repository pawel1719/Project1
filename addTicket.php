<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_FILE;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_SANITIZE;
    require_once PATH_TO_CLASSES_TICKET;
    require_once PATH_TO_CLASSES_TOKEN;
    require_once PATH_TO_CLASSES_USER;
    require_once PATH_TO_CLASSES_VALIDATE;
?>

<!DOCTYPE html>
<HTML lang="pl-PL">
<HEAD>

    <?php require_once PATH_TO_HEAD; ?>

</HEAD>
<BODY onLoad="showList('JS/Request/AjaxRequest.php?id=1', 'listQueue'); showList('JS/Request/AjaxRequest.php?id=2','listPriority')">
    
    <form method="POST" class="login" enctype="multipart/form-data">

<?php
    $user = new User();
    //redirect for user is not login
    // if(!$user->isLoggedIn()) {
    //     header('Location: index.php');
    // }

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'ticketQueue' => array(
                    'required' => true,
                ),
                'ticketPriority' => array(
                    'required' => true
                ),
                'subject' => array(
                    'required' => true,
                    'min' => 5
                ),
                'description' => array(
                    'required' => true,
                    'min' => 5
                )
            ));


            if($validation->passed()) {
                $subject = Input::get('subject');
                $queue = Input::get('ticketQueue');

                //adding ticket to database
                $ticket = new Ticket();
                $ticket->create(array(
                    'id_declarant'          => 12, //user which added ticket
                    'id_ticketStatus'       => 1, //value status for new ticket in system
                    'id_ticketPriority'     => Input::get('ticketPriority'),
                    'id_ticketQueue'        => $queue,
                    'subject'               => $subject,
                    'description'           => Input::get('description'),
                    'date_create_ticket'    => date('Y-m-d H:i:s')
                ));

                //ID from DB to the ticket being created
                $db = DB::getInstance();
                $id_ticket = $db->query("SELECT `ID`, `subject`, `description`, `date_create_ticket` 
                    FROM ticket 
                    WHERE subject = '{$subject}' AND id_ticketQueue = {$queue} AND id_ticketStatus = 1
                    ORDER BY date_create_ticket DESC LIMIT 5");
                $id = $id_ticket->results()[0]->ID;

                //adding attachment 
                $path_to_attachment = 'files/attachment/'. date('Y-m-d');
                if(!empty($_FILES['attachment'])) {            
                    
                    if(File::createFolder($path_to_attachment)) {
                       
                        if(File::uploadFile($path_to_attachment, 'attachment')) {
                           
                            File::infoToDB(array(
                                'name'          => $_FILES['attachment']['name'],
                                'size'          => $_FILES['attachment']['size'],
                                'type'          => $_FILES['attachment']['type'],
                                'error_adding'  => $_FILES['attachment']['error'],
                                'path'          => $path_to_attachment .'/'. $_FILES['attachment']['name'],
                                'date_added'    => date('Y-m-d H-i-s'),
                                'id_ticket'     => $id,
                                'user'          => NULL
                            ));
                        }
                    }
                }

                //destroy the variables 
                Input::destroy('subject');
                Input::destroy('description');
                Input::destroy('ticketQueue');
                Input::destroy('ticketPriority');

                //information about complete success
                echo '<div class="login__message login__message--success">
                        Added a ticket!
                     </div>';

            } else {
                //error from validaion
                echo '<div class="login__message login__message--alert">';
                
                foreach($validation->errors() as $error) {
                    echo $error . '<br/>';
                }
                echo '</div>';
            }
        }
    }

?>
        <label for="ticketQueue" class="login__label"` onClick="showList('http://localhost/quiz2/JS/Request/AjaxRequest.php?id=1', 'listQueue')">Koleja</label>
        <select name="ticketQueue" id="listQueue" class="login__input" >
            <!-- option from database --> 
        </select>

        <label for="ticketPriority" class="login__label"` onClick="showList('http://localhost/quiz2/JS/Request/AjaxRequest.php?id=2','listPriority')">Status</label>
        <select name="ticketPriority" id="listPriority" class="login__input">
            <!-- option from database --> 
        </select>

        <label for="subject" class="login__label" >Temat:</label>
        <input type="text" name="subject" placeholder="Temat..." value="<?php echo Sanitize::escape(Input::get('subject')); ?>" class="login__input" />

        <label for="description" class="login__label" >Opis:</label>
        <textarea name="description" rows="8" cols="40" placeholder="Opis zgłoszenia..." class="login__input"><?php echo Sanitize::escape(Input::get('description')); ?></textarea>

        <input type="file" name="attachment" accept="text/csv,application/msword,application/xml-dtd,image/gif,text/html,image/jpeg,audio/mpeg,audio/mp4, video/mp4,video/mpeg,application/vnd.ms-project,application/pdf,image/png,application/vnd.ms-powerpoint,text/plain,application/vnd.ms-works,application/vnd.ms-excel,text/xml, application/xml,aplication/zip" class="login__input" />

        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />

        <input type="submit" value="Dodaj" class="login__button" />

    </form>

    <script src="JS/ajax.js"></script> 

</BODY>
</HTML>