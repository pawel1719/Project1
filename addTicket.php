<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_FILE;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_LOGS;
    require_once PATH_TO_CLASSES_SANITIZE;
    require_once PATH_TO_CLASSES_SESSION;
    require_once PATH_TO_CLASSES_TICKET;
    require_once PATH_TO_CLASSES_TOKEN;
    require_once PATH_TO_CLASSES_USER;
    require_once PATH_TO_CLASSES_VALIDATE;

    //save information about visit to file
    Logs::logsToFile('Visited on page');
    
?>

<HTML lang="pl-PL">
<HEAD>

    <?php require_once PATH_TO_HEAD; ?>

</HEAD>
<BODY onLoad="showList('JS/Request/AjaxRequest.php?id=1', 'listQueue'); showList('JS/Request/AjaxRequest.php?id=2','listPriority')">

<?php require_once PATH_TO_MENU; ?>


    <form method="POST" class="login" enctype="multipart/form-data">

<?php
    $user = new User();
    //redirect for user is not login
    if(!$user->isLoggedIn()) {
        header('Location: index.php');
    }
    //message to added ticket
    if(Session::exist('ticket_message')) {
        echo Session::flash('ticket_message');
    }

    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            
            //validation form
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
                    'min' => 5,
                    'max' => 100
                ),
                'description' => array(
                    'required' => true,
                    'min' => 5
                )
            ));

            //validation file 
            $validate_file = new Validate();
            $validation_file = $validate_file->check($_FILES['attachment'], array(
                'name' => array(
                    'min' => 6,
                    'max' => 50
                ),
                'size' => array(
                    'max_value' => 10814415
                ),
                'type' => array(
                    'allowed_type_file' => array(
                        //allowed format mime type
                        'application/pdf', 
                        'image/png', 
                        'image/jpeg', 
                        'text/csv'
                    )
                )
            ));


            if($validation->passed() && $validation_file->passed()) {
                $subject = Input::get('subject');
                $queue = Input::get('ticketQueue');

                //adding ticket to database
                $ticket = new Ticket();
                $ticket->create(array(
                    'id_declarant'          => $user->data()->ID, //user which added ticket
                    'id_ticketStatus'       => 1, //value status for new ticket in system
                    'id_ticketPriority'     => Input::get('ticketPriority'),
                    'id_ticketQueue'        => $queue,
                    'subject'               => $subject,
                    'description'           => Input::get('description'),
                    'date_create_ticket'    => date('Y-m-d H:i:s')
                ));
                
                //ID from DB to the ticket being created
                $db = DBB::getInstance();
                $id_ticket = $db->query("SELECT `ID`, `subject`, `description`, `date_create_ticket` 
                            FROM ticket 
                            WHERE subject = '{$subject}' AND id_ticketQueue = {$queue} AND id_ticketStatus = 1
                            ORDER BY date_create_ticket DESC LIMIT 5");
                $id = $id_ticket->results()[0]->ID;
                
                Logs::log($user->data()->username, 'Succes added ticket #'. $id, 'Success');
                
                //adding attachment 
                if(!empty($_FILES['attachment']) && strlen($_FILES['attachment']['name']) > 3) {      
                    $path_to_attachment = 'files/attachment/tickets/'. $id;
                    if(Filee::createFolder($path_to_attachment)) {
                       
                        if(Filee::uploadFile($path_to_attachment, 'attachment')) {
                           
                            Filee::infoToDB(array(
                                'name'          => $_FILES['attachment']['name'],
                                'size'          => $_FILES['attachment']['size'],
                                'type'          => $_FILES['attachment']['type'],
                                'error_adding'  => $_FILES['attachment']['error'],
                                'path'          => $path_to_attachment .'/'. $_FILES['attachment']['name'],
                                'date_added'    => date('Y-m-d H-i-s'),
                                'id_ticket'     => $id,
                                'id_user'       => $user->data()->ID,
                                'user'          => ($user->data()->username .' '. $user->data()->name)
                            ));

                            Logs::log($user->data()->username, 'Succes added file to ticket #'. $id, 'Success');
                        }
                    }
                }

                //destroy the variables 
                Input::destroy('subject');
                Input::destroy('description');
                Input::destroy('ticketQueue');
                Input::destroy('ticketPriority');
                Input::destroy('attachment');

                //information about complete success
                Session::flash('ticket_message', '<div class="login__message login__message--success">
                                    Added a ticket!
                                </div>');
                header('Location: addTicket.php');

            } else {
                //error from validaion
                echo '<div class="login__message login__message--alert">';
                
                foreach($validation->errors() as $error) {
                    echo $error . '<br/>';
                }
                foreach($validation_file->errors() as $error) {
                    echo 'File ' . $error . '<br/>';
                }
                echo '</div>';
                Logs::log($user->data()->username, 'Error fvalidation for added ticket or file', 'Error');
            }
        }
    }   

?>
        <label for="ticketQueue" class="login__label"` onClick="showList('JS/Request/AjaxRequest.php?id=1', 'listQueue')">Koleja</label>
        <select name="ticketQueue" id="listQueue" class="login__input" >
            <!-- option from database --> 
        </select>

        <label for="ticketPriority" class="login__label"` onClick="showList('JS/Request/AjaxRequest.php?id=2','listPriority')">Status</label>
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