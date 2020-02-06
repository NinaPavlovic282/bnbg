<?php

$konekcija = mysqli_connect("localhost", "root", "", "nekretnine");
mysqli_set_charset($konekcija, 'utf8');

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // ------------------------------------------------------ / GET

    if (isset($_GET['id'])) {

        $nekretninaID = $konekcija->real_escape_string($_GET['id']);
        $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
            FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID)
            WHERE n.nekretninaID = '$nekretninaID'");
        $data = $sql->fetch_assoc();
    } else if (isset($_GET['slobodna'])) {

        if ($_GET['slobodna'] == 1 || $_GET['slobodna'] == 0) {

            $slobodna = $konekcija->real_escape_string($_GET['slobodna']);
            $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
                FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID)
                WHERE n.slobodna = '$slobodna'");
            while ($d = $sql->fetch_assoc()) {
                $data[] = $d;
            }
        } else {

            $data = array();
            $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
                FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID) ORDER BY n.nekretninaID ASC");
            while ($d = $sql->fetch_assoc()) {
                $data[] = $d;
            }
        }
    } else if (isset($_GET['opstina'])) {

        if ($_GET['opstina'] == -1) {

            $data = array();
            $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
            FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID) WHERE n.slobodna = 1 ORDER BY n.nekretninaID ASC");
            while ($d = $sql->fetch_assoc()) {
                $data[] = $d;
            }

        } else {

            
            $data = array();
            $opstina = $konekcija->real_escape_string($_GET['opstina']);
            $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
                FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID)
                WHERE n.opstinaID = '$opstina' AND n.slobodna = 1");

            while ($d = $sql->fetch_assoc()) {
                $data[] = $d;
            }
        }

    } else {

        $data = array();
        $sql = $konekcija->query("SELECT n.nekretninaID, n.kvadratura, n.adresa, o.nazivOpstine, t.nazivTipa, n.slobodna 
            FROM nekretnina n JOIN opstina o ON (n.opstinaID = o.opstinaID) JOIN tip t ON (n.tipID = t.tipID) ORDER BY n.nekretninaID ASC");
        while ($d = $sql->fetch_assoc()) {
            $data[] = $d;
        }
    }

    exit(json_encode($data));

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ------------------------------------------------------ / POST

    if (isset($_POST['adresa']) && isset($_POST['opstina']) && isset($_POST['kvadratura']) && isset($_POST['tip'])) {

        $adresa = $konekcija->real_escape_string($_POST['adresa']);
        $opstina = $konekcija->real_escape_string($_POST['opstina']);
        $kvadratura = $konekcija->real_escape_string($_POST['kvadratura']);
        $tip = $konekcija->real_escape_string($_POST['tip']);
        //$slobodna = $konekcija->real_escape_string($_POST['slobodna']);

        $konekcija->query("INSERT INTO nekretnina (adresa, opstinaID, kvadratura, tipID) VALUES ('$adresa', '$opstina', '$kvadratura', '$tip')");
    } else if (isset($_POST['username']) && isset($_POST['password'])) {

        $username = $konekcija->real_escape_string($_POST['username']);
        $password = $konekcija->real_escape_string($_POST['password']);

        $sql = $konekcija->query("SELECT korisnikID, username, password, ime, prezime, admin 
            FROM korisnik WHERE username = '".$username."' AND password = '".$password."'");

        $data = $sql->fetch_assoc();

        if($data['admin'] == 1){

            $url = './admin.php';
            $ime = $data['ime'];
            $admin = $data['admin'];
            exit(json_encode(array("status" => "Uspesno", "url" => $url, "admin" => $url)));

        } else if ($data['admin'] == -1){

            $url = './user.php';
            $ime = $data['ime'];
            $admin = $data['admin'];
            exit(json_encode(array("status" => "Uspesno", "url" => $url, "admin" => $url)));

        } else if ($data['admin'] == 0) {
            $url = './index.php';
            $ime = $data['ime'];
            $admin = $data['admin'];
            
            exit(json_encode(array("status" => "Uspesno", "url" => $url, "admin" => $url)));
        }

    }

} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') { // ------------------------------------------------------ / PUT

    if (!isset($_GET['id'])) {
        exit(json_encode(array("status" => "Neuspesno", "poruka" => "Proverite ulazne parametre")));
    }

    $nekretninaID = $konekcija->real_escape_string($_GET['id']);
    $sql = $konekcija->query("SELECT slobodna FROM nekretnina WHERE nekretninaID = '$nekretninaID'");
    $data = $sql->fetch_assoc();

    if ($data['slobodna'] == 1) {

        $konekcija->query("UPDATE nekretnina SET slobodna = 0 WHERE nekretninaID = '$nekretninaID'");
        exit(json_encode(array("status" => "Uspesno", "poruka" => "Nekretnina " . $nekretninaID . " je sada slobodna.")));
    }

    if ($data['slobodna'] == 0) {

        $konekcija->query("UPDATE nekretnina SET slobodna = 1 WHERE nekretninaID = '$nekretninaID'");
        exit(json_encode(array("status" => "Uspesno", "poruka" => "Nekretnina " . $nekretninaID . " je sada zauzeta.")));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { // ------------------------------------------------------ / DELETE

    if (!isset($_GET['id'])) {
        exit(json_encode(array("status" => "Neuspesan DELETE", "poruka" => "Proverite ulazne parametre")));
    }

    $nekretninaID = $konekcija->real_escape_string($_GET['id']);

    $konekcija->query("DELETE FROM nekretnina WHERE nekretninaID = '$nekretninaID'");

    exit(json_encode(array("status" => "Uspesno", "poruka" => "Nekretnina je uspesno izbrisana")));
}
