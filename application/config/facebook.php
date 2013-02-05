<?php
/*config for facebook*/

$config['canvas_url']   = 'https://apps.facebook.com/missmondial/';
$config['app_id']       = '202940039843952';
$config['page_id']      = "228752263850064";
$config['app_secret']   = "a4d097d35f637a86a4dc5e79282f9ec7";
$config['landing_page'] = "https://www.facebook.com/AnwarTestPage/app_202940039843952";
$fullpath               = explode("application\config", dirname(__FILE__));
$config['callback_dir'] = $fullpath[0];