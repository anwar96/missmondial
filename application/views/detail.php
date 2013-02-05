<div id="main">
    <?php 
        if (!empty($story)){
        	?>
        		<div><?= $story['name']?></div>
        		<div><img src="https://graph.facebook.com/<?= $story['fb_uid']?>/picture?type=large" /></div>
        		<div><?= $story['story']?></div>
        		<div><span>Total Like</span><span><?= $story['total_like']?></span></div>
        		<div onclick="process_like()">Like</div>
        		<div id="error"></div>
        	<?php            
        }
    ?>    
</div>
<script type="text/javascript">
	function process_like(){
        $("#background_popup").fadeIn("fast");
        $("#loader").fadeIn("fast");
        
        $.ajax({
            type		: "POST" ,
            url		    : "<?PHP echo site_url('page/process_like') ?>" ,
            data		: {
                "signed_request"	: "<?= $signed_request ?>",
                "idx"				: "<?= $story['id_story']?>"
            },
            dataType	: "json" ,
            success		: function(response){
                if (response.t == "error")
                {
                    $("#background_popup").fadeOut("fast");
                    $("#loader").fadeOut("fast");
                    $("#error").html(response.m);
                }
                else {
                    //top.location.href = "<?= $this->facebook['canvas_url']."insert_story"?>";
                }
            }
        });
    }
</script>