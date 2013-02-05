<div id="main">
    <div class="page-label">Isi data lengkap anda</div>
    <div class="form-wrap">
        <form class="form1">
            <p>
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="name" class="text" placeholder="Isi di sini" />
            </p>
            <p>
                <label>Email</label>
                <input type="text" name="email" id="email" class="text" placeholder="Isi di sini" value="<?= $email?>"/>
            </p>
            <p>
                <label>Nomor Telepon/HP</label>
                <input type="text" name="no_hp" id="phone" class="text" placeholder="Isi di sini"/>
            </p>
            <p>
                <label>Alamat</label>
                <textarea name="alamat" id="address" class="textarea" placeholder="Isi di sini"></textarea>
            </p>
            <p class="last">
                <label>Twitter Account</label>
                <input type="text" name="twitter" id="twitter" class="text" placeholder="Isi di sini"/>
            </p>
            <p>
                <label>Agree</label>
                <input type="checkbox" name="check" id="check" value="1"> 
            </p>
            <input type="button" name="submit" class="btn" value="Register" id="register" onclick="submit_register()"/>
        </form>
    </div>
    <div id="error"></div>
</div>
<script type="text/javascript">
    function submit_register(){
        $("#background_popup").fadeIn("fast");
        $("#loader").fadeIn("fast");
        var c = document.getElementById("check").checked;
        
        $.ajax({
            type		: "POST" ,
            url		    : "<?PHP echo site_url('page/process_register') ?>" ,
            data		: {
                "signed_request"	: "<?PHP echo $_POST["signed_request"] ?>",
                "name"              : $("#name").val(),
                "email"             : $("#email").val(),
                "address"           : $("#address").val(),
                "twitter"           : $("#twitter").val(),
                "phone"             : $("#phone").val(),
                "check"             : c
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
                    top.location.href = "<?= $this->facebook['canvas_url']."insert_story"?>";
                }
            }
        });
    }
</script>
