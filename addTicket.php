<?php
    require_once 'classes/config.php';
?>

<!DOCTYPE html>
<HTML lang="pl-PL">
<HEAD>

    <?php require_once PATH_TO_HEAD; ?>
    <script src="JS/ajax.js"></script> 

</HEAD>
<BODY>
    <!-- JavaScript testy -->
    
    <script>
        
        function myData() {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "JS/Request/Queue.php", true);

            //successfuly connection
            xhr.addEventListener('load', function() {
                if(this.status === 200) {
                    var myObj = JSON.parse(this.responseText);
                    var result = '';

                    myObj.forEach(function(Queue) {
                        result += '<option value="' + Queue.ID +'">'+ Queue.queue +'</option>\n';
                    })

                    document.getElementById("listQueue").innerHTML = result;
                    //console.log(result);
                    
                }
            })
            //error concetion 
            xhr.addEventListener('error', function() {
                if( this.status === 404 ) {
                    console.log('Page not found');
                }else if( this.status === 500 ) {
                    console.log('Server error');
                }else {
                    console.log('Error connection');
                }
            })

            xhr.send();
        }

    </script>

    <!-- <button type="button" >Try it</button> -->


    <form method="POST" class="login">

        <label type="ticketQueue">Koleja</label>
        <select name="ticketQueue" id="listQueue" onClick="myData()" placeholder="Wybierz">
            <option value="">Wybierz</option>
        </select>

        <label type="ticketPriority">Status</label>
        <select name="ticketPriority">
            <option value="">Wybierz</option>
            <option value="1">Normalny</option>
            <option value="2">Pilny</option>
            <option value="3">Krytyczny</option>
        </select>

        <label for="subject">Temat:</label>
        <input type="text" name="subject" placeholder="Temat..." />

        <label for="description">Opis:</label>
        <input type="textarea" name="description" placeholder="Opis zgłoszenia..." />

        <input type="submit" name="Dodaj" />

    </form>

</BODY>
</HTML>