<?php

session_start();

include 'config/db_konekcija.php';

//if (filter_input(INPUT_GET, "action") == "IsprazniKorpu") {
//    foreach ($_SESSION['korpa'] as $key => $artikal) {
//        unset($_SESSION['korpa'][$key]);
//    }
//}



$error = "";

if (isset($_POST['submit'])) {

    session_start();
    
    $username = $_POST['usernameLog'];
    $loz = $_POST['passwordLog'];

    echo $username;
    echo $loz;

    $upit = "SELECT * FROM korisnik WHERE username = '" . $username . "' and password = '" . $loz . "'";
    $qu = mysqli_query($konekcija, $upit);
    if (!$qu) {
        print("Upit ne moze da se izvrsi" . $konekcija->error); 
    }

    while ($row = mysqli_fetch_array($qu)) {
        $id = $row['korisnikID'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
        $adresa = $row['username'];
        $password = $row['password'];
        $role = $row['admin'];
        $_SESSION["korisnik"] = $row["korisnikID"];
    }


    if ($username !== $adresa && $loz !== $password) {
        header("Location: index.php");
    } else if ($username == $adresa && $loz !== $password) {
        header("Location: index.php");
    } else if ($username !== $adresa && $loz == $password) {
        header("Location: index.php");
    } else if ($username == $adresa && $loz == $password && $role == 1) {

        header("Location: admin.php");

        $_SESSION['korisnikID'] = $id;
        $_SESSION['ime'] = $ime;
        $_SESSION['prezime'] = $prezime;
        $_SESSION['username'] = $adresa;
        $_SESSION['password'] = $password;
        $_SESSION['admin'] = $role;
    } else if ($username == $adresa && $loz == $password && $role == 0) {
        header("Location: user.php");

        $_SESSION['korisnikID'] = $id;
        $_SESSION['ime'] = $ime;
        $_SESSION['prezime'] = $prezime;
        $_SESSION['username'] = $adresa;
        $_SESSION['password'] = $password;
        $_SESSION['admin'] = $role;
    } else {
        header("Location: index.php");
    }
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

            <form class="w-100" id="prijavaforma" method="POST" autocomplete="off" style="padding-top: 1em; border-bottom: 4px #ff7f50 solid; padding-bottom: 2em">
                <h2 class="text-center">Prijavi se!</h2>

                <div class="form-row">
                    <div class="col">
                        <label for="username">Username</label>
                        <input type="text" class="form-control form-control-sm" name="usernameLog" id="usernameLog">
                    </div>

                    <div class="col">
                        <label for="password">Password</label>
                        <input type="password" class="form-control form-control-sm" name="passwordLog" id="passwordLog">
                    </div>
                    <input type="submit" name="submit" class="form-submit btn btn-md btn-primary w-100" value="Prijavi se" id="submit">

                </div>

            </form>

            <form class="w-100" id="registracijaforma" autocomplete="off" style="padding-top: 1em">

                <p class="text-center" style="color: #FFF; font-weight: 800;">Nemaš nalog?</p>

                <a href="registracija.php"><button type="button" class="btn btn-md btn-primary w-100">Registruj se</button></a>

            </form>

        </div>

    </div>


</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/mustache.js"></script>
<script>
    $(document).ready(function() {

        /*

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

        */

    })
</script>

</html>