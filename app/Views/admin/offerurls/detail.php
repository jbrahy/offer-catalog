<?= $this->extend("app") ?>

<?= $this->section("body") ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1><?php echo $title;?></h1>
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
                <div class="col-md-6">
                    <a href="<?php echo base_url(); ?>/placements/<?php
                                           echo $placementData->placement_group_id; ?>" class="btn btn-primary btn-lg" tabindex="-1" role="button" >
                        <i class="fas fa-arrow-left"></i> Back</a>
                </div>

                <div class="col-md-6" style="text-align: right;">
                    
                    
                    
                </div>
            </div>


            <div class="row">
                <div class="col">

                    

                    <table class="table table-bordered">
                      <thead>

                      </thead>
                      <tbody>

                        <tr class="">
                          <th scope="row" style="width: 250px;">Placement Group</th>
                          <td colspan="2" class=""><?php echo $placementData->placement_group;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Offer ID</th>
                          <td colspan="2" class=""><?php echo $placementData->offer_id;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Offer URL</th>
                          <td colspan="2" class=""><?php echo $placementData->offer_url;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Headline</th>
                          <td colspan="2" class=""><?php echo $placementData->headline;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Copy</th>
                          <td colspan="2" class="" style="text-align: left;"><?php echo $placementData->copy;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Main Image</th>
                          <td colspan="2" class="">
                            
                            <?php
                                if (!empty($placementData->main_image))
                                {
                            ?>
                                <img src="<?php echo base_url(); ?>/uploads/<?php echo $placementData->main_image; ?>" height="50"/>
                            <?php
                                }
                            ?>
                            
                          </td>
                        </tr>

                        <tr class="">
                          <th scope="row">Action Button</th>
                          <td colspan="2" class=""><?php echo $placementData->action_button_text;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Created At</th>
                          <td colspan="2" class=""><?php echo $placementData->created_at;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Updated At</th>
                          <td colspan="2" class=""><?php echo $placementData->updated_at;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Placement Status</th>
                          <td colspan="2" class=""><?php echo $placementData->placement_status;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Everflow  Offer Status</th>
                          <td colspan="2" class=""><?php echo $placementData->offer_affiliate_status;?></td>
                        </tr>

                        <tr class="">
                          <th scope="row">Imported</th>
                          <td colspan="2" class=""><?php if ($placementData->is_imported == 1) {?> <span class="badge bg-warning text-dark">Yes</span> <?php }?></td>
                        </tr>

                       
                        

                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
    <script type="text/javascript">

        
        
    </script>

<?= $this->endSection() ?>