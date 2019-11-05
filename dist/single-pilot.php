<?php

get_header(); ?>
 <?php if(is_user_logged_in()) { ?> <!-- template part: <?php echo dirname(__FILE__).'/'.basename(__FILE__);  ?> --> <?php } ?>

<?php while (have_posts()):the_post();

  $extra_fields=get_fields();

  function printCustomFields($displayfields/*[$title=>$fieldSlug]*/){
    global $extra_fields;
    foreach ($displayfields as $title => $fieldSlug) {
      if ($extra_fields[$fieldSlug]) {
         echo '<div class="item-' . $fieldSlug . '-field-container">';
         echo '  <h2 class="field-title">' . $title . '</h2>';
         // echo '  <p class="content">';
         echo apply_filters('the_content',$extra_fields[$fieldSlug]);
         // echo '  </p>';
         echo '</div>';
      }
      //just so that it doesn't appear again.
      unset($extra_fields[$fieldSlug]);
    }
   }

   ?>
   <div class="section-container section-post-header-container">
        <p class="item-post-type-container">Pilot case</p>
        <h1 class="item-title-container"><?php the_title(); ?></h1>
        <span class="item-subtitle-container">
         <?php echo $extra_fields['subtitle'];?>
        </span>
        <div class="item-tags-container">
          <?php

          $mod = get_theme_mod('pilots_list_settings');
          $taxes = Array(
            "year",
            "school",
            'theme_group',
          );



          if($mod["cat_tags"]){
  					$taxes=preg_split("/, */",$mod["cat_tags"]);
  				}
          $url=get_site_url()."/pilots";

          //*1 tag links generation
          foreach($taxes as $term_name=>$term_slug){
            $term_name = strtolower( get_taxonomy($term_slug)->label );
            $terms=get_the_terms( $pilot->ID , $term_slug );

             // print_r($tags);
             foreach ($terms as $key => $term) {
               echo '<a href="'
                 .$url.'#'.$term_name.'/'.$term->name
                 .'" class="tag '.$tax.'">'//'.$tax->slug.'
                    .$term->name
               .'</a>';
             }
           }


           $tags = get_the_tags();
           // print_r($tags);
           foreach ($tags as $key => $tag) {
             echo '<span class="tag tag-'.$tag->slug.'">'.$tag->name.'</span>';
           }

           ?>
       </div>
       <?php if(has_post_thumbnail()){ ?>
        <div <?php post_class('item-post-thumbnail-container')?>>
          <?php
            $img=get_post_thumbnail_url_or_fallback(get_the_ID(),"full", 'pilot');
          ?>
          <img src="<?php echo $img['src']?>" alt="<?php echo $img['alt']?>" class="post-thumbnail"/>
        </div>
       <?php } ?>
   </div>
   <div class="section-container section-post-container">
       <div class="item-side-data-container">
         <!--add a "side-data" field to the post to make it appear here-->
           <?php
           if(!$extra_fields['school']){
             //inject "schools" custom tax field into the extra fields
             $cb = function($value) {
                return $value->name;
            };
             // print_r(array_map($cb,get_the_terms( $pilot->ID , "school", "array" )));
             $extra_fields['school']=implode(", ",array_map($cb,get_the_terms( $pilot->ID , "school", "array" )));
           }
           printCustomFields(array(
               "Pilot leader" => "pilot_leader",
               "Schools" => "school",
               "Reach" => "reach",
               "Timeline" => "timeline",
           ));

           ?>
       </div>
       <div class="item-post-content-container">
         <!--//use field name, rename it to overview (currently looks like description-->
         <!--
         tools, exists but rename it as "plaftorm and tools"
         pedagogical methods, exists. rename it to "pedagogical methods"
         involved courses, doesnt exist
         people, exists.


          -->
         <?php
         //some post types may not be using the post content field. In that case, let's take the first content field available
         $contentDisplay= the_content();
         if(!empty($contentDisplay)){
           ?>
           <?php
           echo $contentDisplay;
         }
         ?>
         <?php edit_post_link('Edit', '<span class="edit-link">', '</span>');?>
       <!--
       if you add new fields to the post, they will appear here as sections.
       (some fields are removed because they are not meant to appear as content.)
       -->
       <?php

       //if you were to print all the remaining fields:
       //
       // except...
       // unset($extra_fields['color']);
       // unset($extra_fields['subtitle']);
       // unset($extra_fields['header-background-picture']);
       //
       // $remaining_fields=array();
       //
       // foreach($extra_fields as $slug => $field){
       //   if($field && is_string($field)){
       //     $fo=get_field_object($slug);
       //     $remaining_fields[$fo['label']]=$slug;
       //   }
       // }
       // printCustomFields($remaining_fields);

       $bodyFields=array();
       //define which other fields to print and in what order
       foreach(array(
         "description",
         "tools_used",
         "pedagogical_methods_used",
         "involved_courses",
         "links_materials",
         "reflection",
         "people") as  $slug){
           //get the title of each field
         $fo=get_field_object($slug);
         $bodyFields[$fo['label']]=$slug;
       }
       //print that list of fields.
       printCustomFields($bodyFields);

       ?>
     </div>
   </div>

  <?php if(false && is_user_logged_in()){ ?>
     <div class="section container section-dev-container">
         <h2>Fields:</h2>
         <textarea style="width:100%; height:300px">
             <?php print_r( $extra_fields ) ?>
         </textarea>

         <h2>Post:</h2>
         <textarea style="width:100%; height:300px">
             <?php print_r( get_post() ) ?>
         </textarea>
     </div>
  <?php } ?>
  <div class="items-wrapper items-other-posts-wrapper">
    <?php
    $pilots = array();
    $current_post = $post;
    /*
    acquiring next and previous post here is programmed a bit oddly; this is
    because as far as I know, there can only be one global $post to which the
    setup_postdata function can refer to. This means that we cannot evaluate
    whether there are sufficient next and previous posts available before
    actually trying to get them.
    The lightest way of getting the four available contiguous posts I thought,
    is to get four previous posts, and then two additional next posts. In case
    there aren't two next posts available, it will use prev posts to fill that
    gap in order to always offer four contiguous posts. There is always a waste
    of two queries except when viewing the last post.
    */
    // echo "<ul>";
    $i=0;
    //get four previous posts
    for($i = 0; $i < 4; $i++){
      $post = get_previous_post();
      if($post){
        setup_postdata($post);
        array_unshift($pilots,$post);
        // echo("<li>prev:get".$post->post_title);
      }else{
        // echo "<li>prev:none";
        break;
      }
    }
    wp_reset_postdata();
    //get next two posts if there were enough previous posts, get more if
    // there were less than enough.
    // echo("<li>next, from 0 to".(max(2,4-$i)));
    for($j=0; $j < max(2,4-$i); $j++){
      $post = get_next_post();
      // echo("<li>next[".$j."]:get".$post->post_title);
      if(!$post){ break; }
      setup_postdata($post);
      array_push($pilots,$post);

    }

    //get only the last four posts in the list
    $pilots=array_slice($pilots,-4);

    wp_reset_postdata();
    // echo "</ul>";


    $append_before = '<hr/><h2>More pilots</h2>';
    $append_before .= '<p class="pilots-subtitle"><!-- get_field(pilots_showcase_section)[pilots_subtitle] -->' . $section['pilots_subtitle'] . '</h2>';
    $append_after = '<a href="'.get_site_url().'/pilots" class="fa-long-arrow-left button-list"> Back to pilots list</a>';
    //$append_before & after are appended in the following included template part:
    include locate_template('includes/lister-pilots.php');

    $append_before = null;
    $append_after = null;
    ?>
 </div>


<?php endwhile;?>


<div class="section-container section-pilot-after-content-container">
  <hr/>
  <?php
  $append = get_page_by_path('pilots-after-content');
  print_r($append->post_content);
  ?>
</div>
<?php get_sidebar(); ?>

<?php get_footer();
