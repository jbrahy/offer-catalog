<?= $this->extend("app") ?>

<?= $this->section("body") ?>

<script src="//cdn.ckeditor.com/4.4.5.1/standard/ckeditor.js"></script>

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
                <div class="col">
                    <form role="form" name="updatePlacementForm" id="updatePlacementForm"
                          action="<?php
						  echo base_url(); ?>/placements/updateplacement/<?php
						  echo $placementData->placement_id; ?>"
                          method="post" onsubmit="return checkFormValidation();" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="placement_group_id">Placement Group:</label>
                            <select class="form-select" id="placement_group_id" name="placement_group_id" required>
                                <option value="">-- Select Placement Group --</option>
								<?php
								if ((isset($resultsPlacementGroups)) && (count($resultsPlacementGroups) > 0))
								{
									foreach ($resultsPlacementGroups as $resultsPlacementGroupData)
									{
										?>
                                        <option value="<?php
										echo $resultsPlacementGroupData->placement_group_id; ?>" <?php
										if ($placementData->placement_group_id == $resultsPlacementGroupData->placement_group_id)
										{ ?> selected="selected" <?php
										} ?>><?php
											echo $resultsPlacementGroupData->placement_group; ?></option>
										<?php
									}
								}
								?>
                            </select>
                        </div>

                         <div class="form-group">
                                <label for="offer_id">Offer ID:</label>
                                <input type="text" class="form-control" id="offer_id" name="offer_id" value="<?php
                                echo $placementData->offer_id; ?>">
                            </div>

                            <div class="form-group">
                                <label for="url">Offer URL:</label>
                                <input type="text" class="form-control" id="url" name="offer_url"
                                       value="<?php
                                       echo $placementData->offer_url; ?>" required>
                            </div>


                        <div class="form-group">
                            <label for="headline">Headline:</label>
                            <input type="text" class="form-control" id="headline" name="headline"
                                   value="<?php
								   echo $placementData->headline; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="copy">Copy:</label>
                            <textarea class="form-control" id="editor1" name="copy" rows="5"
                                      required><?php
								echo $placementData->copy; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="main_image">Main Image: </label>
                            <input type="file" class="form-control" id="main_image" name="main_image">

							<?php
							if ( ! empty($placementData->main_image))
							{
							?>


                                <div class="img-wraps">
                                    <span class="closes" title="Delete">×</span>
                                    <img class="img-responsive" width="80" height="80"
                                         src="<?php
										 echo base_url(); ?>/uploads/<?php
										 echo $placementData->main_image; ?>">
                                </div>
                                <div style="height: 20px;"></div>
								<?php
							}
							?>

                           

                            <div class="form-group">
                                <label for="action_button_text">Action Button Text:</label>

                                <input type="text" class="form-control" id="action_button_text" name="action_button_text" value="<?php
                                    echo $placementData->action_button_text; ?>" 
                                   required>

                              
                            </div>

                            <?php
                            if ((isset($permission_option->archiving_placements)) && ($permission_option->archiving_placements == 1))
                            {
                            ?> 

                            Archiving Placement allowed
                            <?php
                            }
                            ?>


                            <div class="form-group">
                                <label for="placement_status_id">Placement Status:</label>
                                <select class="form-select" id="placement_status_id" name="placement_status_id"
                                        required>
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
											echo $resultsPlacementStatusData->placement_status_id; ?>" <?php
											if ($placementData->placement_status_id == $resultsPlacementStatusData->placement_status_id)
											{ ?> selected="selected" <?php
											} ?>><?php
												echo $resultsPlacementStatusData->placement_status; ?></option>
											<?php
                                            }
										}
									}
									?>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Update">

                                  <a href="<?php echo base_url();?>/placements/<?php echo $placementData->placement_group_id;?>" class="btn btn-primary">Back</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </main>


    <style type="text/css">

        .img-wraps {
            position: relative;
            display: inline-block;

            font-size: 0;
        }

        .img-wraps .closes {
            position: absolute;
            top: 5px;
            right: 8px;
            z-index: 100;
            background-color: #FFF;
            padding: 4px 3px;

            color: #000;
            font-weight: bold;
            cursor: pointer;

            text-align: center;
            font-size: 22px;
            line-height: 10px;
            border-radius: 50%;
            border: 1px solid red;
        }

        .img-wraps:hover .closes {
            opacity: 1;
        }

    </style>
    <script type="text/javascript">

        $(document).ready(function () {

            console.log('CKeditor Load');

             
              CKEDITOR.replace( 'editor1' );

            CKEDITOR.config.toolbar = [
               ['FontSize'],

               ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
               ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
               ['-','Link'],
               ['-','Source'],

               
            ] ;

            CKEDITOR.config.allowedContent = true;


           
            

            $('.closes').click(function () {

                ans = true; // confirm("Sure to Delete this Image?");
                if (ans == true) {
                    $.ajax({
                        //url: frm.attr('action'),
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo base_url();?>/placements/deleteimage/<?php echo $placementData->placement_id;?>",
                        data: {'placement_id': <?php echo $placementData->placement_id;?>},
                        success: function (response) {
                            console.log('status:' + response.status);
                            if (response.status == "success") {
                                //alert('Image has been Deleted Successfully.');
                                $('.img-wraps').hide();
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
            });
        });

        function checkFormValidation() {
            var form = document.getElementById('updatePlacementForm');


            var frm = $('#updatePlacementForm');

            ans = true; // confirm("Sure to Update This Placement?");
            return true;

        }

    </script>
<?= $this->endSection() ?>