

 <?php if(is_user_logged_in()) { ?> <!-- template part: <?php echo dirname(__FILE__).'/'.basename(__FILE__);  ?> --> <?php } ?>
<?php
/*

Single team_member item. Since the team_member item is invoked in many places using different queries, I decided to make the singular item include'able.
reference: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/

this is the context where this part should be invoked:

<div class="section-container section-team_members-list-container">
	<div class="items-wrapper items-team_members-wrapper classifiable-container">

	<?php
	//$team_member contains a post object with all the required properties.
	//The easiest way:

	$team_members = get_team_members("", "4");
	foreach($team_members as $team_member):
*/


?>

<!--
<?php
$team_member->custom_fields = get_fields($team_member->ID);
// print_r($team_member);
?>
-->

			<div class='item-container item-team_member-container'>
				<!-- <a class="item-paragraph-container" href="<?php echo get_the_permalink($team_member->ID);?>"> -->
					<div class="post-image-container image-container">
            
            <?php
                $img=get_post_thumbnail_url_or_fallback($team_member->ID, 'large', 'team_member');
            ?>
            <img src="<?php echo $img['src']?>" alt="<?php echo $img['alt']?>">

					</div>
          <span class="post-title-container">
						<?php echo $team_member->post_title; ?>
					</span>
          <span class="member-title-container">
						<?php echo $team_member->custom_fields["title"]; ?>
					</span>
          <span class="contact-info-container">
						<?php
            //make it a tiny bit less likely that the email gets collected by spambots
            echo str_ireplace('@',"&#64;",
                  str_ireplace('.',"&#46;",$team_member->custom_fields["contact_info"])
            );
            ?>
					</span>
          <span class="items-wrapper items-social-media-wrapper">
						<?php
            foreach($team_member->custom_fields["social_media"] as $k=>$item){
              $item=$item['social_media_link'];
              // print_r($item);
              echo '<a href="'
                .$item['url']
                .'" target="'
                .$item['target']
                .'" class="item-container item-social-media-link-container">'
                .$item['title']
                .'</a>';
            }
            ?>
					</span>

			</div>
