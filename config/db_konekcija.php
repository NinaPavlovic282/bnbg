<?php

$konekcija = mysqli_connect("localhost", "root", "", "nekretnine");
mysqli_set_charset($konekcija, 'utf8');

if(!$konekcija) { echo 'Doslo je do greske pri povezivanju sa serverom.'; }

?>