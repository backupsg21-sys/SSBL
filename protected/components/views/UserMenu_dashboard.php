<?php
$loginUserId = Yii::app()->user->getId();
if (!isset(Yii::app()->session['uriNames']) || count(Yii::app()->session['uriNames']) == 0) {
    $uriNames = array();
} else {
    $uriNames = Yii::app()->session['uriNames'];
}
?>

<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">VMS</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ACME</b> VMS</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                        <?php echo CHtml::link('<i class="fa fa-link"></i> <span>Notification</span>', array('/site/dashboard')); ?>

                   </li>
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">0</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="header">You have 0 messages</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <!-- User Image -->
                                            <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                                        </div>
                                        <!-- Message title and timestamp -->
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <!-- The message -->
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                                <!-- end message -->
                            </ul>
                            <!-- /.menu -->
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
                <!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 0 notifications</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
                                <li><!-- start notification -->
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 0 new members joined today
                                    </a>
                                </li>
                                <!-- end notification -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="/" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>

                        <span class="label label-danger">0</span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="header">You have 0 tasks</li>
                        <li>
                            <!-- Inner menu: contains the tasks -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <!-- Task title and progress text -->
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <!-- The progress bar -->
                                        <div class="progress xs">
                                            <!-- Change the css width attribute to simulate progress -->
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                 role="progressbar"
                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <!-- <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo Users::model()->fullNameOfThis(Yii::app()->user->getId()) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <!-- <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/user2-160x160.jpg" class="img-circle" alt="User Image"> -->

                            <p>
                                <?php echo Users::model()->fullNameOfThis(Yii::app()->user->getId()) ?>
                                <!--                                <small>Member since Nov. 2012</small>-->
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!--              <li class="user-body">
                                        <div class="row">
                                          <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                          </div>
                                          <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                          </div>
                                          <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                          </div>
                                        </div>
                                         /.row 
                                      </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <!--                            <div class="pull-left">
                                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                                        </div>-->
                            <div class="pull-right">
                                <?php echo CHtml::link('Logout', array('/site/logout'), array('class' => 'btn btn-default btn-flat')); ?>
                                <!--<a href="#" class="btn btn-default btn-flat">Sign out</a>-->
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!--                <li>-->
                <!--                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
                <!--                </li>-->
                <li class="dropdown user user-menu">

                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- header menu End -->


