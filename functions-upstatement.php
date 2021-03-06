<?php

	require_once(ABSPATH . WPINC . '/registration.php');

	function get_gallery_info($gid){
		$query = "SELECT * FROM wp_ngg_gallery WHERE gid = '$gid'";
		$result = mysql_query($query);
		$row = mysql_fetch_object($result);
		return $row;
	}
	
	function add_links_to_string($str){
		$new = preg_replace("/(http:\/\/[^\s]+)/", "<a href=\"$1\">$1</a>", $str);
		return $new; /* will display $text with link to http://www.whyistheskyblue.com/... */
	}
	
	function twitterify($ret) {
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
		return $ret;
	}

	
	function eggs_get_post_gallery($pid){
		$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $pid, 'orderby' => 'menu_order', 'order' => 'ASC' ); 
		$attachments = get_posts($args);
		$att2 = array();
		foreach($attachments as $att){
			if ($att->menu_order > 0){
				$att2[] = $att;
			}	
		}
		//print_r($attachments);
		return $att2;
	}
	
	function get_term_info($tid){
		$query = "SELECT * FROM wp_terms WHERE term_id = '$tid'";
		$result = mysql_query($query);
		$row = mysql_fetch_object($result);
		return $row;
	}
	
	function get_post_thumbnail($pid, $w, $h=0){
		$tid = get_post_thumbnail_id($pid);
		$size = 'full';
		
		$src = wp_get_attachment_image_src($tid, $size);
		$src = $src[0];
		if (!isset($src)){
			$gal = get_post_gallery($pid);
			$src = $gal[0]->guid;
		}
		if (isset($w) && $h == 0){
			$base = basename($src);
			$src = '/wp-content/image.php/'.$base.'?image='.$src.'&amp;width='.$w;
		} else if (isset($w) && isset($h)){
			$base = basename($src);
			$src = '/wp-content/image.php/'.$base.'?image='.$src.'&amp;width='.$w.'&amp;height='.$h.'&amp;cropratio='.$w.':'.$h;
		}
		return $src;
	}
	
	function get_post_thumbnail_info($pid){
		$tid = get_post_thumbnail_id($pid);
		return get_post_info($tid);
	}
	
	function get_resized_image($src, $w, $h = 0){
		if (isset($w) && $h == 0){
			$base = basename($src);
			$src = '/wp-content/image.php/'.$base.'?image='.$src.'&amp;width='.$w;
		} else if (isset($w) && isset($h)){
			$base = basename($src);
			$src = '/wp-content/image.php/'.$base.'?image='.$src.'&amp;width='.$w.'&amp;height='.$h.'&amp;cropratio='.$w.':'.$h;
		}
		return $src;
	}


	
	function get_post_info($pid){
		$post = get_post($pid);
		$post->title = $post->post_title;
		$post->body = $post->post_content;
		$post->excerpt = $post->post_excerpt;
		$post->slug = $post->post_name;
		$post->custom = get_post_custom($pid);
		foreach($post->custom as $key => $value){
			$post->$key = $value[0];
		}
		return $post;
	}
	
	function get_post_title($pid){
		$post = get_post_info($pid);
		return $post->post_title;
	}
	
	function get_slug($pid){
		$post_obj = get_post_info($pid);
		return $post_obj->post_name;	
	}
	
	function struncate($string, $max = 20, $rep = '') { 
		if (strlen($string) <= ($max + strlen($rep))) { 
			return $string; 
		} 
		$leave = $max - strlen ($rep); 
		return substr_replace($string, $rep, $leave); 
	} 
	
	function tag_truncate($text, $length, $suffix = '&hellip;', $isHTML = true){
		$i = 0;
		$simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
		$tags = array();
		if($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach($m as $o){
				if($o[0][1] - $i >= $length)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				// test if the tag is unpaired, then we mustn't save them
				if($t[0] != '/' && (!isset($simpleTags[$t])))
					$tags[] = $t;
				elseif(end($tags) == substr($t, 1))
					array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}
		
		// output without closing tags
		$output = substr($text, 0, $length = min(strlen($text),  $length + $i));
		// closing tags
		$output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
		
		// Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
		$pos = (int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
		// Append closing tags to output
		$output.=$output2;

		// Get everything until last space
		$one = substr($output, 0, $pos);
		// Get the rest
		$two = substr($output, $pos, (strlen($output) - $pos));
		// Extract all tags from the last bit
		preg_match_all('/<(.*?)>/s', $two, $tags);
		// Add suffix if needed
		$op = array();
		$op['over'] = false;
		if (strlen($text) > $length) { 
			//$one .= $suffix; 
			$op['over'] = true;
		}
		// Re-attach tags
		$output = $one . implode($tags[0]);

		//added to remove  unnecessary closure
		$output = str_replace('</!-->','',$output); 
		
		$op['body'] = $output;
		return $op;
	}
	
	function truncate($string, $limit, $break=".", $pad="..."){
		// return with no change if string is shorter than $limit
		if (strlen($string) <= $limit) return $string;
		// is $break present between $limit and the end of the string?
		if (false !== ($breakpoint = strpos($string, $break, $limit))) {
			if($breakpoint < strlen($string) - 1) {
				$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
		return $string;
	}
	
	function get_template_name(){
		foreach ( debug_backtrace() as $called_file ) {
			foreach ( $called_file as $index ) {
				if ( !is_array($index[0]) AND strstr($index[0],'/themes/') AND !strstr($index[0],'footer.php') ) {
					$template_file = $index[0] ;
				}
			}
		}
		$template_contents = file_get_contents($template_file) ;
		preg_match_all("(Template Name:(.*)\n)siU",$template_contents,$template_name);
		$template_name = trim($template_name[1][0]);
		if ( !$template_name ) { $template_name = '(default)' ; }
		$template_file = array_pop(explode('/themes/', basename($template_file)));
		return $template_file . ' > '. $template_name ;
	}

	add_filter('body_class', 'my_body_class', 10, 4);


	function my_body_class($classes, $class, $comment_id=0, $post_id=0) {
		if (is_page()) {
			$classes[] = sanitize_title_with_dashes(get_the_title($post_id));
		}
		$uri = $_SERVER["REQUEST_URI"];
		$dir = explode('/', $uri);
		$dir = $dir[1];
		if ($dir){
			$classes[] = 'section-'.$dir;
		}
		return $classes;
	}
	
	function email_to_nickname($email){
		if (strpos($email, '@')){
			$parts = explode('@',$email); 
			$email = $parts[0]; 
		}
		return $email;
	}
	
	function upstatement_comment($comment, $args, $depth){ 
		$GLOBALS['comment'] = $comment;
		echo '<li ';
		comment_class();
		echo ' id="li-comment-'.get_comment_ID().'">';
		echo '<div id="comment-'.get_comment_ID().'" class="clearfix">';
		echo '<div class="comment-col-meta">';
  
  
  		/* IMAGE SHIT */
		echo '<div class="comment-author vcard">';
		$comment_uid = $comment->user_id;
		$fbuid = get_user_meta($comment_uid, 'fbid');
		if ($fbuid){
    	//echo get_avatar($comment,$size='48',$default='https://graph.facebook.com/'.$fbuid[0].'/picture' );
			echo '<img src="https://graph.facebook.com/'.$fbuid[0].'/picture" class="avatar-facebook" />';
		} else {
			echo get_avatar($comment,$size='60');
		}
		echo '</div>';
  		
  		
  
  		/* COMMENTOR NAME SHIT */
		//print_r($comment);
  	 	echo '<div class="comment-author data">';
		//printf(__('<cite class="fn">%s</cite>'), get_comment_author_link());
    	echo '<strong>',email_to_nickname(get_user_meta($comment->user_id, 'nickname', true)),'</strong>';
		if ($comment->comment_approved == '0'){
			echo '<br /><em>Your comment is awaiting moderation</em>';
			echo  '<br />';
		}
		echo '<div class="comment-meta commentmetadata small-gray">';
		printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
		//edit_comment_link(__('(Edit)'),'  ','');
		echo '</div>';
		echo '</div>'; // end meta text column
		
		
	
		echo '</div>'; // end meta column;
		echo '<div class="comment-col-content">';
		comment_text();
		echo '</div>';
		echo '<div class="reply">';
       comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
		echo '</div> </div>';
	}
	




/* CLASS SETTER
=======================*/

$ups_classes;
$ups_classes = array();

//Add user admin when an admin is logged-in

function set_classes($tag, $class){
	/*if (!isset($ups_classes[$tag])){
		$ups_classes[$tag] = array();
	}*/
	global $ups_classes;
	$ups_classes[$tag] .= ' '.$class;
}

function get_classes($tag){
	global $ups_classes;
	return $ups_classes[$tag];
}

function echo_classes($tag){
	echo get_classes($tag);
}


function my_body_class_names($classes, $class=null) {
	$classes[] = get_classes('body');
	return $classes;
}

function get_user_by_nicename($nn){
	global $wpdb;
	$q = "SELECT * FROM wp_users WHERE user_nicename = '$nn'";
	$row = $wpdb->get_row($q);
	$user = get_userdata($row->ID);
	return $user;
	
}
