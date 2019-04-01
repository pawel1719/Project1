<?php
    require_once 'classes/config.php';
?>

<!DOCTYPE html>
<HTML lang="pl-PL">
<HEAD>

    <?php require_once PATH_TO_HEAD; ?>
    <script src="JS/ajax.js"></script> 

</HEAD>
<BODY onLoad="showList('JS/Request/AjaxRequest.php?id=1', 'listQueue'); showList('JS/Request/AjaxRequest.php?id=2','listPriority')">


    <form method="POST" class="login">

        <label type="ticketQueue" onClick="showList('JS/Request/AjaxRequest.php?id=1', 'listQueue')">Koleja</label>
        <select name="ticketQueue" id="listQueue" >
            <!-- option from database --> 
        </select>

        <label type="ticketPriority" onClick="showList('JS/Request/AjaxRequest.php?id=2','listPriority')">Status</label>
        <select name="ticketPriority" id="listPriority" >
            <!-- option from database --> 
        </select>

        <label for="subject">Temat:</label>
        <input type="text" name="subject" placeholder="Temat..." />

        <label for="description">Opis:</label>
        <input type="textarea" name="description" placeholder="Opis zgłoszenia..." />

        <input type="submit" name="Dodaj" />

    </form>

</BODY>
</HTML>