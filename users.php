<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_LOGS;
    require_once PATH_TO_CLASSES_USER;

    
    //save information about visit to file
    Logs::logsToFile('Visited on page');
    
    $user = new User();
    // redirect if user is not login
    if(!$user->isLoggedIn()) {
        Logs::log('unknow', 'Unauthorized access to app', 'Alert security');
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

    <?php

        $db = DBB::getInstance();
        $users_list = $db->get('users', array('ID', '>', '0'))->results();

        echo '<table class="table table-bordered table-hover table-dark">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Login</th>
                        <th>Nazwisko i Imię</th>
                        <th>E-mail</th>
                        <th>Data ustawienia hasła</th>
                        <th>Ostatnie zaogowanie</th>
                        <th>Licznik logowań</th>
                        <th>Nieudane ostatnie logowanie</th>
                        <th>Licznik nieudnych logowań</th>
                    </tr>
                </thead><tbody>';


        foreach($users_list as $user) {
            echo '<tr>
                    <td>'. $user->ID .'</td>
                    <td><a href="userSetting.php?id='. $user->ID .'">'. $user->username .'</a></td>
                    <td><a href="userSetting.php?id='. $user->ID .'">'. $user->surname .' '. $user->name .'</a></td>
                    <td>'. $user->email .'</td>
                    <td>'. $user->password_date  .'</td> 
                    <td>'. $user->last_success_logged .'</td>
                    <td>'. $user->counter_success_logged .'</td>
                    <td>'. $user->last_failed_logged .'</td>
                    <td>'. $user->counter_failed_logged .'</td>
                </tr>';
        }
        echo '</table>';
    ?>

</BODY>
</HTML>