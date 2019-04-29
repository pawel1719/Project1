<?php
    require_once 'classes/config.php';
    require_once PATH_TO_CLASSES_DB;
    require_once PATH_TO_CLASSES_INPUT;
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
        $users_details = $db->get('users', array('ID', '=', Input::get('id')))->results()[0];

        echo '<table class="table table-bordered table-hover table-dark">
                <thead>
                    <tr>
                        <th>ID</th><td>'. $users_details->ID .'</td>
                        <th>username</th><td>'. $users_details->username .'</td>
                    </tr><tr>
                        <th>password</th><td>'. $users_details->password .'</td>
                        <th>salt</th><td>'. $users_details->salt .'</td>
                    </tr><tr>
                        <th>password_date</th><td>'. $users_details->password_date .'</td>
                        <th>name</th><td>'. $users_details->name .'</td>
                    </tr><tr>
                        <th>surname</th><td>'. $users_details->surname .'</td>
                        <th>email</th><td>'. $users_details->email .'</td>                   
                    </tr><tr>
                        <th>number_phone</th><td>'. $users_details->number_phone .'</td>
                        <th>date_birdth</th><td>'. $users_details->date_birdth .'</td>               
                    </tr><tr>
                        <th>city</th><td>'. $users_details->city .'</td>
                        <th>street</th><td>'. $users_details->street .'</td>                   
                    </tr><tr>
                        <th>no_house</th><td>'. $users_details->no_house .'</td>
                        <th>no_flat</th><td>'. $users_details->no_flat .'</td>                  
                    </tr><tr>
                        <th>joined</th><td>'. $users_details->joined .'</td>
                        <th>group</th><td>'. $users_details->group .'</td> 
                    </tr><tr>
                        <th>consent_rodo</th><td>'. $users_details->consent_rodo .'</td>
                        <th>date_changed_password_old_1</th><td>'. $users_details->date_changed_password_old_1 .'</td>
                    </tr><tr>
                        <th>date_changed_password_old_2</th><td>'. $users_details->date_changed_password_old_2 .'</td>
                        <th>date_changed_password_old_3</th><td>'. $users_details->date_changed_password_old_3 .'</td>
                    </tr><tr>
                        <th>last_success_logged</th><td>'. $users_details->last_success_logged .'</td> 
                        <th>last_failed_logged</th><td>'. $users_details->last_failed_logged .'</td>
                    </tr><tr>
                        <th>counter_success_logged</th><td>'. $users_details->counter_success_logged .'</td>
                        <th>counter_failed_logged</th>
                        <td>'. $users_details->counter_failed_logged .'</td>
                    </tr>
                </table>';


        $per = $db->get('groups', array('ID', '=', $users_details->group))->results()[0];
        $perr = json_decode($per->permission, true);

        echo 'GRUPA: '. $per->name .'<br/>';
        foreach( $perr as $name => $group) {
            echo $name .' '. $group .'<br/>';
            foreach($group as $key => $access) {
                echo $key .' - '. $access .' ';
            }
            echo '<br/>============================================<br/>';
        }

    ?>

</BODY>
</HTML>