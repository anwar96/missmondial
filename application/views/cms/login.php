<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Sign in · Admin Page</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="description">
        <meta content="" name="author">

        <!-- Le styles -->
        <link rel="stylesheet" href="<?= base_url() ?>bootstrap/css/bootstrap.css">
        <style type="text/css">
            body {padding-top: 40px;padding-bottom: 40px;background-color: #f5f5f5;}
            .form-signin {max-width: 300px;padding: 19px 29px 29px;margin: 0 auto 20px;background-color: #fff;border: 1px solid #e5e5e5;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);box-shadow: 0 1px 2px rgba(0,0,0,.05);}
            .form-signin .form-signin-heading,.form-signin .checkbox {margin-bottom: 10px;}
            .form-signin input[type="text"],.form-signin input[type="password"] {font-size: 16px;height: auto;margin-bottom: 15px;padding: 7px 9px;}
            #error {font-size: 11px;margin-top: 10px;}
        </style>
        <link rel="stylesheet" href="<?= base_url()?>bootstrap/css/bootstrap-responsive.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <form class="form-signin" action="<?= site_url('cms/login')?>" method="post">
                <h2 class="form-signin-heading">Please sign in</h2>
                <input type="text" name="username" placeholder="Username" class="input-block-level">
                <input type="password" name="password" placeholder="Password" class="input-block-level">
                <button type="submit" class="btn btn-large btn-primary">Sign in</button>
                <div id="error">
                    <?php if ($this->session->flashdata('message')): ?>
                    <?php echo $this->session->flashdata('message'); ?>
                    <?php endif; ?>
                    <?php echo validation_errors(); ?>
                </div>
            </form>
        </div>
        <!-- /container -->
    </body>
</html>