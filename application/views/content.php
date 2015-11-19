<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('template/head');?>
	</head>
	<body class="<?php echo (isset($body_class))?$body_class:"skin-purple"?>">
		<div class="wrapper">

			<?php if (!isset($noheader)) {?>
				<header class="main-header">
					<?php $this->load->view('template/header');?>
				</header>
			<?php }?>
			
			<?php if (!isset($nosidebar)) {?>
			<aside class="main-sidebar">
				<?php $this->load->view('template/sidebar');?>
			</aside>
			<?php }?>

			<div class="<?php echo (isset($content_class))?$content_class:"content-wrapper"?>" style="min-height: 918px;">
				<?php $this->load->view("template/error");?>
				<?php $this->load->view($this->router->fetch_class()."/".$view);?>
			</div>

			<?php if (!isset($nofooter)) {?>
			<footer class="main-footer">
				<?php $this->load->view('template/footer');?>
			</footer>
			<?php }?>
		</div>
	</body>
		<?php $this->load->view('template/script');?>
</html>