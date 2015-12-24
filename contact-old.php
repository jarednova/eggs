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
	
	echo '<form id="contact" method="POST" action="'.get_template_directory_uri().'/send-email.php">';
	
	echo '<input type="hidden" id="subject" value="'.$cats->name.'" />';
	?>
	
	<div class="split-50-50">
		<div>
			
			<input type="text" name="name" id="name" placeholder="Your Name" class="simple-input contact-input"/>
			<label class="contact-label">Your name</label>
			
		</div>
		<div>
			
			<input type="email" name="email" id="email" placeholder="your@email.com" class="simple-input contact-input" />
			<label class="contact-label">Your Email</label>
			
		</div>
	</div>
	<textarea id="comment" name="comment" class="simple-input" placeholder="What's up?"></textarea>
	<input type="submit" value="Send" class="fancy-button" id="send-comment"> 
</form>

<div id="contact-thanks" class="oversize-message disp-none">
	<?php
		$pi = get_post_info(183);
		echo $pi->post_content;
	?>
</div>
</section>