<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- <link href="<?= base_url()?>assets/css/tab.css" rel="stylesheet" type="text/css" /> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <title>Dapur peduli</title>
</head>
<?php
error_reporting(0);
$arr_signedReq	= $this->fb_connect -> get_signedRequest();
$liked			= $arr_signedReq["page"]["liked"];
?>
<body>
<?php $this->fb_connect->include_javascript()?>
<div id="container">
    <?PHP
    if($liked == 1) {
        ?>
        <a href="<?= site_url('page/tab2')?>">Tab 2</a>
        <div class="btn_klik">
            <a href="<?= $this->facebook['canvas_url']?>" target="_top"><img src="<?= base_url()?>assets/css/images/btn-disini.png" width="181" height="49" /></a>
        </div>
        <?PHP
    }
    else{
        ?>
        <div class="btn">
            <img src="<?= base_url()?>assets/css/images/btn-klik.png" height="27" width="118" alt="#" />
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>