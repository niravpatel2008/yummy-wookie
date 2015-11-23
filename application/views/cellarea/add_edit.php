<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?=($this->router->fetch_method() == 'add')?'Add Cell ID':'Update Cell ID'?></h4>
</div>
<div class="modal-body clearfix">
	<form name='cellinfo-addedit' id='cellinfo-addedit'>
	<div class="form-group col-md-6">
		<label>Company Name:</label>
		<select id='company' name='company' class="form-control validate[required]">
			<option value=''>Select Company</option>
			<?php
				foreach ($companies as $company)
				{
					?>
					<option value='<?= ($company)?>' <?php echo (@$cellarea[0]->company == $company)?"selected":"";?>><?= strtoupper($company)?></option>
					<?php 
				}
			?>
		</select>
	</div>
	<?php
	foreach ($filefields as $field)
	{
		?>
		<div class="form-group col-md-6">
			<label><?= strtoupper(str_replace("_"," ",$field))?>:</label>
			<input id='field[<?= $field?>]' type="text" name='<?= $field?>' class="form-control validate[required]" value="<?=@$cellarea[0]->$field?>">
		</div>
		<?php 
	}
	?>
	</form>
</div>
<div class="modal-footer">
	<button  class="btn btn-primary btn-flat" onclick="<?=($this->router->fetch_method() == 'add')?'submit_cellinfo()':'submit_cellinfo('.@$cellarea[0]->id.')'?>">Save</button>
	<button  data-dismiss="modal" class="btn btn-primary btn-flat" onclick="">Close</button>
</div>