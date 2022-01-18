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
                <a href="https://consumer-coalition.com" target="_blank" class="offer-url">Consumer-Coalition.com</a>
              </div>
              <div class="text-left">
                <p class="offer-description">Keeping you in the know about products, medications, and medical devices to help you make informed decisions to potentially get compensation for your injuries.</p>
                <p class="offer-description">We currently offer lead generation services for the following verticals:</p>
              </div>
    
        <?php

            if ((isset($result_brands)) && (count($result_brands)>0))
            {
        ?>
        <div class="row">
            <div class="col">
              <ul>
                <?php
                $row = 0;
                foreach($result_brands as $r)
                {
                    $row++;
                    if ($row %2 != 0)
                    {
                ?>
                <li><a href="<?php /*echo $r->homepage;*/ echo base_url().'/offers/'.$r->brand_id;?>" target="_blank"><?php echo $r->brand;?></a></li>
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
                foreach($result_brands as $r)
                {
                    $row++;
                    if ($row %2 == 0)
                    {
                ?>
                <li><a href="<?php echo $r->homepage;?>" target="_blank"><?php echo $r->brand;?></a></li>
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