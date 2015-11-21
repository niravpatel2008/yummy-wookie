<!-- jQuery 2.0.2 -->
    <script src="<?=public_path()?>js/jquery.2.0.2.min.js"></script>
    <script src="<?=public_path()?>js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/datatables/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>


    <script type="text/javascript">
        function admin_path () {
            return '<?=base_url()?>';
        }

        function success_msg_box (msg) {
            var html = '<div class="alert alert-success alert-dismissable"> \n\
                            <i class="fa fa-check"></i> \n\
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> \n\
                            '+msg+' \n\
                        </div>';
            return html;
        }

        function error_msg_box(msg)
        {
            var html = '<div class="alert alert-danger alert-dismissable"> \n\
                            <i class="fa fa-ban"></i> \n\
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> \n\
                            '+msg+' \n\
                        </div>';
            return html;
        }

        function IsEmail(email) {
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          return regex.test(email);
        }

        function isNumberKey(e) {
            var charCode = (e.which) ? e.which : e.keyCode
			console.log(charCode);
            if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 36 || charCode > 41))
                return false;
            return true;
        }
    </script>
	<?php if (in_array($this->router->fetch_method(), array("add","edit"))) { ?>
		<script src="<?=public_path()?>js/plugins/daterangepicker/datepicker.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/validation/btvalidationEngine.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/validation/btvalidationEngine-en.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/admin/<?=$this->router->fetch_class()?>/add_edit.js" type="text/javascript"></script>
	<?php }?>
	    
	<?php if ($this->router->fetch_class() == "cellarea" && in_array($this->router->fetch_method(), array("index"))) { ?>
		<script src="<?=public_path()?>js/plugins/dropzone/dropzone.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/validation/btvalidationEngine.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/validation/btvalidationEngine-en.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/loading.js" type="text/javascript"></script>

    <?php } ?>

	<script src="<?=public_path()?>js/admin/<?=$this->router->fetch_class()?>/index.js" type="text/javascript"></script>
    <script src="<?=public_path()?>js/AdminLTE/app.js" type="text/javascript"></script>
