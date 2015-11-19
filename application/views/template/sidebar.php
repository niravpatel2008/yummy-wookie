    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <!--<div class="pull-left image">
                <img alt="User Image" class="img-circle" src="img/avatar3.png">
            </div> -->
            <div class="pull-left info">
                <p>Hello, <?php echo $this->user_session['name'];?></p>

                <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
        </div>
        <!-- search form -->
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
			<?php if (hasAccess("dashboard")){ ?>
            <li class="">
                <a href="<?=base_url()."dashboard"?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
			<?php } ?>
			
			<?php if (hasAccess("category") || hasAccess("product") || hasAccess("purchase")){ ?>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-th"></i>
					<span>Inventory</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu" style="display: none;">
					<?php if (hasAccess("category")){ ?>
					<li><a href="<?=base_url()."category"?>"><i class="fa fa-circle-o"></i>Category</a>
					<?php } ?>
					
					<?php if (hasAccess("product")){ ?>
					<li><a href="<?=base_url()."product"?>"><i class="fa fa-circle-o"></i>Product</a>
					<?php } ?>

					<?php if (hasAccess("purchase")){ ?>
					<li><a href="<?=base_url()."purchase"?>"><i class="fa fa-circle-o"></i>Purchase</a>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
			
			<?php if (hasAccess("takein") || hasAccess("invoice")){ ?>
			<li class="treeview">
				<a href="#">
					<i class="fa fa-book"></i>
					<span>Billing</span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu" style="display: none;">
					<?php if (hasAccess("takein")){ ?>
					<li><a href="<?=base_url()."takein"?>"><i class="fa fa-circle-o"></i>Take In</a>
					<?php } ?>

					<?php if (hasAccess("invoice")){ ?>
					<li><a href="<?=base_url()."invoice"?>"><i class="fa fa-circle-o"></i>Bill</a>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>

			<?php if (hasAccess("customer")){ ?>
			<li class="">
				<a href="<?=base_url()."customer"?>">
					<i class="fa fa-users"></i> <span>Customers</span>
				</a>
			</li>
			<?php } ?>
          
            <?php if (hasAccess("users")){ ?>
			<li class="">
				<a href="<?=base_url()."users"?>">
					<i class="fa fa-user-md"></i> <span>Users</span>
				</a>
			</li>
            <?php } ?>
        </ul>
    </section>
    <!-- /.sidebar -->