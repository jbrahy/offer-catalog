<?= $this->extend("app_admin") ?>

<?= $this->section("body") ?>
    <link rel="stylesheet" type="text/css" href="<?php
	echo base_url('assets/css/dragtable.css'); ?>   "/>
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
                    
                </div>
            </div>

            

			<?php
			if (session()->has("success"))
			{
				?>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
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
                    <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                        <div class="alert alert-danger" role="alert">
							<?= session("failure") ?>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>

			
            <div class="row">
               <div class="col-xs-6 col-sm-6 col-md-4 offset-md-2">
               </div>

               <div class="col-xs-6 col-sm-6 col-md-4 offset-md-2">
                    <a href="<?php echo base_url(); ?>/admin/users/new" class="btn btn-primary"><i class="fas fa-user" aria-hidden="true"></i> New User</a>
               </div>
            </div>    
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">

                       

                        <table class="table" id="myTable1">
                            <thead>
                            <tr>
                                <th scope="col" width="20"></th>
                                <th scope="col">ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Username</th>

                                <!--th scope="col">Password</th-->

                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php

                                if ((isset($results_user_list)) && (count($results_user_list) > 0))
                                foreach ($results_user_list as $row)
                                {

                                ?>
                                <tr>
                                    <td width="20"></td>
                                    <td ><?php echo $row->user_id;?></td>
                                    <td ><?php echo $row->first_name;?></td>
                                    <td ><?php echo $row->last_name;?></td>
                                    <td ><?php echo $row->email_address;?></td>
                                    <td ><?php echo $row->username;?></td>
                                    <!--td ><?php echo $row->password;?></td-->
                                    <td>
                                        <a class="edit" href="<?php echo base_url(); ?>/admin/users/edit/<?php echo $row->user_id; ?>" title="Edit" data-toggle="tooltip"><i class="material-icons" style="color:#198754;">&#xE254;</i></a>
                                    </td>
                                </tr>

                                <?php
                                }
                                ?>
                            
                            </tbody>  
                        </table>  


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