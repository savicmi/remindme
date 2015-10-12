<?php
include 'remindme.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>remindMe 1.0 :: Account confirmation</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/style.css" />
    <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>

    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-6  col-md-offset-3">
                    <div class="logo">
                        <img src="./images/remindMe_logo.png" alt="remindMe logo" />
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6  col-md-offset-3">
                    <div class="panel panel-default panel2">

                        <div class="panel-body">
                            <div class="col-md-12">                                
                                    <?php echo verify(); ?>                                
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-custom btn-lg btn-block" id="goto-login">Log in now</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="text-right">
                        <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span>
                        <a href="mailto:savicmi@gmail.com">Miloš Savić</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery library -->
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.4.min.js"></script>
    <!-- Bootstrap framework and individual JavaScript files -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>

</body>
</html>
