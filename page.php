<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

$post = new Gemma_Post();
$context = Timber::get_context();
$context['menu'] = new TimberMenu();
$context['post'] = $post;
Timber::render('page.twig', $context);
return;

get_header(); 

function fullescape($in) 
{ 
  $out = ''; 
  for ($i=0;$i<strlen($in);$i++) 
  { 
    $hex = dechex(ord($in[$i])); 
    if ($hex=='') 
       $out = $out.urlencode($in[$i]); 
    else 
       $out = $out .'%'.((strlen($hex)==1) ? ('0'.strtoupper($hex)):(strtoupper($hex))); 
  } 
  $out = str_replace('+','%20',$out); 
  $out = str_replace('_','%5F',$out); 
  $out = str_replace('.','%2E',$out); 
  $out = str_replace('-','%2D',$out); 
  return $out; 
 } 

?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); $post_info = get_post_info(get_the_ID());?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( is_front_page() ) { ?>
					
				<?php } else {
					get_template_part('testimonials', 'featured');
					$gal = get_post_gallery(get_the_ID());
					if ($post_info->fimage && !$gal){
						echo '<div class="fimage">';
						echo '<img src="'.get_resized_image($post_info->fimage, 610, 300).'" />';
						if ($post_info->fcaption){
							echo '<p class="fcaption caption">'.$post_info->fcaption.'</p>';
						}
						echo '</div>';
					}
					
					if ($gal){
						echo '<section class="cycler">';
						foreach($gal as $img){
							//print_r($img);
							echo '<img class="cycle-image" src="'.get_resized_image($img->guid, 610, 300).'" alt="'.fullescape(addslashes($img->post_excerpt)).'">';
						}
						echo '</section>';
						echo '<div id="gallery-caption" class="lucida-text"></div>';
					}
					echo '<h1 class="entry-title">'.get_the_title().'</h1>';
					if ($post_info->intro){
						echo '<p class="oversize inside">'.$post_info->intro.'</p>';
					}
				} ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php
							if (is_front_page()){
								echo '<section class="testimonials-carousel">';
								get_template_part('testimonials');
								echo '</section>';
							} else {
								get_template_part('testimonials');
							}
							
							
							
							get_template_part('contact');
						?>
						<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->

<?php endwhile; ?>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>