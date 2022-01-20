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
                <a href="<?php echo $brandData->homepage;?>" target="_blank" class="offer-url">
                    <?php echo $brandData->brand;?>
                </a>
              </div>

              <div class="text-center">
                


                        <div class="row">
                            <div class="col">

                                <table class="table">

                                    <thead>
                                        
                                        <th style="text-align: left">Logo</th>
                                        <th>Synopsis</th>
                                        
                                    <thead>
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left">
                                                <a href="<?php echo base_url();?>" >
                                                  <img src="<?php echo base_url();?>/uploads/3m-military-earplug.jpeg" width="150" class="img-fluid" alt="<?php echo $brandData->brand;?>" />
                                                </a>
                                            </td>
                                            <td style="vertical-align: top">
                                                <?php echo $brandData->synopsis;?>
                                            </td>
                                        </tr>    
                                    </tbody>
                                </thead>
                            </thead>
                        </table>                

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
                            <th>Created On</th>
                        <thead>
                       
                        <tbody>
                        <?php
                            $row = 0;
                            foreach($result_offers as $r)
                            {
                                $row++;
                                
                        ?>
                           
                           
                            <tr style="">
                                <td style="border: 0px solid #ced4da;">
                                   <?php echo $r->offer_id;?>
                                </td>
                                <td style="border: 0px solid #ced4da; vertical-align: top;">
                                   <img  src="<?php echo base_url();?>/uploads/qr/qr-<?php echo $r->offer_id;?>.png" width="75" class="img-fluid" alt="" />
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   <a href="" target="_blank"><?php echo $r->offer;?></a>
                                </td>
                                <td style="border: 0px solid #ced4da;">
                                   <a href="<?php echo $r->offer_url;?>" target="_blank"><?php echo $r->offer_url;?></a>
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

        </div> <!-- ./col-xs-12 col-sm-12 col-md-8 offset-md-2 -->
      </div><!-- ./row offer-row -->
    </div><!-- ./container -->

<?= $this->endSection() ?>