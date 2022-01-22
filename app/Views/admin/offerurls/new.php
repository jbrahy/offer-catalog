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
                <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">       


                    <form role="form" name="newOfferUrlForm" id="newOfferUrlForm"
                          action="<?php
						  echo base_url(); ?>/admin/offerurls/save-new" method="post"
                          onsubmit="return checkFormValidation();" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="brand_id">Brand:</label>
                            <select class="form-select" id="brand_id" name="brand_id" required>
                                <option value="">-- Select Brand --</option>
                                <?php 
                                    if (isset($result_brands))
                                    foreach($result_brands as $b)
                                    {
                                ?>
                                <option value="<?php echo $b->brand_id;?>" <?php if ( $offer_detail->brand_id == $b->brand_id ) {?>selected="selected" <?php } ?>>
                                    <?php echo $b->brand;?>
                                    
                                </option>
                                <?php 
                                    }
                                ?> 
                                
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="offer_id">Offer:</label>
                            <select class="form-select" id="offer_id" name="offer_id" required>
                                <option value="">-- Select Offer --</option>
                                <?php 
                                    if (isset($result_offers))
                                    foreach($result_offers as $o)
                                    {
                                ?>
                                <option value="<?php echo $o->offer_id;?>" <?php if ( $offer_detail->offer_id == $o->offer_id ) {?>selected="selected" <?php } ?>>
                                    <?php echo $o->offer;?>
                                    
                                </option>
                                <?php 
                                    }
                                ?> 
                                
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="offer_url">Offer URL:</label>
                            <input type="text" class="form-control" id="offer_url" name="offer_url">
                        </div>

                        

                        <div class="form-group">
                            <label for="brand_id">URL Type:</label>
                            <select class="form-select" id="brand_id" name="brand_id" required>
                                <option value="">-- Select Type --</option>
                                <?php 
                                    if (isset($result_offer_url_types))
                                    foreach($result_offer_url_types as $url_type)
                                    {
                                ?>
                                <option value="<?php echo $url_type->offer_url_type_id;?>"><?php echo $url_type->offer_url_type;?></option>
                                <?php 
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

            
        });

       
        function checkFormValidation() {
            var form = document.getElementById('newOfferUrlForm');

            ans = true; // confirm("Sure to Add New Placement?");
            return ans;

        }

    </script>
<?= $this->endSection() ?>