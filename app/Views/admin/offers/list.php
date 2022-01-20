<?= $this->extend("app") ?>

<?= $this->section("body") ?>
    <link rel="stylesheet" type="text/css" href="<?php
	echo base_url('assets/css/dragtable.css'); ?>   "/>
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1><?php
						echo $title; ?></h1>
                </div>
            </div>

            <?php
            if ((isset($permission_option->adding_placements)) && ($permission_option->adding_placements == 1))
            {
            ?> 
            

            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-12">
                    <a href="<?php
					echo base_url(); ?>/placements/newplacement" class="btn btn-success"><i class="fas fa-plus"></i> New
                        Placement</a>
                </div>
            </div>

            <?php
            }
            ?>

            <div class="row">
                <div class="col">
                    <form role="form" name="placementDetailsForm" id="placementDetailsForm"
                          action="<?php
						  echo base_url(); ?>/placements/index/0" method="post"
                          onsubmit="return checkFormValidation();">



                        <div class="row">
                            <div class="col-sm-6">

                                <select class="form-select" id="placement_group_id" name="placement_group_id" required>
                                    <option value="">-- Select Placement Group --</option>
									<?php
									if ((isset($resultsPlacementGroups)) && (count($resultsPlacementGroups) > 0))
									{
										foreach ($resultsPlacementGroups as $resultsPlacementGroupData)
										{
									?>
                                            <option
                                                    value="<?php
													echo $resultsPlacementGroupData->placement_group_id; ?>"
												<?php
												if (($placement_group_id > 0) && ($placement_group_id == $resultsPlacementGroupData->placement_group_id))
												{
													?>
                                                    selected="selected"
													<?php
												} ?>><?php
												echo $resultsPlacementGroupData->placement_group; ?></option>
									<?php
										}
									}
									?>

                                </select>
                            </div>

                            <div class="col-sm-4">
                                <input type="submit" class="btn btn-success" value="Load">
                            </div>
                        </div>
                    </form>
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

			<?php
			$status_id = array();

			if ((isset($resultNumberOfStatus)) && (count($resultNumberOfStatus) > 0))
				foreach ($resultNumberOfStatus as $row)
				{

					$status_id[$row->placement_status_id] = $row->totalNo;
				}
			/*
			if ((isset($resultsPlacementStatus)) && (count($resultsPlacementStatus)>0))
			foreach ($resultsPlacementStatus as $row)
			{

				echo '<pre>'.print_r($row, true).'</pre>';
			}

			die();
			*/
			//echo '<pre>'.print_r($status_id, true).'</pre>';die();
			?>

            <div class="row">
                <div class="col">

                    <div class="alert icon-alert with-arrow alert-success form-alter" role="alert"
                         style="display:none;">
                        <i class="fa fa-fw fa-check-circle"></i>
                        <strong> Success ! </strong> <span class="success-message"> Placement Order has been updated successfully </span>
                    </div>
                    <div class="alert icon-alert with-arrow alert-danger form-alter" role="alert" style="display:none;">
                        <i class="fa fa-fw fa-times-circle"></i>
                        <strong> Note !</strong> <span class="warning-message"> Placement Order can't be updated </span>
                    </div>

                    <div class="" style=" ">


                        <ul class="nav nav-tabs" id="myTab" role="tablist">

							<?php

							$tab_no = 0;
							if ((isset($resultsPlacementStatus)) && (count($resultsPlacementStatus) > 0))
								foreach ($resultsPlacementStatus as $row)
								{
									$tab_no++;
									?>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php if ($tab_no == 1) { ?> active <?php } ?>"
                                                id="status-<?php echo $row->placement_status_id; ?>-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#status-<?php echo $row->placement_status_id; ?>"
                                                type="button" role="tab"
                                                aria-controls="status-<?php echo $row->placement_status_id; ?>"
                                                aria-selected="<?php if ($tab_no == $row->placement_status_id) { ?>true <?php } ?>"><?php echo $row->placement_status; ?>
                                            (
											<?php
											if (isset($status_id[$row->placement_status_id]))
												echo $status_id[$row->placement_status_id];
											else
												echo '0';
											?> )
                                        </button>
                                    </li>
									<?php
								}
							?>


                        </ul>
                        <div class="tab-content" id="myTabContent"
                             style="padding:1.5rem;margin-right:0; margin-left:0;   border-top-left-radius:.25rem;  border-top-right-radius:.25rem;border-left: 1px solid #dee2e6;border-right: 1px solid #dee2e6;border-bottom: 1px solid #dee2e6;border-top: 0px;">
							<?php

							$tab_no = 0;
							if ((isset($resultsPlacementStatus)) && (count($resultsPlacementStatus) > 0))
								foreach ($resultsPlacementStatus as $option)
								{
									$tab_no++;
									?>
                                    <div class="tab-pane fade show <?php if ($tab_no == 1) { ?> show active <?php } ?>"
                                         id="status-<?php echo $option->placement_status_id; ?>" role="tabpanel"
                                         aria-labelledby="status-<?php echo $option->placement_status_id; ?>-tab"
                                         style="padding:20px;">

                                        <?php
                                            if ((isset($permission_option->sorting_placements)) && ($permission_option->sorting_placements == 1))
                                            {
                                        ?> 
                                        

                                         <div class="alert alert-info alert-dismissible">
                  
                                          <h5><i class="icon fas fa-exclamation-triangle"></i> How to Sort Placement</h5>
                                          To Sort Placement Order, Drag & Drop the Order by holding <i class="fas fa-align-justify"></i>. Click Save Order when done.
                                        </div>

                                        <?php 
                                            }
                                        ?>    

                                        <!-- -->
                                        <table class="table" id="myTable<?php echo $option->placement_status_id; ?>">
                                            <thead>
                                            <tr>
                                                <th scope="col" width="20">
                                                     <?php
                                                    if ((isset($permission_option->sorting_placements)) && ($permission_option->sorting_placements == 1))
                                                    {
                                                    ?> 
                                                    <input type="button"  id="btnSortPlacement<?php echo $option->placement_status_id; ?>" class="btn btn-sm btn-success" value="Save Order" style="display:none;">

                                                    <?php
                                                    }
                                                    ?>
                                                </th>
                                                <th scope="col">Offer ID</th>
                                                <th scope="col">Placement Group</th>
                                                <th scope="col">Headline</th>
                                                
                                                <th scope="col">Preview</th>
                                                <th scope="col">Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php

											if ($placement_group_id == 0)
											{

												?>
                                                <tr>
                                                    <td scope="col" colspan="7" align="center">You have not selected any Placement Group.  Select a Placement Group & click Search Button from Top Left.
                                                    </td>

                                                </tr>
											<?php
											}
											?>

											<?php
											if (($placement_group_id > 0) && (isset($results)) && (count($results) > 0))
											{
												foreach ($results as $row)
												{
													if ($row->placement_status_id == $option->placement_status_id)
													{
														?>

                                                        <tr class="sortable" data-post-id="<?php
														echo $row->placement_id; ?>">
                                                            <td scope="col"
                                                                style="/*border-left: solid 4px #304d49;background: #a7d4d2; */padding: 5px;/*color: #304d49;*/margin: 0px;cursor: move;">
                                                                <?php
                                                                    if ((isset($permission_option->sorting_placements)) && ($permission_option->sorting_placements == 1))
                                                                    {
                                                                ?> 
                                                                <i class="fas fa-align-justify"></i>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td scope="col"><?php
																echo $row->offer_id; ?></td>
                                                            <td scope="col">
																<?php
																echo $row->placement_group; ?>
																<?php
																//echo '<br/>'.$row->token; ?>
                                                            </td>
                                                            <td scope="col"><?php
																echo $row->headline; ?></td>
                                                            
                                                            <td scope="col">
																<?php
																if ( ! empty($row->main_image))
																{
																	?>
                                                                    <img src="<?php
																	echo base_url(); ?>/uploads/<?php
																	echo $row->main_image; ?>"
                                                                         height="50"/>
																	<?php
																}
																?>
                                                            </td>
                                                            <td scope="col">
                                                                <?php
                                                                if ((isset($permission_option->updating_placements)) && ($permission_option->updating_placements == 1))
                                                                {
                                                                ?> 
                                                                <a class="edit"
                                                                   href="<?php
																   echo base_url(); ?>/placements/update/<?php
																   echo $row->placement_id; ?>"
                                                                   title="Edit" data-toggle="tooltip"><i
                                                                            class="material-icons"
                                                                            style="color:#198754;">&#xE254;</i>
                                                                </a>
                                                                <?php
                                                                }
                                                                ?>                

                                                                <a class="copyto"
                                                                   href="<?php
                                                                   echo base_url(); ?>/placements/copyto/<?php
                                                                   echo $row->placement_id; ?>"
                                                                   title="Copy to Placement Group" data-toggle="tooltip"><i class="material-icons far fa-copy"></i>
                                                               </a>

                                                                <a class="audit"
                                                                   href="<?php
																   echo base_url(); ?>/placements/audit/<?php
																   echo $row->placement_id; ?>"
                                                                   title="Audit" data-toggle="tooltip"> <i
                                                                            class="material-icons"
                                                                            style="color:#198754;">&#xE862;</i>
                                                                </a>

                                                                

                                                                <a class="view" href="<?php echo base_url(); ?>/placements/detail/<?php echo $row->placement_id; ?>" title="View Placements" data-toggle="tooltip"><i class="fas fa-search" style=""></i>
                                                                </a>


                                                                <a class="view" href="<?php echo base_url(); ?>/placements/affiliates/<?php echo $row->placement_id; ?>" title="View Placements" data-toggle="tooltip"><i class="fas fa-user" style="color:#198754;"></i>
                                                                </a>


                                                                <!--a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a-->
                                                            </td>

                                                        </tr>
														<?php
													}
												}
											} else
											{
												if (($placement_group_id > 0) && (isset($results)) && (count($results) <= 0))
												{
													?>
                                                    <tr>
                                                        <td scope="col" colspan="6" align="center">
                                                            No Placement Data Found for the Selected Placement Group.
                                                        </td>

                                                    </tr>
													<?php
												}
											}
											?>

                                            </tbody>
                                        </table>
                                        <!-- -->

                                    </div>

									<?php
								}
							?>
                        </div><!-- /.tab-content -->
                    </div>


                </div>
            </div>
        </div>
    </main>
    <style type="text/css">
        #myTable {
            /*
            padding:15;
            border: 0;
            width: 0;
            margin: 0 0 0 50;
            text-align: left;
            */
        }

        tbody tr {
            /*background: #ddffff;*/
        }

        .dragHandle {
            /*background-image: url("http://akottr.github.io/img/handle.png");*/
            /*background-repeat: repeat-x;*/
            height: 18px;
            margin: 0 1px;
            cursor: move;
        }

        #myTable tr.ui-state-highlight {
            padding: 20px;
            background-color: #eaecec;
            border: 1px dotted #ccc;
            cursor: move;
            margin-top: 12px;
        }
    </style>

    <script type="text/javascript">

        var post_order_ids = [];

        $(function () {
            /*
            $("#myTable")
            .sortable({ items: "tr.sortable" })
            .dragtable({dragHandle: ".dragHandle"})
            .tablesorter();
            */

			<?php

			if ((isset($resultsPlacementStatus)) && (count($resultsPlacementStatus) > 0))
			foreach ($resultsPlacementStatus as $row)
			{

			?>

            $("#myTable<?php echo $row->placement_status_id;?>").sortable({
                items: "tr.sortable",
                placeholder: "ui-state-highlight",


                update: function (event, ui) {

                    $('#myTable<?php echo $row->placement_status_id;?> tr.sortable').each(function () {
                        post_order_ids.push($(this).data("post-id"));
                        console.log("ID: " + $(this).data("post-id"));
                    });

                    if (post_order_ids != undefined || post_order_ids.length > 0) {
                        console.log("Order Changed.");
                        $("#btnSortPlacement<?php echo $row->placement_status_id;?>").show();
                    } else {
                        $("#btnSortPlacement<?php echo $row->placement_status_id;?>").hide();
                    }
                }
            });
			<?php
			}
			?>
        });


		<?php
		if ((isset($resultsPlacementStatus)) && (count($resultsPlacementStatus) > 0))
		foreach ($resultsPlacementStatus as $row)
		{
		?>
        $("#btnSortPlacement<?php echo $row->placement_status_id;?>").click(function () {

			<?php
			if (($placement_group_id > 0) && (isset($results)) && (count($results) > 0))
			{
			?>
            ans = true; // confirm("Sure to Change Placement Order?");
            if (ans == true) {
                $.ajax({
                    //url: frm.attr('action'),
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo base_url();?>/placements/saveorder/<?php echo $placement_group_id;?>",
                    //data: frm.serialize(),
                    data: {post_order_ids: post_order_ids},
                    success: function (response) {
                        console.log('status:' + response.status);
                        if (response.status == "success") {
                            $(".alert-success").show();
                            $(".alert-danger").hide();
                            //alert('Placement Order has been Saved Successfully.');
                            window.location.reload();
                        } else if (response.status == "failure") {
                            $(".alert-success").hide();
                            $(".alert-danger").show();
                            //alert('Ooops!! Placement Order could not be Saved, Sorry. Try Again');

                        }


                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                    },
                });
            }
			<?php
			}
			?>
        });
		<?php
		}
		?>


        function checkFormValidation() {
            var placement_group_id = document.getElementById('placement_group_id').value;
            window.location.href = "<?php echo base_url();?>/placements/" + placement_group_id;
            return false;
        }
    </script>
<?= $this->endSection() ?>
