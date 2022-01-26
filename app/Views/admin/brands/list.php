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
                <div class="col"> 
                </div> 
                <div class="col" style="text-align:right;"> 
                        <a href="<?php echo base_url(); ?>/admin/brands/add-new" class="btn btn-success"><i class="fas fa-plus"></i> New Brand</a>
                </div> 
            </div>      

              
            <div class="row ">
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2"> 

                        <!-- -->
                        <table class="table" id="myTableBrand">
                            <thead>
                            <tr>
                                <th scope="col" width="20">
                                   
                                </th>
                                <th scope="col">ID</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Option</th>
                            </tr>
                            </thead>
                            <tbody>

                              <?php

                                if ((isset($result_brands)) && (count($result_brands) > 0))
                                {
                                    foreach($result_brands as $b)  
                                    {
                              ?>
                            <tr class="sortable" data-post-id="<?php
                                                        echo $b->brand_id; ?>">
                                
                                <td scope="col" style="/*border-left: solid 4px #304d49;background: #a7d4d2; */padding: 5px;/*color: #304d49;*/margin: 0px;cursor: move;">
                                     <i class="fas fa-align-justify"></i>
                                </td>
                                <td scope="col"><?php echo $b->brand_id;?></td>
                                <td scope="col">
                                    <?php
                                    if ((!empty($b->logo)) && (file_exists('./uploads/brands/' . $b->logo)))
                                    {
                                    ?>
                                    <img src="<?php echo base_url();?>/uploads/brands/<?php echo $b->logo;?>" class="img-fluid " style="height: 50px;" alt="<?php echo $b->brand;?>" />
                                    <?php 
                                    }
                                    ?>
                                </td>
                                <td scope="col"><?php echo $b->brand;?></td>
                                <td scope="col">
                                    
                                    <a class="edit"
                                                   href="<?php
                                                   echo base_url(); ?>/admin/brands/edit/<?php echo $b->brand_id;?>"
                                                   title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
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
            </div>
               
        </div>
    </main>

    <style type="text/css">
        #myTableBrand {
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

        #myTableBrand tr.ui-state-highlight {
            padding: 20px;
            background-color: #eaecec;
            border: 1px dotted #ccc;
            cursor: move;
            margin-top: 12px;
        }
    </style>

   
    <script type="text/javascript">

        var post_order_ids = [];

        function is_url(str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }

        $(document).ready(function () {

            $("#myTableBrand").sortable({
                items: "tr.sortable",
                placeholder: "ui-state-highlight",


                update: function (event, ui) {

                    $('#myTableBrand tr.sortable').each(function () {
                        post_order_ids.push($(this).data("post-id"));
                        console.log("ID: " + $(this).data("post-id"));
                    });

                    /*
                    if (post_order_ids != undefined || post_order_ids.length > 0) {
                        console.log("Order Changed.");
                        $("#btnSortPlacement<?php //echo $row->placement_status_id;?>").show();
                    } else {
                        $("#btnSortPlacement<?php //echo $row->placement_status_id;?>").hide();
                    }
                    */
                }
            });
            
        });

       
        function checkFormValidation() {
            


            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>