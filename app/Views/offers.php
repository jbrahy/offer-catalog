<?= $this->extend("app") ?>

<?= $this->section("body") ?>

        <div class="container">
          <div class="row offer-row">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
              <div class="text-center">
                <a href="<?php echo base_url();?>" >
                  <img src="<?php echo base_url();?>/assets/img/consumer-coalition-logo.png" class="img-fluid offer-image" alt="Consumer Coalition" />
                </a>
              </div>
              <div class="text-center">
                <a href="<?php echo $brandData->homepage;?>" target="_blank" class="offer-url"><?php echo $brandData->brand;?></a>
              </div>
              <div class="text-left">
                <p class="offer-description"><?php echo $brandData->synopsis;?></p>
              </div>
    
        <?php

            if ((isset($result_offers)) && (count($result_offers)>0))
            {
        ?>
        <div class="row">
            <div class="col">

                    <table class="table">
                        <thead>
                            <th>ID</th>
                            <th>Offer QR</th>
                            <th>Offer Name</th>
                            <th>Offer URL</th>
                        <thead>
                       
                        <tbody>
                        <?php
                            $row = 0;
                            foreach($result_offers as $r)
                            {
                                $row++;
                                
                        ?>
                           
                           
                            <tr style="border: 0px solid #ced4da;">
                                <td style="border: 0px solid #ced4da;">
                                   
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   <a href="" target="_blank"><?php echo $r->offer;?></a>
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   
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

        </div> <!-- ./col-xs-12 col-sm-12 col-md-8 offset-md-2 -->
      </div><!-- ./row offer-row -->
    </div><!-- ./container -->

<?= $this->endSection() ?>