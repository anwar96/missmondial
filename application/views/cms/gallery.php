<script type="text/javascript" src="<?= base_url()?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?= base_url()?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url()?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
        $("a.example4").fancybox();
    });
</script>
<h1>Gallery</h1>
<a class="btn btn-primary" href="<?= site_url('cms/addgallery')?>">Add Gallery</a>
    <br/><br/>
<?php
if(!empty($datagallerys)){
    ?>
<table class="table table-hover table-bordered table-striped">
    <thead>
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>Date Upload</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($datagallerys as $datagallery){
            ?>
        <tr>
            <td><?= $i?></td>
            <td><?= $datagallery['title']?></td>
            <td><?= $datagallery['date_uploaded']?></td>
            <td>
                <a class="example4" title="<?= $datagallery['title']?>" href="<?= site_url('uploaded/gallery/'.md5($datagallery['id_gallery']).".jpg?d=".date('YmdHis'))?>">View Image</a> |
                <a href="<?= site_url('cms/editgallery/'.$datagallery['id_gallery'])?>">Edit</a> |
                <a href="<?= site_url('cms/deleteimage/'.$datagallery['id_gallery'])?>">Delete</a>
            </td>
        </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
</table>
<?php
}
else echo "Tidak ada data";
?>