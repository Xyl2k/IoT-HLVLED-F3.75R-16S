<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo $userPicture; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo htmlentities($userName); ?></p>
                <a href="profile.php"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li>
              <a href="index.php">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class="treeview">
              <a href="messages.php">
                <i class="fa fa-files-o"></i>
                <span>Messages</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="messages.php"><i class="fa fa-circle-o"></i> All</a></li>
                <li><a href="addEditMessage.php"><i class="fa fa-circle-o"></i> Add New</a></li>
              </ul>
            </li>
            <li>
              <a href="settings.php">
                <i class="fa fa-th"></i> <span>Settings</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green">Site Settings</small>
                </span>
              </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>