<section id="get-in-touch">
<?php

	


	$cats = wp_get_post_terms($id, 'areas');
	$cat = $cats[0];

	if (is_front_page() || true){
		echo '<h4 class="banner">Get in Touch</h4>';	
	} else {
		echo '<h4 class="banner">Get in Touch about '.$cat->name.'</h4>';	
	}
	
	echo '<p class="verdana-text">'.get_post_info(128)->post_content.'</p>';
	
	echo do_shortcode('[contact-form-7 id="335" title="Get in Touch"]');
?>
</section>