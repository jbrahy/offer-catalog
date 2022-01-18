<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php
		echo base_url(); ?>">Admin</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link <?php
					if ((isset($active_menu)) && ($active_menu == "verticals"))
					{ ?>active <?php
					} ?>"
                       href="<?php
					   echo base_url(); ?>/verticals/">Verticals</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php
					if ((isset($active_menu)) && ($active_menu == "placements"))
					{ ?>active <?php
					} ?>"
                       href="<?php
					   echo base_url(); ?>/placements/">Placements</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php
					if ((isset($active_menu)) && ($active_menu == "reporting"))
					{ ?>active <?php
					} ?>"
                       href="<?php
					   echo base_url(); ?>/reporting/">Reporting</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " href="<?php
					echo base_url(); ?>/logout">Logout</a>
                </li>

            </ul>
        </div>
    </div>
</nav>