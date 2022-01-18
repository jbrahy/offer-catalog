<?= $this->extend("app") ?>

<?= $this->section("body") ?>
    
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
                <li><a href="<?php echo $r->homepage;?>" target="_blank"><?php echo $r->brand;?></a></li>
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

<?= $this->endSection() ?>