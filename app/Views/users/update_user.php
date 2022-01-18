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
                    
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">

                                
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link  active "
                                                    id="status-1-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#status-1"
                                                    type="button" role="tab"
                                                    aria-controls="status-1"
                                                    aria-selected="true ">Profile
                                            </button>
                                        </li>
                                        
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link "
                                                    id="status-3-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#status-3"
                                                    type="button" role="tab"
                                                    aria-controls="status-3"
                                                    aria-selected="">Permission
                                            </button>
                                        </li>
                                        

                            </ul>
                        </div>
                        <div class="">
                        <div class="tab-content" id="myTabContent"
                             style="padding:1.5rem;margin-right:0; margin-left:0;   border-top-left-radius:.25rem;  border-top-right-radius:.25rem;border-left: 1px solid #dee2e6;border-right: 1px solid #dee2e6;border-bottom: 1px solid #dee2e6;border-top: 0px;">
                                    <div class="tab-pane fade show  show active "
                                         id="status-1" role="tabpanel"
                                         aria-labelledby="status-1-tab"
                                         style="padding:20px;">

                                        <!-- -->
                                        <div class="row">
                                            <div class="col">

                                        


                                                <form role="form" name="updateProfileForm" id="updateProfileForm"  action="<?php
                                                      echo base_url(); ?>/users/saveuser/<?php echo $user_profile->user_id; ?>" method="post" onsubmit="return checkFormValidation();">

                                                    <div class="form-group">
                                                        <label for="site">First Name:</label>
                                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                                               placeholder="" value="<?php echo $user_profile->first_name; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="token">Last Name:</label>
                                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="<?php echo $user_profile->last_name; ?>" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="token">Email Address:</label>
                                                        <input type="text" class="form-control" id="email_address" name="email_address" placeholder="" value="<?php echo $user_profile->email_address; ?>" >
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="token">Username:</label>
                                                        <input type="text" class="form-control" id="username" name="username" placeholder=""
                                                               value="<?php
                                                               echo $user_profile->username; ?>" required>
                                                    </div>

                                                    

                                                    <div class="form-group">
                                                        <label for="current_password">New Password: &nbsp;&nbsp;&nbsp;&nbsp;(Provide Only If You Want to Reset with New Password)</label>
                                                         <input type="text" class="form-control" id="current_password" name="current_password" placeholder="" value="" >
                                                    </div>


                                                    
                                                    




                                                    <div class="form-group">
                                                        <input type="submit" class="btn btn-success" value="Update Profile">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- -->

                                    </div>
                                    <div class="tab-pane fade show "
                                         id="status-3" role="tabpanel"
                                         aria-labelledby="status-3-tab"
                                         style="padding:20px;">

                                        <!-- -->

                                        <table class="table" id="myTable1">
                                            <thead>
                                            <tr>
                                                <th scope="col" width="20"></th>
                                                <th scope="col" align="left" width="260">Options</th>
                                                <th scope="col" width="180">Choice</th>
                                                <th scope="col"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Adding Placements</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="adding_placements" onclick="permission_values_save('Adding Placements',adding_placements');" <?php if ((isset($user_permission->adding_placements)) && ($user_permission->adding_placements==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="adding_placements">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Updating Placements</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="updating_placements" onclick="permission_values_save('Updating Placements','updating_placements');" <?php if ((isset($user_permission->updating_placements)) && ($user_permission->updating_placements==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="updating_placements">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                 <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Sorting Placements</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="sorting_placements" onclick="permission_values_save('Sorting Placements','sorting_placements');" <?php if ((isset($user_permission->sorting_placements)) && ($user_permission->sorting_placements==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="sorting_placements">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>



                                                 <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Archiving Placements</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="archiving_placements" onclick="permission_values_save('Archiving Placements','archiving_placements');" <?php if ((isset($user_permission->archiving_placements)) && ($user_permission->archiving_placements==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="archiving_placements">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Deleting Placements</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="deleting_placements" onclick="permission_values_save('Deleting Placements','deleting_placements');" <?php if ((isset($user_permission->deleting_placements)) && ($user_permission->deleting_placements==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="deleting_placements">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>



                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Adding Placement Groups</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="adding_placement_groups" onclick="permission_values_save('Adding Placement Groups','adding_placement_groups');" <?php if ((isset($user_permission->adding_placement_groups)) && ($user_permission->adding_placement_groups==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="adding_placement_groups">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>


                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Updating Placement Groups</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="updating_placement_groups" onclick="permission_values_save('Updating Placement Groups','updating_placement_groups');" <?php if ((isset($user_permission->updating_placement_groups)) && ($user_permission->updating_placement_groups==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="updating_placement_groups">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>


                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Deleting Placement Groups</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="deleting_placement_groups" onclick="permission_values_save('Deleting Placement Groups','deleting_placement_groups');" <?php if ((isset($user_permission->deleting_placement_groups)) && ($user_permission->deleting_placement_groups==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="deleting_placement_groups">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                 <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Managing Users</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="managing_users" onclick="permission_values_save('Managing Users','managing_users');" <?php if ((isset($user_permission->managing_users)) && ($user_permission->managing_users==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="managing_users">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Managing SSH Keys</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="managing_ssh_keys" onclick="permission_values_save('Managing SSH Keys','managing_ssh_keys');" <?php if ((isset($user_permission->managing_ssh_keys)) && ($user_permission->managing_ssh_keys==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="managing_ssh_keys">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Vertical Enable</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="vertical_enabled" onclick="permission_values_save('Vertical Enable','vertical_enabled');" <?php if ((isset($user_permission->vertical_enabled)) && ($user_permission->vertical_enabled==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="vertical_enabled">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Reporting Enable</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="reporting_enabled" onclick="permission_values_save('Reporting Enable','reporting_enabled');" <?php if ((isset($user_permission->reporting_enabled)) && ($user_permission->reporting_enabled==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="reporting_enabled">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>


                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Updating Admin From Github</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="updating_admin_from_github" onclick="permission_values_save('Updating Admin From Github','updating_admin_from_github');" <?php if ((isset($user_permission->updating_admin_from_github)) && ($user_permission->updating_admin_from_github==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="updating_admin_from_github">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>


                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Updating App from Github</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="updating_app_from_github" onclick="permission_values_save('Updating App from Github','updating_app_from_github');" <?php if ((isset($user_permission->updating_app_from_github)) && ($user_permission->updating_app_from_github==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="updating_app_from_github">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>

                                                <tr>
                                                    <td scope="col" align="center"></td>
                                                    <td scope="col" align="left">Updating Web Templates From Github</td>
                                                    <td scope="col" align="left">
                                                         <div class="form-check form-switch">
                                                          <input class="form-check-input" type="checkbox" id="updating_web_templates_from_github" onclick="permission_values_save('Updating Web Templates From Github','updating_web_templates_from_github');" <?php if ((isset($user_permission->updating_web_templates_from_github)) && ($user_permission->updating_web_templates_from_github==1)){ ?>checked <?php } ?>>
                                                          <label class="form-check-label" for="updating_web_templates_from_github">No / Yes</label>
                                                        </div>
                                                    </td>
                                                    <td scope="col" align="center"></td>

                                                </tr>
                                                
                                            
                                            </tbody>
                                        </table>
                                       
                                        <!-- -->

                                    </div>

                        </div><!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>


                </div>
            </div>



            
        </div>
    </main>


    <style type="text/css">


    </style>

    <script type="text/javascript">

        function permission_values_save(general_name, elem)
        {
            sel_value = 1;  
            if (document.getElementById(elem).checked) 
            {
              sel_value = 1;
            } else {
              sel_value = 0;
            }

            toastr.options = {
              "closeButton": true,
              "newestOnTop": true,
              "progressBar": true,
              "positionClass": "toast-top-right",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }

            ans = true; // confirm("Sure to Add New Site?");
            if (ans == true) {


                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url();?>/users/permission-option/<?php echo $user_profile->user_id; ?>/"+elem,
                    data: {'elem_val':sel_value},
                    success: function (response) {
                        console.log('status:' + response.status);
                        if (response.status == "success") {
                            //alert(response.message);
                            toastr.success(response.message, general_name);
                            //window.location.reload();
                        } else if (response.status == "failure") {
                            //alert('Error: ' + response.message);

                        }


                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                    },
                });
            }
        }

        function is_url(str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }


        function checkFormValidation() {
            var form = document.getElementById('updateProfileForm');
            var frm = $('#updateProfileForm');

            var username            = document.getElementById("username").value;
            var current_password    = document.getElementById("current_password").value;
           

            if(username.length < 5)
            {
                alert('Username must be Minimum 5 Characters.');
                return false;
            }

            /*
            if (new_password === ""){
                 alert('Password EMpty');
            }
            */

            if (current_password !== ""){

              if (current_password.length < 5){
                   alert('Minimum 5 Characters are Required.');
                   document.getElementById("current_password").focus();
                   return false;
              }
                     
            }

         

            
            return true;
            

        }

    </script>
<?= $this->endSection() ?>