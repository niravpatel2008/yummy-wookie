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
						<h3 class="box-title col-md-12">Upload Excel <span class='pull-right' id='file-name'></span></h3>                                    
					</div><!-- /.box-header -->
					<div class="box-body clearfix">
						<div class='file-upload'>
							<form id="my-awesome-dropzone" action="<?=base_url()."cellarea/fileupload"?>" class="dropzone"></form>
						</div>
						<div class='file-process' style='display:none;'>
							<div class='file-process-body'>
								<div id='file-mapping'>
									<form name='file-process-form' id='file-process-form'>
									<input type="hidden" name='filename' id="filename" value="">
									<div class="form-group col-md-6">
										<label>Company Name:</label>
										<select id='company_name' name='company_name' class="form-control validate[required]">
											<option value=''>Select Company</option>
											<?
												foreach ($companies as $company)
												{
													?>
													<option value='<?= ($company)?>'><?= strtoupper($company)?></option>
													<?php 
												}
											?>
										</select>
									</div>
									<?
									foreach ($filefields as $field)
									{
										?>
										<div class="form-group col-md-6">
											<label><?= strtoupper(str_replace("_"," ",$field))?>:</label>
											<select id='field[<?= $field?>]' name='field[<?= $field?>]' class="file-process-field form-control validate[required]"></select>
										</div>
										<?php 
									}
									?>
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
						<table id="cellareaTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>COMPANY</th>
									<th>CELL ID</th>
									<th>TOWN</th>
									<th>ADDRESS</th>
									<th>LATITUDE</th>
									<th>LOGITUDE</th>
									<th>CREATED</th>
									<th>MODIFIED</th>
									<th>ACTION</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>COMPANY</th>
									<th>CELL ID</th>
									<th>TOWN</th>
									<th>ADDRESS</th>
									<th>LATITUDE</th>
									<th>LOGITUDE</th>
									<th>CREATED</th>
									<th>MODIFIED</th>
									<th>ACTION</th>
								</tr>
							</tfoot>
						</table>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
    	</div>
    </div>
</section>
<script>
var companies = <?php echo json_encode($companies)?>;
</script>