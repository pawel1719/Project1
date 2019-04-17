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

    function showTable(row, page) {
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

            let link = "include/PHP/inc.tabTickets.php?id=3&page=" + page + "&row=" + row;
            
            xmlhttp.open("GET", link, true);
            xmlhttp.send();
        }
    }

    function showTableAPI(row, page) {
        const ourHeaders = new Headers();

        //dodajemy dodatkowe nagłówki
        ourHeaders.append("Content-Type", "text/plain");
        ourHeaders.append("X-My-Custom-Header", "CustomValue");

        fetch("include/PHP/inc.tabTickets.php", {
            method: "GET",
            //headers: ourHeaders,
            body: "id=3&row=" + row + "&page=" + page
        })
        //.then(res => res.json())
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