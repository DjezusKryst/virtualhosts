<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniHost - Installation</title>
    <link rel="shortcut icon" href="img/cloud.png">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/installer.css">
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Wire+One' rel='stylesheet' type='text/css'>
</head>

<body class="theme">
<section class="section" id="head">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 text-center">
                <h1 class="title">UniHost</h1>
                <div class="toHide">
                    <h2 class="subtitle">Hébergeur d'hôtes virtuels</h2>
                    <h3 class="tagline">
                        L'application web a besoin d'une base de données pour fonctionner.<br/>
                        Appuyez sur le bouton ci-dessous pour laisser l'installateur s'en charger.
                    </h3>
                </div>
                <div>
                    <aside class="q-question toShow" style="display: none;">
                        <h5 class="subtitle">Configuration de UniHost</h5>
                        <h3 class="tagline">
                            Les valeurs par défaut seront chargées si vous validez sans modifier les champs.
                        </h3>
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="127.0.0.1"
                                       aria-describedby="basic-addon2" id="serveur">
                                <span class="input-group-btn">
                                     <button id="btnServ" class="btn btn-default" type="button" onclick="document.getElementById('serveur').value='127.0.0.1';">
                                         Adresse du serveur
                                     </button>
                                </span>
                            </div><br/>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="virtualhosts"
                                       aria-describedby="basic-addon2" id="bdd">
                                <span class="input-group-btn">
                                    <button id="btnBDD" class="btn btn-default" type="button" onclick="document.getElementById('bdd').value='virtualhosts';">
                                         Nom de la BDD
                                    </button>
                               </span>
                            </div><br/>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="root"
                                       aria-describedby="basic-addon2" id="user">
                                <span class="input-group-btn">
                                    <button id="btnUser" class="btn btn-default" type="button" onclick="document.getElementById('user').value='root';">
                                        Nom d'utilisateur de la BDD
                                    </button>
                               </span>
                            </div><br/>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="laisser vide pour aucun"
                                       aria-describedby="basic-addon2">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        Mot de passe de l'utilisateur
                                    </button>
                               </span>
                            </div><br/>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="3306"
                                       aria-describedby="basic-addon2" id="port">
                                <span class="input-group-btn">
                                    <button id="btnPort" class="btn btn-default" type="button" onclick="document.getElementById('port').value='3306';">
                                        Port de la BDD
                                    </button>
                               </span>
                            </div><br/>
                            <button type="submit" class="btn btn-primary btn-lg toShow load" id="submit" onclick="launchInstall()"
                                    style="display:none"
                                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> En cours...">
                                Installer UniHost
                            </button>
                        </form>
                    </aside>
                    <button type="button" class="btn btn-primary btn-lg toHide load" id="load"
                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> En cours...">
                        Configurer UniHost
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="resultat">

</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="js/modernizr.custom.72241.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
      integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="js/installer.js"></script>
<script>
    $('.load').on('click', function () {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function () {
            $this.button('reset');
        }, 3000);
    });
</script>
<script>
    $('.q-question').hide();

    $('#load').click(function () {
        $('.q-question').slideToggle();
        $('.toHide').hide();
        $('.toShow').show();
    });
</script>
<script>
    $(document).ready(function() {
        $('#submit').click(function(e) {
            if($('#serveur').val() == ''){
                $('#btnServ').trigger('click');
            }

            if($('#bdd').val() == ''){
                $('#btnBDD').trigger('click');
            }

            if($('#user').val() == ''){
                $('#btnUser').trigger('click');
            }

            if($('#port').val() == ''){
                $('#btnPort').trigger('click');
            }

            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'launch_install.php',
                data: {
                    serveur: $('#serveur').val(),
                    bdd: $('#bdd').val(),
                    user: $('#user').val(),
                    mdp: $('#mdp').val(),
                    port: $('#port').val()
                },
                success: function(data)
                {
                    $("#resultat").html(data);
                }
            });
        });
    });
</script>
</body>
</html>
