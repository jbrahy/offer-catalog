<?= $this->extend("app") ?>

<?= $this->section("body") ?>

        <div class="container">
          <div class="row offer-row">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">

              <div class="text-center">
                <a href="<?php echo base_url();?>" >
                  <img src="<?php echo base_url();?>/uploads/brands/<?php echo $brandData->logo;?>" class="img-fluid offer-image" alt="<?php echo $brandData->brand;?>" />
                </a>
              </div>

              <div class="text-center">
                <a href="<?php echo $brandData->homepage;?>" target="_blank" class="offer-url">
                    <?php echo $brandData->homepage;?>
                </a>
              </div>

              <div class="text-left">
                
                <?php echo $brandData->synopsis;?>
                <p class="offer-description">We currently offer lead generation services for the following verticals:</p>
              </div>

                <?php
                if ((isset($result_offers)) && (count($result_offers)>0))
                {
                ?>
                <div class="row">
                    <div class="col">
                      <ul>
                        <?php
                        $row = 0;
                        foreach($result_offers as $r)
                        {
                            $row++;
                            if ($row %2 != 0)
                            {
                        ?>
                        <li><a href="<?php echo base_url().'/offer-url/'.$r->offer_id;?>" target="_blank"><?php print_r($r->offer);?></a></li>
                        <?php
                            }
                        }
                        ?>
                      </ul>
                    </div><!-- /.col-->
                    <div class="col">
                      <ul>
                        <?php
                        $row = 0;
                        foreach($result_offers as $r)
                        {
                            $row++;
                            if ($row %2 == 0)
                            {
                        ?>
                        <li><a href="<?php echo base_url().'/offer-url/'.$r->offer_id;?>" target="_blank"><?php echo $r->offer;?></a></li>
                        <?php
                            }
                        }
                        ?>
                      </ul>
                    </div><!-- /.col-->
                </div><!-- /.row-->

                <?php
                    }
                ?>


              

                  

        </div> <!-- ./col-xs-12 col-sm-12 col-md-8 offset-md-2 -->
      </div><!-- ./row offer-row -->
    </div><!-- ./container -->

<?= $this->endSection() ?>