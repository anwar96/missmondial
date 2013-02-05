<h1>Add Gallery</h1>
<div class="span5">
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="inputTitle">Title</label>
            <div class="controls">
                <input type="text" id="inputTitle" name="inputTitle" placeholder="Title">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Choose File</label>
            <div class="controls">
                <form id="form2" method="post" enctype="multipart/form-data" target="form_post" action="<?= site_url('cms/do_upload')?>">
                    <input type="file" name="userfile" id="file" class="hide ie_show" value="" onchange="submit_photo()" />
                    <input type="submit" class="hide ie_show">
                </form>
                <div class="input-append ie_hide" id="test">
                    <input id="pretty-input"  class="input-medium" type="text" onclick="$('input[id=file]').click();">
                    <a class="btn" onclick="$('input[id=file]').click();">Browse</a>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls" id="loader">
                <a class="btn btn-primary" onclick="submit()">Submit</a>
            </div>
            <div class="controls">
                <div id="error2"></div>
            </div>
        </div>
    </div>
</div>
<div class="span5">
    <div id="error"></div>
    <div id="frame_photo"></div>
</div>
<iframe name="form_post" style="display: none;"></iframe>
<script type="text/javascript">
    function submit_photo(){
        var a = $("#file").val();
        $("#pretty-input").val(a);
        $("#frame_photo").html("loading..");
        $("#form2").submit();
    }

    function show_message(msg) {
        success = 0;
        $("#error").html(msg);
        $("#error").fadeIn("fast");
        $("#frame_photo").html("");
    }

    function refresh_image(url) {
        success = 1;
        $("#error").fadeOut("fast");
        $("#frame_photo").html("<img id='uploadedImage' class='img-polaroid' width='250' src='"+url+"' />");
    }

    function submit(){
        $('#loader').html('Loading...');
        var path = $("#uploadedImage").attr('src');
        var ary = path.split("/");
        var imgname = ary[ary.length - 1];

        $.ajax({
            type		: 'post',
            url			: '<?= site_url('cms/save_image')?>',
            data		: {
                'image'    : imgname,
                "title"    : $("#inputTitle").val()
            },
            dataType	: 'json',
            success		: function(response) {
                if (response.t == "error"){
                    $("#error2").html(response.m);
                    $("#error2").fadeIn("fast");
                    $('#loader').html('<a class="btn btn-primary" onclick="submit()">Submit</a>');
                }
                else {
                    top.location.href = "<?= base_url('cms/gallery') ?>";
                }
            }
        });
    }

</script>