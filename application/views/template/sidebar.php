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
            <li class="">
                <a href="<?=base_url()."cellarea"?>">
                    <i class="fa fa-dashboard"></i> <span>Cell Area</span>
                </a>
            </li>

			<li class="">
				<a href="<?=base_url()."users"?>">
					<i class="fa fa-user-md"></i> <span>Users</span>
				</a>
			</li>
        </ul>
    </section>
    <!-- /.sidebar -->