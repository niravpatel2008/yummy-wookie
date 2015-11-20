<section class="content-header">
    <h1>
        Cell Area
    </h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-6">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Upload Excel</h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class='file-upload'>
							<form id="my-awesome-dropzone" action="<?=base_url()."cellarea/fileupload"?>" class="dropzone"></form>
						</div>
						<div class='file-process' style='display:none;'>
							<div class='file-process-body'>
								<div id='file-name'>
									<b></b>
								</div>
								<div id='file-mapping'>
									<form name='file-process-form' id='file-process-form'>
									<input type="hidden" name='filename' id="filename" value="">
									<div class="form-group">
										<label>Company Name:</label>
										<select id='company_name' name='company_name' class="form-control validate[required]">
											<option value=''>Select Company</option>
											<option value='idea'>IDEA</option>
											<option value='airtel'>AIRTEL</option>
											<option value='vodafone'>VODAFONE</option>
											<option value='telenor'>TELENOR</option>
											<option value='docomo'>DOCOMO</option>
											<option value='reliance'>RELIANCE</option>
										</select>
									</div>
									<div class="form-group">
										<label>Lat:</label>
										<select id='field_lat' name='field_lat' class="file-process-field form-control validate[required]"></select>
									</div>
									<div class="form-group">
										<label>Long:</label>
										<select id='field_lng' name='field_lng' class="file-process-field form-control validate[required]"></select>
									</div>
									</form>
								</div>
							</div>
						</div>
					</div><!-- /.box-body -->
					<div class='box-footer file-process' style='display:none;'>
						<button class="btn btn-primary btn-flat" type="button" id="file-submit">Submit</button>
						<button class="btn btn-primary btn-flat" type="button" id="file-cancel">Cancel</button>
					</div>
				</div><!-- /.box -->
		</div>
		<div class="col-md-6">
		</div>
	</div>
	<div class="row">
    	<div class="col-md-12">
    		<a class="btn btn-default pull-right" href="<?=base_url()."users/add"?>">
            <i class="fa fa-plus"></i>&nbsp;Add</a>
            <div id="list">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">list</h3>                                    
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