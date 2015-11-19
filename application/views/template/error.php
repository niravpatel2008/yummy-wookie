<div class="col-md-12">
	<div id="flash_msg">
		<?php
			if ($this->session->flashdata('flash_type') == "success") {
				echo success_msg_box($this->session->flashdata('flash_msg'));
			}

			if ($this->session->flashdata('flash_type') == "error") {
				echo error_msg_box($this->session->flashdata('flash_msg'));
			}
		?>
	</div>
</div>