<!-- sidebar: Menu Start -->
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo ucwords(Users::model()->fullNameOfThis(Yii::app()->user->getId())) ?></p>
                <!-- Status -->
                <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
        </div>

        <!-- search form (Optional) -->
        <!--       <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                      </button>
                    </span>
                </div>
              </form> -->
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">ACME VMS</li>
            <li class="active"><?php echo CHtml::link('<i class="fa fa-link"></i> <span>Dashboard</span>', array('/site/dashboard')); ?></li>
            <?php
            $AdditionalLink[] = array('Company Informations', 'yourCompany/admin');
            $AdditionalLink[] = array('Voucher Prefix', 'voucherNoFormateac/admin');
            $AdditionalLink[] = array('Manage Users', 'users/admin');
            $AdditionalLink[] = array('Manage Permissions', 'rights');
            $AdditionalLink[] = array('Manage User Roll', 'usersRoll/admin');
            $AdditionalLink[] = array('Manage Roll Menu', 'usersRollMenu/admin');
            $AdditionalLink[] = array('Add Employee', 'employees/create');
            $AdditionalLink[] = array('Manage Employees', 'employees/admin');
            $SoftConfigureLink[] = array('Additional', 'menu_item_config', $AdditionalLink);

            $sublink = '';
            ?>
            <!--<li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>-->
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Software Configure</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php
                    foreach ($AdditionalLink as $oneRow) {
                        $lable = $oneRow[0];
                        $uri_name = $oneRow[1];
                        if ($loginUserId == 1 || in_array($uri_name, $uriNames)) {


                            echo $sublink = '<li><span class="menu_item_config"></span>' . CHtml::link($lable, array('/' . $uri_name)) . '</li>';
                        }
                    }
                    ?>
                    <!--                    <li><a href="#">Link in level 2</a></li>
                                        <li><a href="#">Link in level 2</a></li>-->
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Vehicle Config</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><?php echo CHtml::link('Vehicle Brand', array('/vehicleBrand/admin')); ?></li>
                    <li><?php echo CHtml::link('Vehicle Model', array('/vehicleModel/admin')); ?></li>
                    <li><?php echo CHtml::link('Vehicle Type', array('/vehicleType/admin')); ?></li>
                    <li><?php echo CHtml::link('Division', array('/unitType/admin')); ?></li>
                    <li><?php echo CHtml::link('Department', array('/distributionUnitName/admin')); ?></li>
                    <li><?php echo CHtml::link('Sub Department', array('/subSubUnit/admin')); ?></li>
                    <li><?php echo CHtml::link('Cost Center', array('/unitName/admin')); ?></li>
                    <li><?php echo CHtml::link('Route Location', array('/routeLocation/admin')); ?></li>
                    <li><?php echo CHtml::link('Route Planning Entry', array('/routeLocation/adminPlanning')); ?></li>
                    <li><?php echo CHtml::link('Manage Vehicle Assign', array('/routePlaning/admin')); ?></li>
                    <li><?php echo CHtml::link('Add Passanger In Vehicle', array('/routePlaning/adminPassanger')); ?></li>
                </ul>
            </li>


            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Vehicle Inventory</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Vehicle Inventory</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Vehicle Details', array('vehicleInfo/admin')); ?></li>
                            <li><?php echo CHtml::link('Manual Vehicle Entry', array('vehicleInfo/createVehicleInfo')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Purchase & Receive</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Vehicle Entry from File', array('vehicleInfo/uploadFileData')); ?></li>
                            <li><?php echo CHtml::link('Purchase', array('purchaseItems/purchaseForm')); ?></li>
                            <li><?php echo CHtml::link('Received Purchase', array('purchaseItemVehicle/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Vehicle Report</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Inventory Reprot', array('vehicleInfo/vehicleInventoryReprot')); ?></li>
                            <li><?php echo CHtml::link('Undistributed Inv. Reprot', array('vehicleInfo/undistributedVehicleInventoryReprot')); ?></li>
                            <li><?php echo CHtml::link('Division Wise Report', array('vehicleInfo/vehicleDistributionReprot')); ?></li>
                            <li><?php echo CHtml::link('Department wise Report', array('vehicleInfo/rangeWiseVehicleDistributionReprot')); ?></li>
                            <li><?php echo CHtml::link('Unit Wise Report', array('vehicleInfo/UnitWiseVehicleReprot')); ?></li>
                            <li><?php echo CHtml::link('Detail Report', array('vehicleInfo/vehicleDetailReprot')); ?></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Pool Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Pick & Drop</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <!-- <li><?php CHtml::link('Route Location', array('routeLocation/admin')); ?></li>     -->
                            <li><?php echo CHtml::link('Add Vehicle To Route', array('routeLocation/adminPlanning')); ?></li>
                            <li><?php echo CHtml::link('Manage Vehicle To Route', array('routePlaning/admin')); ?></li>
                            <li><?php echo CHtml::link('Add Passanger In Vehicle', array('routePlaning/adminPassanger')); ?></li>
                            <li><?php echo CHtml::link('Manage Passanger', array('vehiclePassenger/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Usage Requisition</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Create Requisition', array('usageRequisition/usageRequisitionCreate')); ?></li>
                            <li><?php echo CHtml::link('Manage Requisition', array('usageRequisition/AdminUsageRequisition')); ?></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Purchase & Receive</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><?php echo CHtml::link('Vehicle Entry from File', array('/vehicleInfo/uploadFileData')); ?></li>
                    <li><?php echo CHtml::link('Purchase', array('/purchaseItems/purchaseForm')); ?></li>
                    <li><?php echo CHtml::link('Received Purchase', array('/purchaseItemVehicle/admin')); ?></li>
                </ul>
            </li>
            <!--            <li class="treeview">-->
            <!--                <a href="#"><i class="fa fa-link"></i> <span>Vehicle Inventory</span>-->
            <!--                    <span class="pull-right-container">-->
            <!--                        <i class="fa fa-angle-left pull-right"></i>-->
            <!--                    </span>-->
            <!--                </a>-->
            <!--                <ul class="treeview-menu">-->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Vehicle Inventory</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Vehicle Details', array('vehicleInfo/admin')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Manual Vehicle Entry', array('vehicleInfo/createVehicleInfo')); ?><!--</li>                          -->
            <!--                        </ul>-->
            <!--                    </li> -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Purchase & Receivet</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Vehicle Entry from File', array('vehicleInfo/uploadFileData')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Purchase', array('purchaseItems/purchaseForm')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Received Purchase', array('purchaseItemVehicle/admin')); ?><!--</li>                          -->
            <!--                        </ul>-->
            <!--                    </li> -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Vehicle Report</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Inventory Reprot', array('vehicleInfo/vehicleInventoryReprot')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Undistributed Inv. Reprot', array('vehicleInfo/undistributedVehicleInventoryReprot')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Division Wise Report', array('vehicleInfo/vehicleDistributionReprot')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Department wise Report', array('vehicleInfo/rangeWiseVehicleDistributionReprot')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Unit Wise Report', array('vehicleInfo/UnitWiseVehicleReprot')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Detail Report', array('vehicleInfo/vehicleDetailReprot')); ?><!--</li>                          -->
            <!--                        </ul>-->
            <!--                    </li> -->
            <!--                </ul>-->
            <!--            </li>                                       -->
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Vehicle Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Usage Requisition</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Create Requisition', array('usageRequisition/usageRequisitionCreate')); ?></li>
                            <li><?php echo CHtml::link('Manage Requisition', array('usageRequisition/AdminUsageRequisition')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Distribution</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Distribution / Transfer', array('vTransactionHistory/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Document Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Document Entry', array('vehicleDocuments/admin')); ?></li>
                            <li><?php echo CHtml::link('Yearly Document Renew', array('vehicleDocumentsRenew/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Driver Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Driver Profile Setup', array('driverInfo/createDriverProfile')); ?></li>
                            <li><?php echo CHtml::link('Manage Driver', array('driverInfo/admin')); ?></li>
                            <li><?php echo CHtml::link('Asign Driver', array('assignDriver/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Fuel Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Issue Fuel', array('issueFuel/createIssueFuel')); ?></li>
                            <li><?php echo CHtml::link('Manage Issue Fuel', array('issueFuel/admin')); ?></li>
                            <li><?php echo CHtml::link('Fuel Consumption Record', array('')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Accident Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Vehicle Accident', array('vehicleAccident/createVehicleAccident')); ?></li>
                            <li><?php echo CHtml::link('Manage Vehicle Accident', array('vehicleAccident/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>VMS Reports</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Report', array('')); ?></li>
                            <li><?php echo CHtml::link('Vehicle Dashboard', array('vehicleDashboard/admin')); ?></li>
                            <li><?php echo CHtml::link('Fuel Report', array('IssueFuel/fuelReport')); ?></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Workshop Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Configure</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Priorities', array('priorities/admin')); ?></li>
                            <li><?php echo CHtml::link('Complain Heads', array('complainHeads/admin')); ?></li>
                            <li><?php echo CHtml::link('Complain Categories', array('complainCat/admin')); ?></li>
                            <li><?php echo CHtml::link('Complain Sub-Cat', array('complainSubCat/admin')); ?></li>
                            <li><?php echo CHtml::link('Work Details', array('complainWorkDetails/admin')); ?></li>
                            <li><?php echo CHtml::link('Sublet Works', array('ComplainWorkDetails3rd/admin')); ?></li>
                            <li><?php echo CHtml::link('Store & Inventory', array('')); ?></li>
                            <li><?php echo CHtml::link('Workshop Setup', array('workshopInfo/createWorkshopSetup')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Complain</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Work Requisition', array('complainSheet/createComplainSheet')); ?></li>
                            <li><?php echo CHtml::link('Vehicle Manage', array('complainSheet/admin')); ?></li>
                            <li><?php echo CHtml::link('Add More Complains ', array('vehicleComplain/admin')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Estimate</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Create Manual Estimate', array('estimate/manualEstimateCreate')); ?></li>
                            <li><?php echo CHtml::link('Edit Manual Estimate', array('estimate/adminManualEstimate')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Bill</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Create Manual Bill', array('csBillProd/manualBillCreate')); ?></li>
                            <li><?php echo CHtml::link('Edit Manual Bill', array('csBillProd/adminManualBill')); ?></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-link"></i> <span>Reports</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link('Work Status', array('complainHeads/workStetus')); ?></li>
                            <li><?php echo CHtml::link('Receive Report', array('complainHeads/receiveReport')); ?></li>
                        </ul>
                    </li>
                </ul>
            </li>


            <!--            <li class="treeview">-->
            <!--                <a href="#"><i class="fa fa-link"></i> <span>Inventory</span>-->
            <!--                    <span class="pull-right-container">-->
            <!--                        <i class="fa fa-angle-left pull-right"></i>-->
            <!--                    </span>-->
            <!--                </a>-->
            <!--                <ul class="treeview-menu">-->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Configure</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Product Quality', array('prodItems/admin')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Product Types', array('prodBrands/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Add Products', array('prodModels/create')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Manage Products', array('prodModels/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Product Brands', array('pBrand/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Vehicle Brands', array('customField1/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Supported Models', array('customField2/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Year Models', array('yearModels/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Color & Sizes', array('criteriaValues/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Unit', array('units/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Suppliers', array('suppliers/admin')); ?><!--</li>                          -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Stores', array('stores/admin')); ?><!--</li>                          -->
            <!--                        </ul>-->
            <!--                    </li>               -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Purchase</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Requisition', array('purchaseItems/purchaseForm')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Receive', array('receivedPurchase/adminReceive')); ?><!--</li>                                                                       -->
            <!--                        </ul>-->
            <!--                    </li>  -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Store Requisition</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Issue Parts For Vehicle', array('complainSheet/adminStoreReq')); ?><!--</li>-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Return Parts For Vehicle', array('storeReq/admin')); ?><!--</li>                                                                       -->
            <!--                        </ul>-->
            <!--                    </li> -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Inventory</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Inventory Stock', array('inventory/admin')); ?><!--</li>                                                                                               -->
            <!--                        </ul>-->
            <!--                    </li>  -->
            <!--                    <li class="treeview">-->
            <!--                        <a href="#"><i class="fa fa-link"></i> <span>Reports</span>-->
            <!--                            <span class="pull-right-container">-->
            <!--                                <i class="fa fa-angle-left pull-right"></i>-->
            <!--                            </span>-->
            <!--                        </a>-->
            <!--                        <ul class="treeview-menu">-->
            <!--                            <li>-->
            <?php //echo CHtml::link('Supplier (Ledger)', array('suppliers/partyLedgerDateWise')); ?><!--</li>                                                                                               -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Stock (Qty)', array('')); ?><!--</li>                                                                                               -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Stock (Amounts)', array('inventory/stockReportInAmount')); ?><!--</li>                                                                                               -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Purchase', array('purchaseItems/purchaseReport')); ?><!--</li>                                                                                               -->
            <!--                            <li>-->
            <?php //echo CHtml::link('Issue Report', array('storeReq/report')); ?><!--</li>                                                                                               -->
            <!--                        </ul>-->
            <!--                    </li> -->
            <!--                </ul>-->
            <!--            </li>-->
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>


<!--<ul id="nav" class="dropdown dropdown-linear dropdown-columnar">

    <li><?php echo CHtml::link('Dashboard', array('/site/dashboard')); ?></li>
<?php
$AdditionalLink[] = array('Company Informations', 'yourCompany/admin');
$AdditionalLink[] = array('Voucher Prefix', 'voucherNoFormateac/admin');
$AdditionalLink[] = array('Manage Users', 'users/admin');
$AdditionalLink[] = array('Manage Permissions', 'rights');
$AdditionalLink[] = array('Manage User Roll', 'usersRoll/admin');
$AdditionalLink[] = array('Manage Roll Menu', 'usersRollMenu/admin');

$sublink = '';
foreach ($AdditionalLink as $oneRow) {
    $lable = $oneRow[0];
    $uri_name = $oneRow[1];
    if ($loginUserId == 1 || in_array($uri_name, $uriNames)) {
        $sublink .= '<li><span class="menu_item_config"></span>' . CHtml::link($lable, array('/' . $uri_name)) . '</li>';
    }
}

if ($sublink != '') {
    ?>
            <li class="dir">Soft Configure
                <ul>                
                    <li class="dir"><span class="titleM">Config</span>    
                        <ul sub="sub">
    <?php
    echo $sublink;
    ?>
                        </ul>    
                    </li>
                </ul>
            </li>
    <?php
}
?>

    <li class="dir">Vehicle Config
        <ul>                
            <li class="dir"><span class="titleM">Vehicle Config</span>    
                <ul>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Vehicle Brand', array('/vehicleBrand/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Vehicle Model', array('/vehicleModel/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Vehicle Type', array('/vehicleType/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Unit Type', array('/unitType/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Distribution Cost Center', array('/distributionUnitName/admin')); ?></li>

                </ul>    
            </li>
        </ul>
    </li>
    <li class="dir">Vehicle Management
        <ul>                
            <li class="dir"><span class="titleM">Vehicle Management</span>    
                <ul>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Purchase', array('/purchaseItems/purchaseForm')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Received Purchase', array('/purchaseItemVehicle/adminReceive')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Vehicle Details', array('/vehicleInfo/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Distribution', array('/vTransactionHistory/admin')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Vehicle Transfer', array('/vehicleTransfer/admin')); ?></li>

                </ul>    
            </li>
        </ul>
    </li>


    <li class="dir">Report
        <ul>                
            <li class="dir"><span class="titleM">Report</span>    
                <ul>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Payment Report', array('/csBillMr/paymentReport')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Payment Summary Report', array('/csBillMr/summeryReport')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Complete Summary Report', array('//')); ?></li>
                    <li><span class="menu_item_config"></span><?php echo CHtml::link('Expenditure Report', array('/expenditure/expenditureReport')); ?></li>
                </ul>    
            </li>
        </ul>
    </li>

    <li><?php echo CHtml::link('Logout ( ' . Users::model()->fullNameOfThis(Yii::app()->user->getId()) . ' )', array('/site/logout')); ?></li>
</ul> -->
<style>
    ul#nav li.dir ul {
        display: none;
    }
</style>
<script>
    $(document).ready(function () {
        $("ul#nav li.dir").click(function () {
            $("ul#nav li.dir ul").toggle();
        });
    });
</script>