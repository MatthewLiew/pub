                <?php if($_SESSION['login_user_role']=='1'){ ?>
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
					<?php if (basename($_SERVER['PHP_SELF']) == 'index.php') { ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard" style="background-color: #367fa9;">
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-fw fa-dashboard"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-fw fa-dashboard"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'order_manage.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard" style="background-color: #367fa9;">
                        <a class="nav-link" href="order_manage.php">
                            <i class="fa fa-fw fa-shopping-cart "></i>
                            <span class="nav-link-text">Order Management</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="order_manage.php">
                            <i class="fa fa-fw fa-shopping-cart "></i>
                            <span class="nav-link-text">Order Management</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'user_manage.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage" style="background-color: #367fa9;">
                        <a class="nav-link" href="user_manage.php">
                            <i class="fa fa-fw fa-users"></i>
                            <span class="nav-link-text">User Management</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage">
                        <a class="nav-link" href="user_manage.php">
                            <i class="fa fa-fw fa-users"></i>
                            <span class="nav-link-text">User Management</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'cctv_view.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="CCTV View" style="background-color: #367fa9;">
                        <a class="nav-link" href="cctv_view.php">
                            <i class="fa fa-fw fa-film"></i>
                            <span class="nav-link-text">CCTV View</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="CCTV View">
                        <a class="nav-link" href="cctv_view.php">
                            <i class="fa fa-fw fa-film"></i>
                            <span class="nav-link-text">CCTV View</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'system_log.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="System Log" style="background-color: #367fa9;">
                        <a class="nav-link" href="system_log.php">
                            <i class="fa fa-fw fa-list-ol"></i>
                            <span class="nav-link-text">System Log</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="System Log">
                        <a class="nav-link" href="system_log.php">
                            <i class="fa fa-fw fa-list-ol"></i>
                            <span class="nav-link-text">System Log</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'transaction_log.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Facility Log" style="background-color: #367fa9;">
                        <a class="nav-link" href="transaction_log.php">
                            <i class="fa fa-fw fa-bars"></i>
                            <span class="nav-link-text">Facility Log</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Facility Log">
                        <a class="nav-link" href="transaction_log.php">
                            <i class="fa fa-fw fa-bars"></i>
                            <span class="nav-link-text">Facility Log</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'error_log.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Error Log" style="background-color: #367fa9;">
                        <a class="nav-link" href="error_log.php">
                            <i class="fa fa-fw fa-exclamation-circle"></i>
                            <span class="nav-link-text">Error Log</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Error Log">
                        <a class="nav-link" href="error_log.php">
                            <i class="fa fa-fw fa-exclamation-circle"></i>
                            <span class="nav-link-text">Error Log</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'report.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage" style="background-color: #367fa9;">
                        <a class="nav-link" href="report.php">
                            <i class="fa fa-fw fa-list-ol"></i>
                            <span class="nav-link-text">Report</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage">
                        <a class="nav-link" href="report.php">
                            <i class="fa fa-fw fa-list-ol"></i>
                            <span class="nav-link-text">Report</span>
                        </a>
                    </li>
					<?php } ?>
                </ul>
                <?php } else { ?>
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
					<?php if (basename($_SERVER['PHP_SELF']) == 'user_profile.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage" style="background-color: #367fa9;">
                        <a class="nav-link" href="user_profile.php">
                            <i class="fa fa-fw fa-users"></i>
                            <span class="nav-link-text">User Profile</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="User Manage" >
                        <a class="nav-link" href="user_profile.php">
                            <i class="fa fa-fw fa-users"></i>
                            <span class="nav-link-text">User Profile</span>
                        </a>
                    </li>
					<?php } ?>
					<?php if (basename($_SERVER['PHP_SELF']) == 'order_history.php') { ?>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard" style="background-color: #367fa9;">
                        <a class="nav-link" href="order_history.php">
                            <i class="fa fa-fw fa-shopping-cart "></i>
                            <span class="nav-link-text">Order History</span>
                        </a>
                    </li>
					<?php }else{ ?>
					<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="order_history.php">
                            <i class="fa fa-fw fa-shopping-cart "></i>
                            <span class="nav-link-text">Order History</span>
                        </a>
                    </li>
					<?php } ?>
                </ul>
                <?php } ?>
				<!--
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                        <a class="nav-link text-center" id="sidenavToggler">
                            <i class="fa fa-fw fa-angle-left"></i>
                        </a>
                    </li>
                </ul>
				-->
