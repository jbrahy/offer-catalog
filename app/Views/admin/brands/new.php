<?= $this->extend("app_admin") ?>

<?= $this->section("body") ?>



<script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>
    

    <main>
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                    &nbsp;
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                    <h1><?php echo $title;?></h1>
                </div>
            </div>



            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">


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

                    <div class="alert alert-success" role="alert" style="display: none;">

                    </div>

                    <div class="alert alert-danger" role="alert" style="display: none;">

                    </div>

                </div>
            </div> 

            <div class="row ">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">       


                    <form role="form" name="newPlacementForm" id="newPlacementForm"
                          action="<?php
						  echo base_url(); ?>/placements/addnewplacement" method="post"
                          onsubmit="return checkFormValidation();" enctype="multipart/form-data">


                        <div class="form-group">
                            <label for="placement_group_id">Brand:</label>
                            <select class="form-select" id="placement_group_id" name="placement_group_id" required>
                                <option value="">-- Select Brand --</option>
								<?php
								if ((isset($resultsPlacementGroupsList)) && (count($resultsPlacementGroupsList) > 0))
								{
									foreach ($resultsPlacementGroupsList as $resultsPlacementGroupData)
									{
										?>
                                        <option value="<?php
										echo $resultsPlacementGroupData->placement_group_id; ?>"><?php
											echo $resultsPlacementGroupData->placement_group; ?></option>
										<?php
									}
								}
								?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="offer_id">Offer Name:</label>
                            <input type="text" class="form-control" id="offer_id" name="offer_id">
                        </div>

                        <div class="form-group">
                            <label for="offer_url">Offer URL:</label>
                            <input type="text" class="form-control" id="offer_url" name="offer_url">
                        </div>

                        <div class="form-group">
                            <label for="headline">Headline:</label>
                            <input type="text" class="form-control" id="headline" name="headline" required>
                        </div>

                        <div class="form-group">
                            <label for="copy">Copy:</label>
                            <textarea class="ckeditor form-control" id="editor1" name="copy"  rows="5" required ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="main_image">Main Image:</label>
                            <input type="file" class="form-control" id="main_image" name="main_image" >
                        </div>

                        <div class="form-group">
                            <label for="action_button_text">Action Button Text:</label>
                            <input type="text" class="form-control" id="action_button_text" name="action_button_text"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="placement_status_id">Placement Status:</label>
                            <select class="form-select" id="placement_status_id" name="placement_status_id" required>
                                <option value="">-- Select Status --</option>
								<?php
								if ((isset($resultsPlacementStatusList)) && (count($resultsPlacementStatusList) > 0))
								{
									foreach ($resultsPlacementStatusList as $resultsPlacementStatusData)
									{
                                        if ((($resultsPlacementStatusData->placement_status_id == 3) && (isset($permission_option->archiving_placements)) && ($permission_option->archiving_placements == 1)) || ($resultsPlacementStatusData->placement_status_id != 3))
                                            {
										?>


                                        <option value="<?php
										echo $resultsPlacementStatusData->placement_status_id; ?>"><?php
											echo $resultsPlacementStatusData->placement_status; ?></option>
										<?php
                                    }
									}
								}
								?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                                <div class="form-group" style="margin-top: 2em;">
                                    <input type="submit" class="btn btn-success" value="Submit">
                                    
                                    <a href="<?php echo base_url();?>/admin/offers/" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </main>


   
    <script type="text/javascript">

        function is_url(str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }

        $(document).ready(function () {
            console.log("Here I am");
            $('#offer_id').change(function () {

                console.log("Offer ID changed");

                var offerId = $(this).val();

            });

            CKEDITOR.replaceAll( 'editor1', {
                                  entities : false
                              } );

            CKEDITOR.config.toolbar = [
               ['FontSize'],

               ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
               ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
               ['-','Link'],
               ['-','Source'],

               
            ] ;
            CKEDITOR.config.allowedContent = true;



            if ( window.localStorage ) {

             


                var offer_id           = localStorage.getItem("offer_id");
                var offer_url          = localStorage.getItem("offer_url");
                var copy_text          = localStorage.getItem("copy_text");
                var action_button_text = localStorage.getItem("action_button_text");

                var headline           = localStorage.getItem("headline");
                

                var placement_group  = localStorage.getItem("placement_group");
                var placement_status = localStorage.getItem("placement_status");

                var headline = localStorage.getItem("headline");
                
                document.getElementById('offer_id').value = offer_id;
                document.getElementById('offer_url').value = offer_url;

                document.getElementById('headline').value = headline;
                CKEDITOR.instances.editor1.setData(copy_text);
                document.getElementById('placement_group_id').value  = placement_group;
                document.getElementById('action_button_text').value  = action_button_text;
                document.getElementById('placement_status_id').value = placement_status;
            } else {
                alert('localStorage is not available');
            }
            
        });

       
        function checkFormValidation() {
            console.log("Loaded new placement on submit");
            var form = document.getElementById('newPlacementForm');


            var frm = $('#newPlacementForm');


            var placement_group_elem  = document.getElementById("placement_group_id");
            var placement_group_value = placement_group_elem.options[placement_group_elem.selectedIndex].value;
            /*
            var placement_group_text = placement_group_elem.options[placement_group_elem.selectedIndex].text;
            */



            var headline           = document.getElementById("headline").value;
            var offer_id           = document.getElementById("offer_id").value;
            var offer_url          = document.getElementById("offer_url").value;
            var editor1            = CKEDITOR.instances.editor1.getData(); 
            var action_button_text = document.getElementById("action_button_text").value;

            var placement_status_elem  = document.getElementById("placement_status_id");
            var placement_status_value = placement_status_elem.options[placement_status_elem.selectedIndex].value;
            
            localStorage.setItem("placement_group",    placement_group_value);
            localStorage.setItem("headline",           headline);
            localStorage.setItem("offer_id",           offer_id);
            localStorage.setItem("offer_url",          offer_url);
            localStorage.setItem("copy_text",          editor1);
            localStorage.setItem("action_button_text", action_button_text);

            localStorage.setItem("placement_status",    placement_status_value);


            console.log('Editor:' + editor1);



            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>