<?php
session_start();

 include 'config/db_konekcija.php';

//if (filter_input(INPUT_GET, "action") == "IsprazniKorpu") {
//    foreach ($_SESSION['korpa'] as $key => $artikal) {
//        unset($_SESSION['korpa'][$key]);
//    }
//}



$error = "";

if(isset($_POST['submit']))
{
    $username = $_POST['usernameLog'];
    $loz = $_POST['passwordLog'];

    echo $username;
    echo $loz;
    
    $upit = "SELECT * FROM korisnik WHERE username = '" . $username . "' and password = '" . $loz . "'";
    $qu = mysqli_query($conn, $upit); 
    if(!$qu)
    {
        print("Upit ne moze da se izvrsi" . $conn->error);
    }
    
    while($row = mysqli_fetch_array($qu))
    {
        $id = $row['korisnikID'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
        $adresa = $row['username'];
        $password = $row['password'];
        $role = $row['admin'];
        $_SESSION["korisnik"]=$row["korisnikID"];
    }
    
    if ($username !== $adresa && $loz !== $password) 
    {
        header("Location: user.php");
    }
    else if($username == $adresa && $loz !== $password) 
    {
        header("Location: user.php");
    }
    else if($username !== $adresa && $loz == $password)
    {
        header("Location: user.php");
    }
    else if ($username == $adresa && $loz == $password && $role == 1) 
    {

        header("Location: user.php");
                
                $_SESSION['korisnikID'] = $id;
                $_SESSION['ime'] = $ime;
                $_SESSION['prezime'] = $prezime;
                $_SESSION['username'] = $adresa;
                $_SESSION['password'] = $password;
                $_SESSION['admin'] = $role;
    }
    else if ($username == $adresa && $loz == $password && $role == 0) 
    {
        header("Location: user.php");
                
                $_SESSION['korisnikID'] = $id;
                $_SESSION['ime'] = $ime;
                $_SESSION['prezime'] = $prezime;
                $_SESSION['username'] = $adresa;
                $_SESSION['password'] = $password;
                $_SESSION['admin'] = $role;
    }
    else 
    {
        header("Location: user.php");
    }
}
?>