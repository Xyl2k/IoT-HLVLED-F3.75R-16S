<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>HL</b>N</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>HLVLED-F3.75R-16S</b>World</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="index.php" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
    
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $userPicture; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo htmlentities($userName); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo $userPicture; ?>" class="img-circle" alt="User Image">
    
                <p>
                  <?php echo htmlentities($userName); ?> - Administrator
                  <small>Member since <?php echo date("M. Y",strtotime($userData['created'])); ?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="adminAccount.php?logoutSubmit=1" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
</header>
