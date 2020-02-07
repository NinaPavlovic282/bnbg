<?php

include 'config/db_konekcija.php';

session_start();

$izvestaj = '';

if(isset($_POST['submit'])){

    

    if($_POST['datumOd'] != null && $_POST['datumDo'] != null){

        $datumOd = $_POST['datumOd'];
        $formatiranOd =  DateTime::createFromFormat('d. m. Y.', $datumOd);
        $stringOd = $formatiranOd->format('Y-m-d');
        $datumDo = $_POST['datumDo'];
        $formatiranDo =  DateTime::createFromFormat('d. m. Y.', $datumDo);
        $stringDo = $formatiranDo->format('Y-m-d');
        $nekretninaID = $_POST['nekretninaID'];

        $upit = "INSERT INTO rezervacija (korisnikID, nekretninaID, datumOd, datumDo) VALUES (" . $_SESSION['korisnikID'] . ", ". $nekretninaID . ", '" . $stringOd . "', '" . $stringDo . "')";

        $rez = $konekcija->query($upit);

        $izvestaj = '<p style="font-weight: 800; color: #2f3542; padding-bottom: 1em">Uspešno ste rezervisali nekretninu!</p>';

    } else {

        $izvestaj = '<p style="font-weight: 800; color: #ff6348; padding-bottom: 1em">Neuspesna rezervacija! Morate uneti datume.</p>';

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
    <link rel="stylesheet" href="css/jquery-ui.theme.css">
    <link rel="stylesheet" href="css/jquery-ui.structure.min.css">
    <link rel="icon" type="image/png" sizes="24x24" href="img/favicon.png">
    <title>bnbg</title>

</head>

<body>

    <div class="row no-gutters">

        <div class="col-md-8">

            <div class="lijeva">

                <img src="img/favicon.png" align="left" style="height: 3em;">
                <h1><a href="./">bnbg</a></h1>

                <?php echo $izvestaj ?>

                <div class="nekretnine row">

                    <template id="karticaSlobodna">
                        <div class="col-lg-3 kartica slobodna">
                            <p><span class="span">Adresa</span></br><strong>{{adresa}}</strong></p>
                            <p><span class="span">Opština</span></br><strong>{{nazivOpstine}}</strong></p>
                            <p><span class="span">Tip</span></br><strong>{{nazivTipa}}</strong></p>
                            <p><span class="span">Kvadratura</span></br><strong>{{kvadratura}} m²</strong></p>
                            <p><span class="span">Dostupnost</span></br><strong>Slobodna</strong></p>
                            
                            <?php
                                if (isset($_SESSION['korisnikID'])):
                                    ?>
                                    <input type="submit" name="dodaj" data-id="{{nekretninaID}}" id="rezervacija" style="margin-top:5px;" class="btn btn-sm btn-info w-100" value="Rezerviši"> 
                                    <?php
                                endif;
                                ?>

                            <!-- <button data-id="{{nekretninaID}}" id="rezervacija" class="btn btn-sm btn-info w-100">Rezerviši</button> -->
                        </div>
                    </template>
                    

                </div>
            </div>

        </div>

        <div class="col-md-4">

            <div class="desna">
                <div class="row no-gutters w-100">

                    <?php if(isset($_SESSION['korisnikID'])) { ?>

                    <p style="color: #FFF; font-weight: 800; padding-bottom: 2em; border-bottom: 4px #ff7f50 solid;" class = "w-100"><a href="logout.php">Odjavi se <?php echo $_SESSION['ime']." ".$_SESSION['prezime']; ?></a></p>

                    <?php } else { ?>
                        
                        <p style="color: #FFF; font-weight: 800; padding-bottom: 2em; border-bottom: 4px #ff7f50 solid;" class = "w-100"><a href="./">Prijavi se</a></p>

                    <?php } ?>
                    <form class="w-100" id="drugaforma" autocomplete="off" style="padding-top: 1em; border-bottom: 4px #ff7f50 solid; padding-bottom: 1em">

                        <h2>Pretraži po opštini</h2>

                        <div class="form-group">
                            <label class="label" for="inputState">Opština</label>
                            <select name="opstina" id="opstina" class="form-control form-control-sm">
                                <option value="-1">Sve</option>
                                <?php
                                $rezultati = mysqli_query($konekcija, "SELECT * FROM opstina ORDER BY nazivOpstine DESC");
                                while ($red = mysqli_fetch_array($rezultati)) {
                                ?> <option value="<?php echo $red["opstinaID"] ?>"> <?php echo $red["nazivOpstine"] ?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <button type="button" id="pretrazi" class="btn btn-md btn-primary w-100">Pretraži</button>

                    </form>

                    <form class="w-100" id="trecaforma" autocomplete="off" style="padding-top: 1em">
                        <h2>Konvertuj cenu</h2>

                        <div class="form-row">
                            <div class="col">
                                <label class="label" for="RSD">Iznos u RSD</label>
                                <input type="text" class="form-control form-control-sm" id="RSD">
                            </div>

                            <div class="col">
                                <label class="label" for="EUR">Iznos u USD</label>
                                <input type="text" class="form-control form-control-sm" id="USD" readonly>
                            </div>
                            <button type="button" id="konvertuj" class="btn btn-md btn-primary w-100">Konvertuj</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>






    <div class="modal fade" id="rezervacija_modal" tabindex="-1" role="dialog">
        <!-- Modal -->
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="naslov">Rezerviši</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="top: 0em !important;">
                        <span aria-hidden="true" style="height: 1em;">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form method="POST">
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label class="rezervacijalbl">Datum od</label>
                                <input type="text" name="datumOd" id="datumOd" class="form-control rezervacija" autocomplete="off">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="rezervacijalbl">Datum do</label>
                                <input type="text" name="datumDo" id="datumDo" class="form-control rezervacija" autocomplete="off">
                            </div>
                            <input type="hidden" name="nekretninaID" id="nekretninaID" />
                            <input type="submit" name="submit" class="form-submit btn btn-md btn-primary w-100" value="Rezerviši" id="submit">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light btn-block" data-dismiss="modal">Odustani</button>
                </div>
            </div>
        </div>
    </div>







</body>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/mustache.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>

<script>
    $(document).ready(function() {


        var $nek = $('.nekretnine');

        var $karticaSlobodna = $('#karticaSlobodna').html();

        function prikaziNekretninu(nekretnina) {
            if (nekretnina.slobodna == 0) {
                $nek.append(Mustache.render($karticaZauzeta, nekretnina));
            }
            if (nekretnina.slobodna == 1) {
                $nek.append(Mustache.render($karticaSlobodna, nekretnina));
            }

        };

        $.ajax({

            type: 'GET',
            dataType: 'json',
            url: 'api/nekretnine/?slobodna=1',
            success: function(nekretnine) {
                $nek.empty();
                $.each(nekretnine, function(i, nekretnina) {
                    prikaziNekretninu(nekretnina);
                })
            },
            error: function() {
                alert('Greska kod ucitavanja nekretnina');
            }

        });

        $(function() {
            var dateFormat = "mm/dd/yy",
                from = $("#datumOd")
                .datepicker({
                    dateFormat: "dd. mm. yy.",
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                })
                .on("change", function() {
                    to.datepicker("option", "minDate", getDate(this));
                }),
                to = $("#datumDo").datepicker({
                    dateFormat: "dd. mm. yy.",
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                })
                .on("change", function() {
                    from.datepicker("option", "maxDate", getDate(this));
                });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }
        });

        $('#pretrazi').on('click', function() {
            $opstina = $('#opstina').val();
            console.log($opstina);

            $.ajax({

                type: 'GET',
                dataType: 'json',
                url: 'api/nekretnine/?opstina=' + $opstina,
                success: function(nekretnine) {
                    if (Array.isArray(nekretnine) && nekretnine.length) {
                        console.log('usao u if');
                        $nek.empty();
                        $.each(nekretnine, function(i, nekretnina) {
                            prikaziNekretninu(nekretnina);
                        })
                    } else {
                        console.log('usao u else');
                        $nek.empty();
                        $nek.append("<h3>Nema slobodnih nekretnina u izabranoj opštini</h3>");
                    }

                },
                error: function() {
                    alert('Greska kod ucitavanja nekretnina');
                }

            });

        });

        $nek.delegate('#rezervacija', 'click', function() {

            $nekretninaID = $(this).attr('data-id');

            console.log($nekretninaID);

            var $adresa = '';

            $.ajax({

                type: 'GET',
                dataType: 'json',
                url: 'api/nekretnine/?id=' + $nekretninaID,
                success: function(data) {
                    $adresa = data['adresa'];
                    document.getElementById('naslov').innerHTML = "Rezervacija — " + $adresa;
                    $('#nekretninaID').val($nekretninaID);
                    console.log('ovo je iz ajaxa '+$('#nekretninaID').val());
                },
                error: function() {
                    alert('Greska kod nekretnine u modalu');
                }

            });

            $('#rezervacija_modal').modal('show');

        });

    })
</script>

</html>