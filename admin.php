<?php

include 'config/db_konekcija.php';

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

    <div class="row no-gutters">

        <div class="col-md-8">

            <div class="lijeva">

                <img src="img/favicon.png" align="left" style="height: 3em;">
                <h1><a href="./">bnbg</a></h1>

                <div class="nekretnine row">

                    <script type="text/template" id="karticaZauzeta">
                        <div class="col-lg-3 kartica zauzeta">
                            <button data-id="{{nekretninaID}}" id="delete" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><span class="span">Adresa</span></br><strong>{{adresa}}</strong></p>
                            <p><span class="span">Opština</span></br><strong>{{nazivOpstine}}</strong></p>
                            <p><span class="span">Tip</span></br><strong>{{nazivTipa}}</strong></p>
                            <p><span class="span">Kvadratura</span></br><strong>{{kvadratura}} m²</strong></p>
                            <p><span class="span">Dostupnost</span></br><strong>Zauzeta</strong></p>
                            <button data-id="{{nekretninaID}}" id="put" class="btn btn-sm btn-info w-100">Promeni dostupnost</button>
                        </div>
                    </script>

                    <script type="text/template" id="karticaSlobodna">
                        <div class="col-lg-3 kartica slobodna">
                            <button data-id="{{nekretninaID}}" id="delete" type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><span class="span">Adresa</span></br><strong>{{adresa}}</strong></p>
                            <p><span class="span">Opština</span></br><strong>{{nazivOpstine}}</strong></p>
                            <p><span class="span">Tip</span></br><strong>{{nazivTipa}}</strong></p>
                            <p><span class="span">Kvadratura</span></br><strong>{{kvadratura}} m²</strong></p>
                            <p><span class="span">Dostupnost</span></br><strong>Slobodna</strong></p>
                            <button data-id="{{nekretninaID}}" id="put" class="btn btn-sm btn-info w-100">Promeni dostupnost</button>
                        </div>
                    </script>

                </div>
            </div>

        </div>

        <div class="col-md-4">

            <div class="desna">
                <div class="row no-gutters w-100">

                    <form class="w-100" autocomplete="off" method="POST" style="border-bottom: 4px #ff7f50 solid; padding-bottom: 1em">
                        <h2>Unesi nekretninu</h2>

                        <div class="form-group">
                            <label for="inputState">Opština</label>
                            <select name="opstina" id="opstina" class="form-control form-control-sm">
                                <?php
                                $rezultati = mysqli_query($konekcija, "SELECT * FROM opstina ORDER BY nazivOpstine DESC");
                                while ($red = mysqli_fetch_array($rezultati)) {
                                ?> <option value="<?php echo $red["opstinaID"] ?>"> <?php echo $red["nazivOpstine"] ?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="adresa">Adresa</label>
                            <input type="text" class="form-control form-control-sm" id="adresa">
                        </div>

                        <div class="form-group">
                            <label for="inputState">Tip nekretnine</label>
                            <select name="tip" id="tip" class="form-control form-control-sm">
                                <?php
                                $rezultati = mysqli_query($konekcija, "SELECT * FROM tip");
                                while ($red = mysqli_fetch_array($rezultati)) {
                                ?> <option value="<?php echo $red["tipID"] ?>"> <?php echo $red["nazivTipa"] ?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Kvadratura</label>
                            <input type="text" class="form-control form-control-sm" id="kvadratura">
                        </div>

                        <button type="submit" id="unesi" class="btn btn-md btn-primary w-100">Unesi</button>

                    </form>

                    <form class="w-100" id="drugaforma" autocomplete="off" style="padding-top: 1em; border-bottom: 4px #ff7f50 solid; padding-bottom: 1em">
                        <h2>Pretraži po dostupnosti</h2>

                        <div class="form-group">
                            <label for="inputState">Prikaži</label>
                            <select name="dostupnost" id="dostupnost" class="form-control form-control-sm">
                                <option value="-1">Sve</option>
                                <option value="0">Zauzete</option>
                                <option value="1">Slobodne</option>
                            </select>
                        </div>

                        <button type="button" id="pretrazi" class="btn btn-md btn-primary w-100">Pretraži</button>

                    </form>

                    <form class="w-100" id="trecaforma" autocomplete="off" style="padding-top: 1em">
                        <h2>Konvertuj cenu</h2>

                        <div class="form-row">
                            <div class="col">
                                <label for="RSD">Iznos u RSD</label>
                                <input type="text" class="form-control form-control-sm" id="RSD">
                            </div>

                            <div class="col">
                                <label for="EUR">Iznos u USD</label>
                                <input type="text" class="form-control form-control-sm" id="USD" readonly>
                            </div>
                            <button type="button" id="konvertuj" class="btn btn-md btn-primary w-100">Konvertuj</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/mustache.js"></script>
<script>
    $(document).ready(function() {

        var $nek = $('.nekretnine');

        var $adresa = $('#adresa');
        var $opstina = $('#opstina');
        var $tip = $('#tip');
        var $kvadratura = $('#kvadratura');

        var $karticaZauzeta = $('#karticaZauzeta').html();
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
            url: 'api/nekretnine',
            success: function(nekretnine) {
                $.each(nekretnine, function(i, nekretnina) {
                    prikaziNekretninu(nekretnina);
                })
            },
            error: function() {
                alert('Greska kod ucitavanja nekretnina');
            }

        });

        $('#unesi').on('click', function() {

            if ($adresa.val() === "" && $kvadratura.val() === "") {
                document.getElementById("adresa").focus();
                alert('Morate uneti adresu i kvadraturu');
                return false;
            }
            if ($kvadratura.val() === "") {
                alert('Morate uneti kvadraturu');
                document.getElementById("kvadratura").focus();
                return false;
            }
            if ($adresa.val() === "") {
                alert('Morate uneti adresu');
                document.getElementById("adresa").focus();
                return false;
            }
            if (isNaN($kvadratura.val())) {
                alert('Kvadratura mora biti uneta brojevima');
                document.getElementById("kvadratura").focus();
                return false;
            }

            var nekretnina = {
                adresa: $adresa.val(),
                opstina: $opstina.val(),
                tip: $tip.val(),
                kvadratura: $kvadratura.val(),
            };


            $.ajax({

                type: 'POST',
                url: 'api/nekretnine',
                data: nekretnina,
                success: function(novaNekretnina) {
                    prikaziNekretninu(novaNekretnina);
                },
                error: function() {
                    alert('Greska kod unosa nekretnine');
                }

            })

        });

        $nek.delegate('#delete', 'click', function() {

            var $div = $(this).closest('div');

            $.ajax({
                type: 'DELETE',
                url: 'api/nekretnine/' + $(this).attr('data-id'),
                success: function() {
                    $div.remove();
                },
                error: function() {
                    alert('DELETE greska');
                }
            })

        });

        $('#pretrazi').on('click', function() {
            $dostupnost = $('#dostupnost').val();
            console.log($dostupnost);

            $.ajax({

                type: 'GET',
                dataType: 'json',
                url: 'api/nekretnine/?slobodna=' + $dostupnost,
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
 
        });

        $('#konvertuj').on('click', function() {

            var $rsdiznos = $('#RSD').val();
            var $usdkurs = '';

            $.ajax({ 

                type: 'GET',
                url: 'https://cors-anywhere.herokuapp.com/https://kurs.resenje.org/api/v1/currencies/usd/rates/today',
                success: function(data) {

                    $usdkurs = data['exchange_middle'];
                    console.log('Trenutni srednji USD kurs je ' + $usdkurs);
                    $('#USD').val('$' + ($rsdiznos / $usdkurs).toFixed(2));

                },
                error: function() {
                    console.log('Greska kod konverzije');
                }

            })

        });


        $nek.delegate('#put', 'click', function() {

            var $id = $(this).attr('data-id');
            var $div = $(this).closest('div');

            $.ajax({
                type: 'PUT',
                url: 'api/nekretnine/' + $(this).attr('data-id'),
                data: $id,
                success: function(data) {

                    /*
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: 'api/nekretnine/' + $id,
                        success: function(nekr){
                            $div.remove();
                            prikaziNekretninu(nekr);
                            console.log(nekr['slobodna']);
                        }

                    })
                    */
                    $nek.empty();

                    $.ajax({

                        type: 'GET',
                        dataType: 'json',
                        url: 'api/nekretnine',
                        success: function(nekretnine) {
                            $.each(nekretnine, function(i, nekretnina) {
                                prikaziNekretninu(nekretnina);
                            })
                        },
                        error: function() {
                            alert('Greska kod ucitavanja nekretnina');
                        }

                    })

                },
                error: function() {
                    alert('PUT greska');
                }
            })

        });

    })
</script>

</html>