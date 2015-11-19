<section class="content-header">
    <h1>
        Cell Area
    </h1>
</section>
<section class="content">
	<div class="row">
    	<div class="col-md-12">
    		<a class="btn btn-default pull-right" href="<?=base_url()."users/add"?>">
            <i class="fa fa-plus"></i>&nbsp;Add User</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">User list</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
						<table id="usersTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Name</th>
									<th>Role</th>
									<th>Email</th>
									<th>Created At</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Role</th>
									<th>Contact</th>
									<th>Created At</th>
									<th>Action</th>
								</tr>
							</tfoot>
						</table>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
    	</div>
    </div>
</section>