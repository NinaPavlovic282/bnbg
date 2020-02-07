<?php

$conn = mysqli_connect("localhost", "root", "", "nekretnine");

$upit = "SELECT * FROM rezervacija r JOIN nekretnina n ON (r.nekretninaID = n.nekretninaID) JOIN korisnik k ON (r.korisnikID = k.korisnikID)";

mysqli_query($conn, "SET NAMES utf8");

$res = mysqli_query($conn, $upit);

$rezervacije = mysqli_fetch_all($res, MYSQLI_ASSOC);

$output = '
			<table class="table table-sm" id="rezervacije"> 
				<thead>
					<tr>
						<th>Adresa</th>
						<th>Korisnik</th>
						<th>Datum od</th>
						<th>Datum do</th>
					</tr>
				</thead>
				<tbody>';

foreach ($rezervacije as $r) {

    $adresa = $r['adresa'];
    $korisnik = $r['ime'] . " " . $r['prezime'];
    $datumOd = $r['datumOd'];
    $datumDo = $r['datumDo'];

    /*
    $formatiranOd =  DateTime::createFromFormat('Y-m-d', $datumOd);
    $stringOd = $formatiranOd->format('d. m. Y.');

    $formatiranDo =  DateTime::createFromFormat('Y-m-d', $datumDo);
    $stringDo = $formatiranDo->format('d. m. Y.');
    */

    $output .= '<tr>';
    $output .= '<td>' . $adresa . '</td>';
    $output .= '<td>' . $korisnik . '</td>';
    $output .= '<td>' . $datumOd . '</td>';
    $output .= '<td>' . $datumDo . '</td>';
    $output .= '</tr>';
}

$output .= '</tbody></table>';

echo $output;
