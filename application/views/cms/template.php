<!DOCTYPE html>
<html>
    <head>
        <title>Admin Page</title>
        <!-- Bootstrap -->
        <style type="text/css">
            body {padding-top: 60px}
        </style>
        <link href="<?= base_url()?>bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
            <link href="<?= base_url()?>bootstrap/datepicker/css/datepicker.css" rel="stylesheet" media="screen">
        <script src="<?= base_url()?>bootstrap/js/jquery.js"></script>
        <script src="<?= base_url()?>bootstrap/js/bootstrap.js"></script>
        <script src="<?= base_url()?>bootstrap/datepicker/js/bootstrap-datepicker.js"></script>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <div class="brand">Miss Mondial Admin</div>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><?php echo anchor('cms/home', 'Home') ?></li>
                            <li><?php echo anchor('cms/datauser', 'User') ?></li>
                            <li><?php echo anchor('cms/datastory', 'Story') ?></li>
                            <li><?php echo anchor('cms/logout', 'Logout') ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php if (!empty($content)): ?>
            <?php $this->load->view($content); ?>
            <?php endif; ?>
        </div>
    </body>
</html>