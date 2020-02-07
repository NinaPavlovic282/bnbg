<?php

include 'config/db_konekcija.php';


session_start();
//session_destroy();



$error = "";

if (isset($_POST['submit'])) {

    include 'config/db_konekcija.php';
    include 'index.php';

    $name = $_POST['ime'];
    $lastName = $_POST['prezime'];
    $username = $_POST['usernameReg'];
    $password = $_POST['passwordReg'];
    $role = 0;

    $query = "INSERT INTO korisnik (ime, prezime, username, password, admin) VALUES ('" . $name . "', '" . $lastName . "', '" . $username . "', '" . $password . "', " . $role . ")";

    $rez = $konekcija->query($query);


    
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="icon" type="image/png" sizes="24x24" href="img/favicon.png">
    <title>bnbg</title>

</head>

<body>

    <div class="row no-gutters align-items-center justify-content-center" style="height: 8em; padding: 1.5em;">

        <div class="row">
            <img src="img/favicon.png" align="left" style="height: 3em;">
            <h1><a href="./">bnbg</a></h1>
        </div>

    </div>


    <div class="row no-gutters align-items-center justify-content-center" style="height: calc(100vh - 8em); background-color: #ff6348;">

        <div>

            <form class="w-100" id="registracijaforma" method="POST" autocomplete="off" style="padding-top: 1em">
                <h2 class="text-center">Registracija</h2>

                <div class="form-row">
                    <div class="col">
                        <label class="label" for="ime">Ime</label>
                        <input type="text" class="form-control form-control-sm" name="ime" id="ime">
                    </div>

                    <div class="col">
                        <label class="label" for="prezime">Prezime</label>
                        <input type="text" class="form-control form-control-sm" name="prezime" id="prezime">
                    </div>

                </div>

                <div class="form-row" style="padding-top: 1em;">
                    <div class="col">
                        <label class="label" for="username">Username</label>
                        <input type="text" class="form-control form-control-sm" name="usernameReg" id="usernameReg">
                    </div>

                    <div class="col">
                        <label class="label" for="password">Password</label>
                        <input type="password" class="form-control form-control-sm" name="passwordReg" id="passwordReg">
                    </div>
                    <input type="submit" name="submit" id="submit" class="form-submit btn btn-md btn-primary w-100" value="Registruj se"/>

                </div>

            </form>

        </div>



    </div>


</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/mustache.js"></script>
<script>
    $(document).ready(function() {

        $('#prijavi_se').on('click', function() {
            var username = $('#usernameLog').val();
            var password = $('#passwordLog').val();

            console.log(username);
            console.log(password);

            $.ajax({

                type: 'POST',
                url: 'api/api.php',
                dataType: 'json',
                data: {username: username, password:password},
                success: function(data) {
                    var adresa = data['url'];
                    window.location.href = adresa;
                },
                error: function() {
                    alert('Greska kod ucitavanja nekretnina');
                }

            }); 

        });

    })
</script>

</html>