<?php include_once "header.php"?>
<body>
<?php $this -> fb_connect -> include_javascript()?>
<div id="container">
    <a href="<?= $this->facebook['canvas_url']."register" ?>" target="_top">Register</a><br/>
    <a href="#" onclick="invite()">Invite</a><br/>
</div>
<script type="text/javascript">
	function invite() {
    	FB.ui(
        	{
            	method  : "apprequests",
                message	: "Lorem Ipsum",
            });
        }
</script>
</body>
</html>