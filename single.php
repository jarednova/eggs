<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

	$context = Timber::get_context();
	$context['post'] = new TimberPost();
	$context['menu'] = new TimberMenu();
	Timber::render('single.twig', $context);
