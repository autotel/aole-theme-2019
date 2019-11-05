
<?php if(is_user_logged_in()) { ?> <!-- template part: <?php echo dirname(__FILE__).'/'.basename(__FILE__);  ?> --> <?php } ?>
<?php
/*

Single news item. Since the news item is invoked in many places using different queries, I decided to make the singular item include'able.
reference: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/

this is the context where this part should be invoked:

<div class="section-container section-news-container">
	<div class="items-wrapper items-news-wrapper">

	<?php

	postdata must have been set, this include operates using the standard template tags.
	this either happens naturally at an index page, or it can be set by using `setup_postdata($post);`

	//The easiest way:

    $args = array( 'category_name'=> 'News' );
    $posts = get_posts($args);

    foreach ($posts as $post) :
        //it could work without using setup_postdata, in which case the different attributes would be accessed using $post -> <attribute>
		setup_postdata($post);
		include locate_template('includes/lister-new.php');

*/
?>
			<a href="<?php echo get_permalink(); ?>">
				<div class="item-container item-news-container">
					<div class="item-image-container">
						<?php
							$img=get_post_thumbnail_url_or_fallback($post->post_id, 'event-thumbnail', 'news_item');
						?>
						<img src="<?php echo $img['src']?>" alt="<?php echo $img['alt']?>">
					</div>
					<div class="item-title-container">
						<?php the_title(); ?>
					</div>
					<div class="item-paragraph-container" style="position:relative; top:0px; left:0px; opacity:0.5">
						<p><?php
							if( has_excerpt() ) {
								the_excerpt();
							}
							?>
						</p>
					</div>
					<span class="item-author-container"><?php the_author(); ?></span>
				</div>
			</a>
