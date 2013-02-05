<div id="main">
    <?php 
        if (!empty($storys)){
            foreach ($storys as $item_story) {
                ?>
                    <div>
                        <img src="https://graph.facebook.com/<?= $item_story['fb_uid']?>/picture" />
                        <span><?= $item_story['name']?></span>
                        <p><?= $item_story['reason']?></p>
                        <a href="<?= site_url('page/gallery_detail/'.$item_story['id_story'])?>" target='_top'>Detail</a>
                    </div>
                <?php 
            }
        }
    ?>
</div>


