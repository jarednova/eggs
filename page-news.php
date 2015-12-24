<?php
	$context = Timber::get_context();
	$posts = Timber::get_posts(array('post_type' => 'post'));
	$context['menu'] = new TimberMenu();
 	$context['posts'] = $posts;
	Timber::render('index.twig', $context);