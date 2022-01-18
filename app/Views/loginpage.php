<!doctype html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php
	echo base_url('assets/css/loginpage.css'); ?>   "/>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mb-5">
                <h2 class="heading-section">Admin Login</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-wrap p-4 p-md-5">
                    <div class="icon d-flex align-items-center justify-content-center"><span
                                class="fa fa-user-o"></span></div>
					<?php
					if (session()->has("success"))
					{
						?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert">
									<?= session("success") ?>
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>

					<?php
					if (session()->has("failure"))
					{
						?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
									<?= session("failure") ?>
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>
                    <form action="logincheck" class="login-form" method="POST">
                        <div class="form-group">
                            <input type="text" name="email" class="form-control rounded-left"
                                   placeholder="Email Address .." required>
                        </div>
                        <div class="form-group d-flex">
                            <input type="password" name="password" class="form-control rounded-left"
                                   placeholder="Password" required>
                        </div>
                        <div class="form-group d-md-flex">
                            <div class="w-50"><label class="checkbox-wrap checkbox-primary">Remember Me <input
                                            type="checkbox" checked><span class="checkmark"></span></label></div>
                            <div class="w-50 text-md-right"><a href="#">Forgot Password</a></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded submit p-3 px-5">Login Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
        crossorigin="anonymous"></script>
</body>
</html>