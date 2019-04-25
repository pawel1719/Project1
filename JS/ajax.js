    function showListQueue() {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "JS/Request/Queue.php", true);

        //successfuly connection 
        xhr.addEventListener('load', function() {
            if(this.status === 200) {
                var myObj = JSON.parse(this.responseText);
                var result = '<option value="">Wybierz</option>';

                myObj.forEach(function(Queue) {
                    result += '<option value="' + Queue.ID +'">'+ Queue.queue +'</option>\n';
                })

            document.getElementById( "listQueue" ).innerHTML = result;
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

    function showList(url, destination) {
        //connect to download data 
        fetch( url )

            //converting to JSON
            .then(resp => resp.json())
            .then(resp => {
                let result = '<option value="">Wybierz</option>\n';

                for(let value in resp) {
                    let val1 = '';
                    let val2 = '';

                    for(let col in resp[value]) {
                        if (val1 == '') {
                            val1 = resp[value][col];
                        }else {
                            val2 = resp[value][col];
                        }
                    }
                    result += '<option value="' + val1 + '">' + val2 + '</option>\n'
                }

                //add list to html
                document.getElementById( destination ).innerHTML = result;
            })
            //error conection 
            .catch(error => {
                if (error.status === 404) {
                    console.log("Page not found");
                }else if (error.status === 500) {
                    console.log("Server error");
                }else {
                    // console.log("Error connection");
                }
            });
    }

    function showTable(_case, row, page) {
        if (row.length == 0) { 
            document.getElementById("tableTickets").innerHTML = "Error";
            return;
        } else {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("tableTickets").innerHTML = this.responseText;
                    document.getElementById("ticket_row").value = row;
                }
            };

            let link = "include/PHP/inc.tabTickets.php?data=" + _case + "&id=3&page=" + page + "&row=" + row;
            
            xmlhttp.open("GET", link, true);
            xmlhttp.send();
        }
    }

    function showTableAPI(_case, row, page) {
        const ourHeaders = new Headers();

        //dodajemy dodatkowe nagłówki
        ourHeaders.append("Content-Type", "text/plain");
        ourHeaders.append("X-My-Custom-Header", "CustomValue");

        fetch("include/PHP/inc.tabTickets.php", {
            method: "GET",
            //headers: ourHeaders,
            body: "data=" + _case + "3&row=" + row + "&page=" + page
        })
        .then(res => res.text())
        .then(res => {
            console.log("Succes send data:");
            console.log(res);
        })
        .catch(error => {
            if (error.status === 404) {
                console.log("Page not found");
            }else if (error.status === 500) {
                console.log("Server error");
            }else {
                console.log("Error connection " + error.status);
            }
        });
    }

    function addComment() {
        let comment = document.querySelector('#comment').value;
        let user = document.querySelector('#user').value;
        let ticket = document.querySelector('#ticket').value
        let token = document.querySelector('#token').value;

        if(comment.length > 5 && user.length != 0 && ticket.length != 0) {
            const xhr = new XMLHttpRequest();

            xhr.onload = function() {
                if(this.status === 200 && this.readyState == 4){
                    console.log(this.responseText);
                    alert(this.responseText);
                    comment = document.querySelector('#comment').value = '';
                }
            }

            let data = 'comment=' + comment + '&user=' + user + '&token=' + token + '&ticket=' + ticket;

            xhr.open('POST', 'JS/Request/AjaxRequest.php?id=4', true);
            xhr.setRequestHeader('Content-type', "application/x-www-form-urlencoded");
            xhr.send(data);
        }else{
            console.log('Error variables');
            alert('Za krótki komentarz!');
        }
    }//end

    function addCommentAPI() {
        //data from a form
        let comment     = document.querySelector("#comment").value;
        let user        = document.querySelector("#user").value;
        let ticket      = parseInt(document.querySelector("#ticket").value);
        let token       = document.querySelector("#token").value;
        let visibility  = document.querySelector("#visibility").value;
        
        if(comment.length > 5 && user.length != 0 && ticket.length != 0) {
            //headers to send
            const ourHeaders = new Headers();
            ourHeaders.append("Content-Type", "text/html");

            //data to send
            const data = new FormData();
            data.append('comment', comment);
            data.append('user', user);
            data.append('ticket', ticket);
            data.append('token', token);
            data.append('visibility', visibility);
            
            fetch("JS/Request/AjaxRequest.php?id=4", {
                    method: 'POST',
                    body: data
                })
                .then(resp => resp.text())
                .then(resp => {
                    //results for the success conection 
                    console.log(resp);
                    document.querySelector("#comment").value = "";
                    this.showCommentsAPI(ticket);
                    alert(resp);
                })
                .catch(error => {
                    //results for the errors conection
                    if (error.status === 404) {
                        console.log("Page not found");
                    }else if (error.status === 500) {
                        console.log("Server error");
                    }else {
                        console.log("Error connection " + error.status);
                    }
                });
        }else{
            //message when comment is shorter than 5 charset
            console.log('Error variables');
            alert('Za krótki komentarz!');
        }
    }//end function

    function showCommentsAPI(id) {
        if(typeof id === 'number') {
            const link = "include/PHP/inc.tabComments.php?id=" + id;

            fetch(link)
            .then(resp => resp.text())
            .then(resp => {
                document.getElementById("comments").innerHTML = resp;
                console.log('Comments refresh');
            })
            .catch(error => {
                //results for the errors conection
                if (error.status === 404) {
                    console.log("Page not found");
                }else if (error.status === 500) {
                    console.log("Server error");
                }else {
                    console.log("Error connection " + error.status);
                }
            });
        }else{
            console.log('Podana wartość ' + id + ' nie jest liczbą');
            console.log(typeof id);
        }
    }