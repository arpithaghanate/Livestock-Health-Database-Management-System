<?php

$mani= basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
?>

    <nav class="pcoded-navbar navbar-collapsed menu-light brand-lightblue menupos-static menu-item-icon-style5 title-purple active-red drp-icon-style2 icon-colored" >
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="index.php" class="b-brand">
                    <div class="b-bg">
                        <i class="feather icon-trending-up"></i>
                    </div>
                    <span class="b-title">Admin Panel</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            </div>
            <div class="navbar-content scroll-div" style="background-image: url(https://steamuserimages-a.akamaihd.net/ugc/812182356879927695/0775E6C68CA2A4314FBAEF5627BEDED99F282639/);

    background-size: auto;">
                <div class="layout1-nav">
                    <div class="side-content">
                        <div class="sidelink navigation active">
                            <ul class="nav pcoded-inner-navbar">
                                <li class="nav-item pcoded-menu-caption">
                                    <label>Admin Panel</label>
                                </li>
<li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" 
<?php  if($mani=='')  {
                    echo "class='nav-item pcoded-hasmenu active pcoded-trigger' ";
                  } else{
                    echo "class='nav-item pcoded-hasmenu' ";
                  }          ?>
>
                                    <a href="medical_1.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                                </li>

                                  <!--   <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project"  class="dropdown">
                                    <a href="" class="dropdown-toggle nav-link" data-toggle="dropdown" data-target="#mynav"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Sales</a>
                                    <ul class="dropdown-menu" id="mynav">
                                      <li href="">option_1</li>
                                      <li href="">option_2</li>
                                    </ul>
                                </li> -->

         
              <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" <?php  if($mani=='')  {
                    echo "class='nav-item pcoded-hasmenu active pcoded-trigger' ";
                  } else{
                    echo "class='nav-item pcoded-hasmenu' ";
                  }          ?>>
                                    <a href="medical_1.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Medical</span></a>
                                </li>

                           <!-- Sale_Report.php -->
              
                                                       





             
     



              
                   
                   <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" <?php  if($mani=='Listofitems.php')  {
                    echo "class='nav-item pcoded-hasmenu active pcoded-trigger' ";
                  } else{
                    echo "class='nav-item pcoded-hasmenu' ";
                  }          ?>>
                                    <a href="logout.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Logout</span></a>
                                </li>

                                      

                            </ul>
                        </div>
          
                    </div>
                </div>
            </div>
        </div>
    </nav>