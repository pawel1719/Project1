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


<!DOCTYPE HTML lang="pl=PL">
<HEAD>
    
    <?php   require_once PATH_TO_HEAD;  ?>

<script>
    function showTable(row, page) {
        if (row.length == 0) { 
            document.getElementById("tableTickets").innerHTML = "Error";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tableTickets").innerHTML = this.responseText;
                    document.getElementById("ticket_row").value = row;
                    //console.log('Success');
                }
            };
            xmlhttp.open("GET", "http://localhost/quiz/include/PHP/inc.tabTickets.php?id=3&page=" + page + "&row=" + row, true);
            xmlhttp.send();
        }
    }

</script>

</HEAD>
<BODY onLoad="showTable(<?php echo (int)Input::get('row') .','. (int)Input::get('page'); ?>)">

    <?php
        require_once PATH_TO_MENU;
    ?>

    <div id="tableTickets">
    </div>


</BODY>
</HTML>