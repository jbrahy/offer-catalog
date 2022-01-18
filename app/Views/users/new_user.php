<?= $this->extend("app") ?>

<?= $this->section("body") ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1><?php
						echo $title; ?></h1>
                </div>
            </div>

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

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert" style="display: none;">

                    </div>

                    <div class="alert alert-success" role="alert" style="display: none;">

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    
                    <div class="row">
                                            <div class="col">

                                        


                                                <form role="form" name="saveUserProfileForm" id="saveUserProfileForm"  action="<?php
                                                      echo base_url(); ?>/users/save-new-user" method="post" onsubmit="return checkFormValidation();">

                                                    <div class="form-group">
                                                        <label for="site">First Name:</label>
                                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                                               placeholder="" value="" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="token">Last Name:</label>
                                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="token">Email Address:</label>
                                                        <input type="text" class="form-control" id="email_address" name="email_address" placeholder="" value="" >
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="token">Username:</label>
                                                        <input type="text" class="form-control" id="username" name="username" placeholder=""
                                                               value="" required>
                                                    </div>

                                                    

                                                    <div class="form-group">
                                                        <label for="current_password">Password:</label>
                                                         <input type="text" class="form-control" id="current_password" name="current_password" placeholder="" value="" >
                                                    </div>


                                                    
                                                    




                                                    <div class="form-group">
                                                        <input type="submit" class="btn btn-success" value="Save User">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                </div>
            </div>



            
        </div>
    </main>


    <style type="text/css">


    </style>

    <script type="text/javascript">

        

        function is_url(str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }


        function checkFormValidation() {
            var form = document.getElementById('saveUserProfileForm');
            var frm = $('#saveUserProfileForm');

            var username          = document.getElementById("username").value;
            var current_password  = document.getElementById("current_password").value;
           

            if(username.length < 5)
            {
                alert('Minimum 5 Characters are Required');
                return false;
            }

            /*
            if (new_password === ""){
                 alert('Password EMpty');
            }
            */

            if (current_password === ""){
                alert('Please Provide Current Password.');
                document.getElementById("current_password").focus();
                return false;
            }

         

            
            return true;
            

        }

    </script>
<?= $this->endSection() ?>