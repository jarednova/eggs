<?php

	$cp = get_post_info(get_the_ID());
	$id = get_the_ID();
	//echo $id;
	$cats = wp_get_post_terms($id, 'areas');
	//print_r($cats);
	$tax = array(array('taxonomy' => 'areas', 'field' => 'slug', 'terms' => $cats[0]->slug, 'operator' => 'IN'));
	//print_r($tax);
	$args = array('post_type' => 'testimonials', 'tax_query' =>$tax, 'orderby' => 'rand', 'numberposts' => 1);
	$tests = get_posts($args);
	
	foreach($tests as $test){
		echo '<div class="testimonial featured">';
		$test = get_post_info($test->ID);
		echo '<p class="quote-medium">'.$test->highlight.'</p>';
		echo '<p class="who-what museo-text">'.$test->person_name.', '.$test->person_title.'</p>';
		echo '</div>';
	}
	
	
?>