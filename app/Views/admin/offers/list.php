<?= $this->extend("app_admin") ?>

<?= $this->section("body") ?>


    <main>
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                    &nbsp;
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                    <h1><?php echo $title; ?></h1>
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
                <div class="col">
                </div>
                <div class="col" style="text-align:right;">
                    <a href="<?php echo base_url(); ?>/admin/offers/add-new" class="btn btn-success"><i
                                class="fa fa-plus" aria-hidden="true"></i> New Offer</a>
                </div>
            </div>


            <div class="row ">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">

						<?php
						$tab_no = 0;
						foreach ($result_brands as $brand)
						{
							$tab_no++;
							?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php if ($tab_no == 1) { ?> active <?php } ?>"
                                        id="brand<?php echo $brand->brand_id; ?>-tab" data-bs-toggle="tab"
                                        data-bs-target="#brand<?php echo $brand->brand_id; ?>" type="button" role="tab"
                                        aria-controls="brand<?php echo $brand->brand_id; ?>"
                                        aria-selected="true"><?php echo $brand->brand; ?></button>
                            </li>
							<?php
						}
						?>

                    </ul>
                    <div class="tab-content" id="myTabContent">
						<?php
						$tab_no = 0;
						foreach ($result_brands as $brand)
						{
							$tab_no++;
							?>
                            <div class="tab-pane fade <?php if ($tab_no == 1) { ?> show active <?php } ?>"
                                 id="brand<?php echo $brand->brand_id; ?>" role="tabpanel"
                                 aria-labelledby="brand<?php echo $brand->brand_id; ?>-tab">


                                <!-- -->
                                <table class="table" id="myTable<?php echo $brand->brand_id; ?>">
                                    <thead>
                                    <tr>
                                        <th scope="col" width="20">

                                        </th>
                                        <th scope="col">Offer ID</th>
                                        <th scope="col">Offer Name</th>
                                        <th scope="col">Option</th>
                                    </tr>
                                    </thead>
                                    <tbody>

									<?php

									if ((isset($result_offers[$brand->brand_id])) && (count($result_offers[$brand->brand_id]) > 0))
									{
										foreach ($result_offers[$brand->brand_id] as $offer)
										{

											?>
                                            <tr>
                                                <td scope="col" width="20">

                                                </td>
                                                <td scope="col"><?php echo $offer->offer_id; ?></td>
                                                <td scope="col"><?php echo $offer->offer; ?></td>
                                                <td scope="col">

                                                    <a class="edit"
                                                       href="<?php
													   echo base_url(); ?>/admin/offers/edit/<?php echo $offer->offer_id; ?>"
                                                       title="Edit" data-toggle="tooltip" style="text-decoration: none !important;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="" href="<?php
													echo base_url(); ?>/admin/offerurls/add-new/<?php echo $offer->brand_id; ?>/<?php echo $offer->offer_id; ?>"
                                                       title="New Offer URL" data-toggle="tooltip"
                                                       style="text-decoration: none !important;"><i class="fa fa-plus" aria-hidden="true"></i></a>

                                                    <a data-bs-toggle="collapse"
                                                       href="#collapseOffers_<?php echo $offer->offer_id; ?>" style="text-decoration: none !important;">
                                                        <i class="fa fa-list" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="" href="javascript:shareit(<?php echo $offer->offer_id; ?>,'<?php echo $offer->offer; ?>');"
                                                       title="Share Offer URL" data-toggle="tooltip" 
                                                       style="text-decoration: none !important;"><i class="fa fa-qrcode" aria-hidden="true"></i></a>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="" class="collapse"
                                                    id="collapseOffers_<?php echo $offer->offer_id; ?>">


                                                    <div>

														<?php
														if (isset($result_offers_url_list[$brand->brand_id][$offer->offer_id]))
														{

														?>

                                                        <div class="card">
                                                            <div class="card-header">
                                                                Offer URL
                                                            </div>
                                                            <div class="card-body">


                                                                <table class="table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>QR</th>
                                                                        <th>URL</th>
                                                                        <th>Type</th>
                                                                        <th>Option</th>
                                                                    </tr>
                                                                    </thead>

                                                                    <tbody>

																	<?php
																	foreach ($result_offers_url_list[$brand->brand_id][$offer->offer_id] as $offers)
																	{
																		?>
                                                                        <tr style="">
                                                                            <td style="border: 0px solid #ced4da;">
																				<?php echo $offers->offer_url_id; ?>
                                                                            </td>
                                                                            <td style="border: 0px solid #ced4da; vertical-align: top;">
                                                                                <img src="<?php echo base_url(); ?>/uploads/qr/url-<?php echo $offers->offer_url_id; ?>.png"
                                                                                     width="75" class="img-fluid"
                                                                                     alt="">
                                                                            </td>
                                                                            <td style="border: 0px solid #ced4da;">
                                                                                <a href="<?php echo $offers->offer_url; ?>"
                                                                                   target="_blank"><?php echo $offers->offer_url; ?></a>
                                                                            </td>
                                                                            <td style="border: 0px solid #ced4da;">
																				<?php echo $offers->offer_url_type; ?>
                                                                            </td>

                                                                            <td style="border: 0px solid #ced4da;">
                                                                                <a href="<?php
																				echo base_url(); ?>/admin/offerurls/edit/<?php echo $offers->brand_id; ?>/<?php echo $offers->offer_url_id; ?>"
                                                                                   target="_blank">
                                                                                    <i class="fa fa-pencil-square-o"
                                                                                       aria-hidden="true"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
																		<?php
																	}
																	?>

                                                                    </tbody>
                                                                </table>

                                                            </div><!-- ./card-body -->
                                                        </div><!-- ./card -->

                                                    </div>

													<?php
													} else
													{
														?>
                                                        <div class="card">
                                                            <div class="card-header">
                                                                Offer URL
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="alert alert-danger" role="alert">
                                                                    No Offer URL Posted Yet.
                                                                    <a href="<?php
																	echo base_url(); ?>/admin/offerurls/add-new/<?php echo $brand->brand_id; ?>/<?php echo $offer->offer_id; ?>"
                                                                       target="_blank" class="btn btn-danger"><i
                                                                                class="fa fa-plus"
                                                                                aria-hidden="true"></i> Add Offer
                                                                        URL</a>
                                                                </div>


                                                            </div>
                                                        </div>
														<?php
													}
													?>

                                                </td>
                                            </tr>
											<?php
										}
									} else
									{
										?>

                                        <tr>
                                            <td colspan="5" style="text-align:center;">
                                                <div class="alert alert-danger" role="alert">
                                                    No Offers
                                                </div>

                                            </td>
                                        </tr>
										<?php
									}
									?>


                                    </tbody>
                                </table>
                                <!-- -->


                            </div>


							<?php
						}
						?>
                    </div>


                </div>
            </div>

        </div>
 
        <div id="offer_modal" class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div> <!-- ./modal -->


    </main>


    <script type="text/javascript">

        function is_url (str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }

        $(document).ready(function () {


        });

        function shareit (offer_id, offer_name) {
            //alert(offer_id);

            $('.modal-title').html('Offer :: ' + offer_name);
            $('.modal-body').html('<?php echo base_url();?>/offer-url/' + offer_id);

            $('#offer_modal').modal('show');
        }


        function checkFormValidation () {


            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>