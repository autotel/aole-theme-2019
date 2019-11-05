

<?php
/*

Single pilot item. Since the pilot item is invoked in many places using different queries, I decided to make the singular item include'able.
reference: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/

this is the context where this part should be invoked:

<div class="section-container section-pilots-list-container">
	<div class="items-wrapper items-pilots-wrapper classifiable-container">

	<?php
	//$pilot contains a post object with all the required properties.
	//The easiest way:

  $pilots = get_posts(
      array('showposts' => 4,
          'post_type' => 'pilot',
      )
  );
	foreach($pilots as $pilot):
*/

?>

			<div class='item-container item-pilot-container classifiable-item'>
        <?php

        //here you select which post taxonomies are used for classification in the categorizer.
        //the selected terms need to work with "get_the_terms()"
				$mod = get_theme_mod('pilots_list_settings');

        $taxes = Array(
          "year",
          "school",
          'theme_group',
        );
				if($mod["cat_tags"]){
					$taxes=preg_split("/, */",$mod["cat_tags"]);
				}
        foreach($taxes as $term_slug){
					//TODO: this should happen once at the start instead of for every pilot.
					//the acquired data could be appended to the $mod
					$term_name = strtolower( get_taxonomy($term_slug)->label );
          ?>
          <ul class="classifiable-attributes" name="<?php echo $term_name ?>" slug="<?php echo $term_slug ?>">
            <?php
            $terms=get_the_terms( $pilot->ID , $term_slug );
            // echo "<!--";
            // print_r($terms);
						// print_r();
            // echo "-->";
            //this selects which non-hierarchical metadata to show.
            //this affects the categorizer: it categorizes items according to what is displayed here.
            foreach ($terms as $key=>$term) {
  						echo '<li key="'.$key.'" name="'.$term->name.'" slug="'.$term->slug.'">'.$term->name.'</li>';
  					}

  		      ?>
          </ul>
          <?php
        }
        ?>

  			<a class="item-paragraph-container" href="<?php echo get_the_permalink($pilot->ID);?>">
  				<div class="post-image-container">
            <?php
            $img=get_post_thumbnail_url_or_fallback($pilot->ID, 'medium', 'pilot');
            ?>
            <img src="<?php echo $img['src']?>" alt="<?php echo $img['alt']?>">
  				</div>
  				<div class="post-title-container">
  					<?php
            echo $pilot->post_title;
            ?>
  				</div>
          <!--
          <?php
          // print_r($pilot);
          // print_r("-----------");
          // print_r(  wp_get_post_terms($pilot->ID,"post_tag") );
          ?>
          -->
  			</a>

			</div>
