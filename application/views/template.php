<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="overflow:hidden;">
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <link rel="stylesheet" href="<?= base_url()?>themes/base/jquery.ui.all.css">
	    <link rel="stylesheet" href="<?= base_url()?>assets/css/demos.css">
	    <!-- <link rel="stylesheet" href="<?= base_url()?>assets/css/style.css"> -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	</head>
	<body>
		<?php $this->fb_connect->include_javascript()?>
		<div id="container">
			<?php if (!empty($content)): ?>
            <?php $this->load->view($content); ?>
            <?php endif; ?>
			<img id="loader" src="<?= base_url()."assets/css/img/279.gif"?>"  style="display:none";/>
			<div id="background_popup"></div>
		</div>
	</body>
</html>
