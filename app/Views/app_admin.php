<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php
    echo $title; ?> - Listicle Admin</title>
    <link rel="shortcut icon" type="image/png" href="<?php
  echo base_url('favicon.ico'); ?>"/>
    <link href="data:," rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php
  echo base_url('assets/css/style.css'); ?>"/>

    <script src="https://kit.fontawesome.com/9bfb243be9.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

    <link href="<?php
    echo base_url('assets/vendors/general/toastr/build/toastr.css'); ?>" rel="stylesheet" type="text/css" />

    <script src="<?php
    echo base_url('assets/vendors/general/toastr/build/toastr.min.js'); ?>" type="text/javascript"></script>

    <!-- STYLES -->
    <style {csp-style-nonce}>

        .form-group {

          margin-top: 0.7em;
          margin-bottom: 0.7em;
        }

        footer {
            background-color: rgba(221, 72, 20, .8);
            text-align: center;
        }

        footer .environment {
            color: rgba(255, 255, 255, 1);
            padding: 2rem 1.75rem;
        }

        footer .copyrights {
            background-color: rgba(62, 62, 62, 1);
            color: rgba(200, 200, 200, 1);
            padding: .25rem 1.75rem;
        }

        /* Footer  */
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 100px;
            border-top: 1px solid #ccc;
        }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php
    echo base_url(); ?>">Admin</a>

        

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php
        if ((isset($active_menu)) && ($active_menu == "sites"))
        { ?>active <?php
        } ?>"
                       href="<?php
        echo base_url(); ?>/sites/">Brands</a>
                </li>

                


                <li class="nav-item">
                    <a class="nav-link <?php
          if ((isset($active_menu)) && ($active_menu == "offers"))
          { ?>active <?php
          } ?>"
                       href="<?php
             echo base_url(); ?>/admin/offers/">Offers</a>
                </li>


                

                <li class="nav-item">
                    <a class="nav-link <?php
                    if ((isset($active_menu)) && ($active_menu == "profile"))
                    { ?>active <?php
                    } ?>"
                       href="<?php
                       echo base_url(); ?>/update-profile/">Change Password</a>
                </li>

               
                <li class="nav-item">
                    <a class="nav-link "
                       href="<?php
             echo base_url(); ?>/logout">Logout <?php
            if (isset($user_name))
            {
              echo '( ' . $user_name . ' )';
            } ?> </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?= $this->renderSection("body") ?>
<br/><br/>
<footer>
    <div class="copyrights">
        <p>&copy; <?= date('Y') ?> Offer Catalog Project Rendered in {elapsed_time}
            seconds, Environment: <?= ENVIRONMENT ?></p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<!-- SCRIPTS -->
</body>
</html>    