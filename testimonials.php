<?php
	$cp = get_post_info(get_the_ID());
	$id = get_the_ID();
	
	
	//echo $id;
	$cats = wp_get_post_terms($id, 'areas');
	//print_r($cats);
	$tax = array(array('taxonomy' => 'areas', 'field' => 'slug', 'terms' => $cats[0]->slug, 'operator' => 'IN'));
	//print_r($tax);
	$args = array('post_type' => 'testimonials', 'tax_query' =>$tax, 'orderby' => 'rand');
	$tests = get_posts($args);
	
	if (!count($tests)){
		return;
	}
	
	if ($id == 10){
		echo '<h4 class="banner">Reviews</h4>';
	}  else {
		echo '<h4 class="banner">Testimonials</h4>';
	}
	
	foreach($tests as $test){
		echo '<div class="testimonial">';
		$test = get_post_info($test->ID);
		if (is_front_page()){
			echo '<p class="quote-big">'.$test->highlight.'</p>';
		} else {
			echo '<p class="quote-big">'.$test->post_content.'</p>';
		}
		echo '<p class="who museo-text">'.$test->person_name.'</p>';
		echo '<p class="what museo-text">'.$test->person_title.'</p>';
		echo '</div>';
	}
	
	
?>