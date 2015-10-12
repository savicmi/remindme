<?php
session_start();
// If user is not logged in, redirect to login page. This checks whether the username session var has been created yet.
if (!isset($_SESSION["username"])) {
    header("location: index.php");
} //else... load the user's page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>remindMe 1.0 :: My reminder</title>

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
                <div class="col-md-4 col-md-push-7">
                    <div class="panel panel-default panel3">

                        <div class="panel-body">

                            <ul class="tab-group col-md-12">
                                <li class="tab">
                                    <a href="#myProfileTab">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                        My profile
                                    </a>
                                </li>
                                <li class="tab active">
                                    <a href="#addEventTab">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        Add new event
                                    </a>
                                </li>
                                <li class="tab">
                                    <a href="#logoutTab">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                        Log Out
                                    </a>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>

                <div class="col-md-6 col-md-pull-4 col-md-offset-1">
                    <div class="panel panel-default panel4">

                        <div class="panel-heading">
                            <h1></h1>
                        </div>

                        <div class="panel-body">

                            <div id="myProfileTab">

                                <div class="static_data clearfix">
                                    <div class="col-xs-5 col-md-5">
                                        Username:
                                    </div>
                                    <div class="col-xs-7 col-md-7 data" id="username">
                                        *
                                    </div>
                                    <div class="col-xs-5 col-md-5">
                                        Date registered:
                                    </div>
                                    <div class="col-xs-7 col-md-7 data" id="date_reg">
                                        *
                                    </div>
                                </div>


                                <form class="form" id="userProfile">
                                    <div class="form-group col-md-6">
                                        <label for="firstname">
                                            First name
                                        </label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" required="required" placeholder="Your First name..." />
                                        <div class="alert alert-danger" role="alert">
                                            <span class="sr-only">Error:</span>
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="lasttname">
                                            Last name
                                        </label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" required="required" placeholder="Your Last name..." />
                                        <div class="alert alert-danger" role="alert">
                                            <span class="sr-only">Error:</span>
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">
                                            Email
                                        </label>
                                        <input type="text" class="form-control" id="email" name="email" required="required" placeholder="Your Email address..." />
                                        <div class="alert alert-danger" role="alert">
                                            <span class="sr-only">Error:</span>
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password">
                                            New password
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Set a password..." />
                                        <div class="alert alert-danger" role="alert">
                                            <span class="sr-only">Error:</span>
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="password2">
                                            Confirm password
                                        </label>
                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm password..." />
                                        <div class="alert alert-danger" role="alert">
                                            <span class="sr-only">Error:</span>
                                            Error
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="time_zone">
                                            Time zone
                                        </label>
                                        <select class="form-control" id="time_zone">
                                        </select>

                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn btn-custom btn-lg btn-block" id="save">Save user data</button>
                                    </div>
                                </form>
                            </div>

                            <div id="addEventTab">


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
                <div class="col-md-10 col-md-offset-1">
                    <div class="text-right">
                        <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span>
                        <a href="mailto:savicmi@gmail.com">Miloš Savić</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal -->
    <div id="messageModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Title</h4>
                </div>
                <div class="modal-body">
                    <p>Modal Message</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                </div>
            </div>

        </div>
    </div>

    <!-- jQuery library -->
    <script src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-2.1.4.min.js"></script>
    <!-- Bootstrap framework and individual JavaScript files -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>

</body>
</html>
