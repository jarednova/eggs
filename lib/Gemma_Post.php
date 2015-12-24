<?php

	class Gemma_Post extends TimberPost {

		public function testimonials() {
			$cats = wp_get_post_terms($this->ID, 'areas');
			//print_r($cats);
			$tax = array(array('taxonomy' => 'areas', 'field' => 'slug', 'terms' => $cats[0]->slug, 'operator' => 'IN'));
			//print_r($tax);
			$args = array('post_type' => 'testimonials', 'tax_query' =>$tax, 'orderby' => 'rand');
			$tests = Timber::get_posts($args);
			return $tests;
		}

		public function featured_testimonial() {

			$cats = wp_get_post_terms($this->ID, 'areas');
			//print_r($cats);
			$tax = array(array('taxonomy' => 'areas', 'field' => 'slug', 'terms' => $cats[0]->slug, 'operator' => 'IN'));
			//print_r($tax);
			$args = array('post_type' => 'testimonials', 'tax_query' =>$tax, 'orderby' => 'rand', 'numberposts' => 1);
			return Timber::get_post($args);
		}

	}


	