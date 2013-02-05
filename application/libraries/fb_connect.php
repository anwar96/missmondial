<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	error_reporting(0);
	class fb_connect{
	
		var $instan;
        var $app_id;
        var $page_id;
        var $app_secret;
        var $canvas_url;
        var $callback_dir;
        var $landing_page;

        function __construct(){
			$CI =& get_instance();
			$this->instan       = $CI;
            $this->instan->config->load('facebook', TRUE);
            $this->facebook     = $this->instan->config->item('facebook');
            $this->app_id       = $this->facebook['app_id'];
            $this->page_id      = $this->facebook['page_id'];
            $this->app_secret   = $this->facebook['app_secret'];
            $this->canvas_url   = $this->facebook['canvas_url'];
            $this->landing_page = $this->facebook['landing_page'];
            $this->callback_dir = dirname(__FILE__);
            
        }

        private function base64_url_decode($input) {
			return base64_decode (strtr ($input, '-_', '+/'));
		}

		public function get_signedRequest() {
			$signed_request	= $_POST ["signed_request"];
			list ($encoded_sig , $payload) = explode ('.' , $signed_request , 2);
			$sig 	= $this -> base64_url_decode ($encoded_sig);
			$data 	= json_decode ($this -> base64_url_decode ($payload) , true);
			return $data;
		}

		public function call_openGraph($graph_url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $graph_url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = curl_exec($ch);
			$output	= json_decode ($output);
			curl_close($ch);
			return $output;
		}

		public function authentication($function) {
			$arr_signedReq	= $this -> get_signedRequest ();
			$fb_uid			= $arr_signedReq ["user_id"];

			$permission		= $this -> get_permission();
			if($permission != "") {
				$filename		= $_SERVER ["SCRIPT_FILENAME"];
				$filename		= str_replace ($this->callback_dir."/" , "" , $filename);
				$query_get		= $_SERVER ["QUERY_STRING"];
				$full_url		= $this->canvas_url.$function."?";
				$full_url		= $full_url.$query_get;
				$auth_url = "http://www.facebook.com/dialog/oauth?client_id=".$this->app_id."&redirect_uri=".urlencode($full_url)."&scope=".$permission;
				?>
				<script language="javascript">top.location.href	= "<?PHP echo $auth_url ?>"</script>
				<?PHP
			}
            return $fb_uid;
	    }

        function user_data(){
            $arr_signedReq  = $this -> get_signedRequest ();
            $fb_uid         = $arr_signedReq ["user_id"];
            $oauth_token    = urlencode ($arr_signedReq ["oauth_token"]);
            $fql    = "SELECT uid, name,email FROM user WHERE uid = '".$fb_uid."'";
            $fql    = urlencode ($fql);
            $result = $this -> call_openGraph ("https://api.facebook.com/method/fql.query?query=".$fql."&format=json&access_token=".$oauth_token);
            return $result;
        }

		public function get_permission() {
            $not_exists     = "";
			$arr_signedReq	= $this -> get_signedRequest();
			$oauth_token	= urlencode ($arr_signedReq["oauth_token"]);

			$graph_url	= "https://graph.facebook.com/me/permissions?access_token=".$oauth_token;
			$result		= $this -> call_openGraph ($graph_url);

			$obj_result		= $result -> data;
			$arr_permission	= $obj_result[0];

			$permission		= array("user_birthday", "user_likes" ,"user_interests", "user_location", "user_relationships", "user_relationship_details", "publish_stream", "email", "user_religion_politics", "user_photos", "publish_stream");

			foreach($permission as $item_permission) {
				if($arr_permission -> $item_permission != 1) $not_exists .= $item_permission.",";
			}

			$not_exists = substr($not_exists, 0, -1);
			return $not_exists;
		}

		public function check_isFan() {
			$arr_signedReq	= $this -> get_signedRequest();
			$oauth_token	= urlencode ($arr_signedReq["oauth_token"]);

			$graph_url	= "https://graph.facebook.com/me/likes/".$this->page_id."?access_token=".$oauth_token;
			$result		= $this -> call_openGraph ($graph_url);

			$count_result = count($result -> data);
			if($count_result == 0) return false;
			else return true;
		}

		public function include_javascript() {
			?>
            <div id="fb-root"></div>
            <script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
            <script type="text/javascript">
                FB.init({appId: '<?PHP echo $this->app_id ?>', status: true, cookie: true, xfbml: true});
				FB.Canvas.setAutoGrow();
			</script>
            <?PHP
		}

		public function redirect($goto) {
            ?>
            <script type="text/javascript">
                top.location.href   = "<?PHP echo $goto ?>";
            </script>
            
            <?PHP
        }

		public function delete_request($id) {
			$arr_signedReq	= $this -> get_signedRequest();
			$oauth_token	= urlencode ($arr_signedReq["oauth_token"]);

			$graph_url	= "https://graph.facebook.com/".$id."?access_token=".$oauth_token."&method=delete";
			$this -> call_openGraph ($graph_url);
		}

		public function get_request($id) {
			$arr_signedReq	= $this -> get_signedRequest();
			$oauth_token	= urlencode($arr_signedReq["oauth_token"]);

			$graph_url	= "https://graph.facebook.com/".$id."?access_token=".$oauth_token;
			$result		= $this -> call_openGraph ($graph_url);
			return $result;
		}

        public function auto_publish($name_user) {
            $arr_signedReq	= $this -> get_signedRequest ();
            $oauth_token	= urlencode($arr_signedReq ["oauth_token"]);
            $image			= urlencode(base_url()."assets/css/img/share.jpg");
            $link			= urlencode($this->canvas_url);
            $name			= urlencode("Miss Mondial");
            $caption		= urlencode($name_user." lorem ipsum");
            $graph_url		= "https://graph.facebook.com/me/feed?message=".$caption."&picture=".$image."&link=".$link."&name=".$name."&caption=".$caption."&access_token=".$oauth_token."&method=post";
            $this -> call_openGraph ($graph_url);
        }

        public function get_friend ()
        {
            $arr_signedReq	= $this -> get_signedRequest ();
            $oauth_token	= urlencode ($arr_signedReq ["oauth_token"]);

            $graph_url	= "https://graph.facebook.com/me/friends?access_token=".$oauth_token;
            $result		= $this -> call_openGraph ($graph_url);
            return $result;
        }

        public function get_album() {
            $arr_signedReq	= $this -> get_signedRequest();
            $oauth_token	= urlencode ($arr_signedReq["oauth_token"]);

            $graph_url	= "https://graph.facebook.com/me/albums?access_token=".$oauth_token;
            $result		= $this -> call_openGraph($graph_url);
            return $result;
        }

        public function get_photo($id) {
            $arr_signedReq	= $this -> get_signedRequest ();
            $oauth_token	= urlencode ($arr_signedReq ["oauth_token"]);

            $graph_url	= "https://graph.facebook.com/".$id."/photos?access_token=".$oauth_token;
            $result		= $this -> call_openGraph ($graph_url);
            return $result;
        }

        public function getPhoto ($aid)
        {
            $signed_request	= $_POST ["signed_request"];
            list ($encoded_sig , $payload) = explode ('.' , $signed_request , 2);
            $arr_signedReq 	= json_decode ($this -> base64_url_decode ($payload) , true);

            $oauth_token	= urlencode ($arr_signedReq ["oauth_token"]);
            //print_r($oauth_token);

            $fb_uid			= $arr_signedReq ["user_id"];
            $fql	= "SELECT aid FROM album WHERE object_id = '".$aid."'";
            $fql	= urlencode ($fql);
            $result	= $this -> call_openGraph ("https://api.facebook.com/method/fql.query?query=".$fql."&format=json&access_token=".$oauth_token."&");
            $aid	= $result [0] -> aid;

            $fql	= "SELECT pid,src_small,src_big FROM photo WHERE aid = '".$aid."'";
            $fql	= urlencode ($fql);
            $result	= $this -> call_openGraph ("https://api.facebook.com/method/fql.query?query=".$fql."&format=json&access_token=".$oauth_token."&");
            return $result;
        }

        public function get_name($fb_uid) {
            $arr_signedReq = $this -> get_signedRequest ();
            $oauth_token = urlencode ($arr_signedReq ["oauth_token"]);

            $fql = "SELECT name FROM user WHERE uid = '".$fb_uid."'";
            $fql = urlencode ($fql);
            $result = $this -> call_openGraph ("https://api.facebook.com/method/fql.query?query=".$fql."&format=json&access_token=".$oauth_token);
            $name = addslashes (trim ($result [0] -> name));

            return $name;
        }
	}


?>