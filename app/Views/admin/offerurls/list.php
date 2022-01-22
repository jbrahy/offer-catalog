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
                        <table class="table" id="myTable">
                            <thead>
                            <tr>
                                <th scope="col" width="20">
                                   
                                </th>
                                <th scope="col">ID</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Homepage</th>
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
                            <tr>
                                <td scope="col" width="20">
                                   
                                </td>
                                <td scope="col"><?php echo $b->brand_id;?></td>
                                <td scope="col">
                                    <img src="<?php echo base_url();?>/uploads/brands/<?php echo $b->logo;?>" class="img-fluid " height="60" alt="<?php echo $b->brand;?>" />
                                </td>
                                <td scope="col"><?php echo $b->brand;?></td>
                                <td scope="col"><?php echo $b->homepage;?></td>
                                <td scope="col">
                                    
                                    <a class="edit"
                                                   href="<?php
                                                   echo base_url(); ?>/admin/brands/update/<?php echo $b->brand_id;?>"
                                                   title="Edit" data-toggle="tooltip"><i
                                                            class="material-icons"
                                                            style="color:#198754;">&#xE254;</i>
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


   
    <script type="text/javascript">

        function is_url(str) {
            regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
            return regexp.test(str);
        }

        $(document).ready(function () {

            
            
        });

       
        function checkFormValidation() {
            


            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>