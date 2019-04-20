<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_INPUT;
    require_once PATH_TO_CLASSES_LOGS;
    require_once PATH_TO_CLASSES_SESSION;
    require_once PATH_TO_CLASSES_TICKET;
    require_once PATH_TO_CLASSES_USER;

    //save information about visit to file
    Logs::logsToFile('Visited on page');
    
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
<BODY onLoad="showTable(<?php echo (int)Input::get('row') .','. (int)Input::get('page'); ?>)">

    <?php   require_once PATH_TO_MENU;  ?>

    <!-- TABLE WITH TICKETS -->
    <div id="tableTickets"></div>


</BODY>
</HTML>