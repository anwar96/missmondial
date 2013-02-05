<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!-- <link href="<?= base_url()?>assets/css/tab.css" rel="stylesheet" type="text/css" /> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <title>Dapur peduli</title>
    <script type="text/javascript">
        $(document).ready(function(){
            var a = window.parent.location;
            var b = window.location;
            if (a == b){
                top.location.href = '<?= $this->facebook['landing_page']?>';
            }
        });
    </script>
</head>
<?php
error_reporting(0);
$arr_signedReq	= $this->fb_connect -> get_signedRequest();
$liked			= $arr_signedReq["page"]["liked"];
?>
<body>
<?php $this->fb_connect->include_javascript()?>
<div id="container">
    page 2
</div>
</body>
</html>