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


                    <form role="form" name="newBrandForm" id="newBrandForm"
                          action="<?php
						  echo base_url(); ?>/admin/brands/save-new-brand" method="post"
                          onsubmit="return checkFormValidation();" enctype="multipart/form-data">


                    

                        <div class="form-group">
                            <label for="brand_name">Brand Name:</label>
                            <input type="text" class="form-control" id="brand_name" name="brand_name">
                        </div>

                        <div class="form-group">
                            <label for="brand_homepage">Brand Homepage URL:</label>
                            <input type="text" class="form-control" id="brand_homepage" name="brand_homepage">
                        </div>

                        

                        <div class="form-group">
                            <label for="brand_synopsis">Synopsis:</label>
                            <textarea class="ckeditor form-control" id="editor1" name="brand_synopsis"  rows="5" required ></textarea>
                        </div>

                        <div class="form-group">
                            <label for="main_image">Logo:</label>
                            <input type="file" class="form-control" id="brand_logo" name="brand_logo" required>
                        </div>

                      

                        

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                                <div class="form-group" style="margin-top: 2em;">
                                    <input type="submit" class="btn btn-success" value="Submit">
                                    
                                    <a href="<?php echo base_url();?>/admin/brands/" class="btn btn-primary">Back</a>
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



            
        });

       
        function checkFormValidation() {
            console.log("Loaded new placement on submit");
            var form = document.getElementById('newBrandForm');


            var frm = $('#newBrandForm');


            


            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>