
<div id="main">

    <div class="page-label">Isi data Ibu Pahlawanmu Yang Kamu Rekomendasikan</div>

    <div class="form-wrap">
        <form class="form2">
            <p>
                <label>Endorsed Person</label>
                <select name="endorsed_person" id="endorsed_person">
                    <option value="Riani J">Riani J</option>
                    <option value="Omaswati">Omaswati</option>
                    <option value="Soimah">Soimah</option>
                </select>
            </p>
            <p>
                <label>Suggest Place</label>
                <select name="place" id="place">
                    <?php 
                        foreach ($places as $item_place) {
                            ?>
                                <option value="<?= $item_place['place']?>"><?= $item_place['place']?></option>
                            <?php
                        }
                    ?>
                </select>
            </p>
            <p>
                <label class="short">Reason</label>
                <input type="text" name="reason" id="reason" class="text" placeholder="Isi di sini"/>
            </p>
            <input type="button" name="submit" class="btn" value="Submit" id="register" onclick="submit_story()" />
        </form>
    </div>
    <div id="error"></div>
</div>


<script type="text/javascript">
    function submit_story(){
        $("#background_popup").fadeIn("fast");
        $("#loader").fadeIn("fast");
        $.ajax({
            type		: "POST" ,
            url		    : "<?PHP echo site_url('page/process_insert_story') ?>" ,
            data		: {
                "signed_request"	: "<?PHP echo $_POST["signed_request"] ?>",
                "endorsed_person"   : $("#endorsed_person").val(),
                "suggest_place"     : $("#place").val(),
                "reason"            : $("#reason").val()
            },
            dataType	: "json" ,
            success		: function(response){
                if (response.t == "error")
                {
                    $("#background_popup").fadeOut("fast");
                    $("#loader").fadeOut("fast");
                    $("#error").html(response.m);
                    $("#error").fadeIn ("slow");
                }
                else {
                    top.location.href = "<?= $this->facebook['canvas_url']."gallery"?>";
                }
            }
        });
    }
</script>
