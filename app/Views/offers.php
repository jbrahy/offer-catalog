<?= $this->extend("app") ?>

<?= $this->section("body") ?>

        <div class="container">
          <div class="row offer-row">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
              <div class="text-center">
                <a href="<?php echo base_url();?>" >
                  <img src="<?php echo base_url();?>/uploads/brands/<?php echo $result_offer->logo;?>" class="img-fluid offer-image" alt="<?php echo $result_offer->brand;?>" />
                </a>
              </div>

              <div class="text-center">
                <a href="<?php echo $result_offer->homepage;?>" target="_blank" class="offer-url">
                    <h1>Brand: <?php echo $result_offer->brand;?></h1>
                </a>
              </div>

              <div class="text-center">
                
                    <h3>Offer: <?php echo $result_offer->offer;?></h3>
                
              </div>

        </div> <!-- ./col-xs-12 col-sm-12 col-md-8 offset-md-2 -->
      </div><!-- ./row offer-row -->  

             
    
        <?php

            if ((isset($result_offer_url)) && (count($result_offer_url)>0))
            {
        ?>
        <div class="row">
            <div class="col">

                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>QR</th>
                            <th>Offer URL</th>
                            <th>URL Type</th>
                            <th>Created On</th>
                        <thead>
                       
                        <tbody>
                        <?php
                            $row = 0;
                            foreach($result_offer_url as $r)
                            {
                                $row++;
                                
                        ?>
                           
                           
                            <tr style="">
                                <td style="border: 0px solid #ced4da;">
                                   <?php echo $r->offer_url_id;?>
                                </td>
                                <td style="border: 0px solid #ced4da; vertical-align: top;">
                                   <img  src="<?php echo base_url();?>/uploads/qr/qr-<?php echo $r->offer_url_id;?>.png" width="75" class="img-fluid" alt="" />
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   <a href="<?php echo $r->offer_url;?>" target="_blank"><?php echo $r->offer_url;?></a>
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   <?php echo $r->offer_url_type;?>
                                </td>
                                
                                <td style="border: 0px solid #ced4da;">
                                   <?php echo $r->created_at;?>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>

                        </tbody>            

            </div><!-- /.col-->
            
        </div><!-- /.row-->

        <?php
            }
        ?>

      
    </div><!-- ./container -->

<?= $this->endSection() ?>