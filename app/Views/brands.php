<?= $this->extend("app") ?>

<?= $this->section("body") ?>

        <div class="container">
          <div class="row offer-row">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">

        <?php

            if ((isset($result_brands)) && (count($result_brands) > 0))
            {
              foreach($result_brands as $r)
              {
        ?>
              
              <div class="text-center">
                <a href="<?php echo $r->homepage;?>" >
                  <img src="<?php echo base_url();?>/uploads/brands/<?php echo $r->logo;?>" class="img-fluid offer-image" alt="<?php echo $r->brand;?>" />
                </a>
              </div>

              <div class="text-center">
                <a href="<?php echo $r->homepage;?>" target="_blank" class="offer-url">
                    <?php echo $r->brand;?>
                </a>
              </div>

              <div class="text-left">
                
                <p class="offer-description"><?php echo str_replace('<p>','<p class="offer-description">',$r->synopsis);?></p>

                <?php
                if ((isset($result_offers[$r->brand_id])) && (count($result_offers[$r->brand_id]) > 0))
                {
                ?>
                <p class="offer-description">We currently offer lead generation services for the following verticals:</p>
                <?php
                }else {
                ?>
                <p class="offer-description">No lead generation services.</p>
                <?php
                }
                ?>
              </div>

              <?php

                if ((isset($result_offers[$r->brand_id])) && (count($result_offers[$r->brand_id]) > 0))
                {

              ?>

              <div class="row">
                  <div class="col">
                    <ul>
                      <?php
                        $row = 0;
                        foreach($result_offers[$r->brand_id] as $offers)
                        {
                            $row++;
                            if ($row %2 != 0)
                            {
                        ?>

                        <li>
                            <a data-bs-toggle="collapse" href="#collapseExample_<?php echo $offers->offer_id;?>"> <?php echo $offers->offer;?></a>

                            <?php
                            if ((isset($result_offer_url[$r->brand_id][$offers->offer_id])) && (count($result_offer_url[$r->brand_id][$offers->offer_id]) > 0))
                            {

                          ?>
                            <div class="collapse" id="collapseExample_<?php echo $offers->offer_id;?>">
                             <ul>
                               <?php
                                foreach($result_offer_url[$r->brand_id][$offers->offer_id] as $url)
                                {
                               ?>
                                <li>
                                  <?php echo $url->offer_url_type;?>: 
                                  <a href="<?php echo $url->offer_url;?>" target="_blank"><?php echo $url->offer_url;?></a>
                                </li>
                               <?php
                               }
                               ?>
                                

                              </ul>
                            </div>
                            <?php 
                            }
                            ?>

                        </li>

                        <?php
                            }
                        }
                        ?>
                      


                    </ul>


                  </div> <!-- ./col -->

                  <div class="col">
                    <ul>
                       <?php
                        $row = 0;
                        foreach($result_offers[$r->brand_id] as $offers)
                        {
                            $row++;
                            if ($row %2 == 0)
                            {
                        ?>

                        <li>
                            <a data-bs-toggle="collapse" href="#collapseExample_<?php echo $offers->offer_id;?>"> <?php echo $offers->offer;?></a>

                          <?php
                            if ((isset($result_offer_url[$r->brand_id][$offers->offer_id])) && (count($result_offer_url[$r->brand_id][$offers->offer_id]) > 0))
                            {

                          ?>
                            <div class="collapse" id="collapseExample_<?php echo $offers->offer_id;?>">
                             <ul>
                               <?php
                                foreach($result_offer_url[$r->brand_id][$offers->offer_id] as $url)
                                {
                               ?>
                                <li>
                                  <?php echo $url->offer_url_type;?>: 
                                  <a href="<?php echo $url->offer_url;?>" target="_blank"><?php echo $url->offer_url;?></a>
                                </li>
                               <?php
                               }
                               ?>
                                

                              </ul>
                            </div>
                            <?php 
                            }
                            ?>


                        </li>

                        <?php
                            }
                        }
                        ?>
                       
                    </ul>
                  </div> <!-- /.col -->
               </div>

              
                <?php
                    }
                ?>

    
        <?php
              }
            }
        ?>

         

        </div> <!-- ./col-xs-12 col-sm-12 col-md-8 offset-md-2 -->
      </div><!-- ./row offer-row -->
    </div><!-- ./container -->

<?= $this->endSection() ?>