<?php
// define( 'WP_DEBUG', true );
function maintenace_mode() {

      if ( !is_user_logged_in() ) {
        // include(locate_template('maintenance'));
        wp_die('

        <h1>Aalto Online Learning</h1>
        <p>We are currently renovating the website. Come back soon to see it tomorrow!</p>
        ');
        // die();
      }

}
// add_action('get_header', 'maintenace_mode');


include locate_template("customizer/index.php");

/** Various clean up functions */

$fullWPpath = '/Users/taipala2/Documents/OneDrive - Aalto-yliopisto/Web Projects/htdocs/wordpress';

//add featured image to pages too
add_theme_support('post-thumbnails');
add_theme_support('post-thumbnails', array('post', 'page'));


// require_once( 'library/cleanup.php' );

/** Register all navigation menus */
// require_once( 'library/navigation.php' );

/** Create widget areas in sidebar and footer */
// require_once( 'library/widget-areas.php' );

/** Return entry meta information for posts */
// require_once( 'library/entry-meta.php' );

/** Enqueue scripts */
require_once( 'library/enqueue-scripts.php' );

/** Add theme support */
// require_once( 'library/theme-support.php' );

/** Add Nav Options to Customer */
// require_once( 'library/custom-nav.php' );

/** Change WP's sticky post class */
// require_once( 'library/sticky-posts.php' );

/** Configure responsive image sizes */
require_once( 'library/responsive-images.php' );

/* Write to log */

if (!function_exists('write_log')) {
    function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

///////Custom post types start


function cptui_register_my_cpts() {

    /**
     * Post Type: Pilots.
     */

    $labels = array(
        "name" => __( "Pilots", "" ),
        "singular_name" => __( "Pilot", "" ),
        "menu_name" => __( "Pilots", "" ),
        "all_items" => __( "All pilots", "" ),
        "add_new" => __( "Add new", "" ),
        "add_new_item" => __( "Add new Pilot", "" ),
        "edit_item" => __( "Edit Pilot", "" ),
        "new_item" => __( "New Pilot", "" ),
        "view_item" => __( "View Pilot", "" ),
        "view_items" => __( "View Pilots", "" ),
        "search_items" => __( "Search Pilot", "" ),
        "not_found" => __( "No Pilots found", "" ),
        "not_found_in_trash" => __( "No Pilots found in Trash", "" ),
        "parent_item_colon" => __( "Parent Pilot:", "" ),
        "featured_image" => __( "Featured image for this Pilot", "" ),
        "set_featured_image" => __( "Set featured image for this Pilot", "" ),
        "remove_featured_image" => __( "Remove featured image for this Pilot", "" ),
        "use_featured_image" => __( "Use as featured image for this Pilot", "" ),
        "archives" => __( "Pilot archives", "" ),
        "insert_into_item" => __( "Insert into Pilot", "" ),
        "uploaded_to_this_item" => __( "Uploaded to this Pilot", "" ),
        "filter_items_list" => __( "Filter Pilots list", "" ),
        "items_list_navigation" => __( "Pilots list navigation", "" ),
        "items_list" => __( "Pilots list", "" ),
        "attributes" => __( "Pilots attributes", "" ),
        "parent_item_colon" => __( "Parent Pilot:", "" ),
        );

    $args = array(
        "label" => __( "Pilots", "" ),
        "labels" => $labels,
        "description" => "Aalto Online Learning pilot project.",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "pilot",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "pilot", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "thumbnail", "author"),
        'taxonomies' => array('post_tag','theme_group','year','school')
        );

    register_post_type( "pilot", $args );

    /**
     * Post Type: Team Members.
     */

    $labels = array(
        "name" => __( "Team Members", "" ),
        "singular_name" => __( "Team Member", "" ),
        "menu_name" => __( "Team Members", "" ),
        "all_items" => __( "All Team Members", "" ),
        "add_new" => __( "Add new", "" ),
        "add_new_item" => __( "Add new Team Member", "" ),
        "edit_item" => __( "Edit Team Member", "" ),
        "new_item" => __( "New Team Member", "" ),
        "view_item" => __( "View Team Member", "" ),
        "view_items" => __( "View Team Members", "" ),
        "search_items" => __( "Search Team Member", "" ),
        "not_found" => __( "No Team Members found", "" ),
        "not_found_in_trash" => __( "No Team Members found in Trash", "" ),
        "featured_image" => __( "Featured image for this Team Member", "" ),
        "set_featured_image" => __( "Set featured image for this Team Member", "" ),
        "remove_featured_image" => __( "Remove featured image for this Team Member", "" ),
        "use_featured_image" => __( "Use as featured image for this Team Member", "" ),
        "archives" => __( "Team Member archives", "" ),
        "insert_into_item" => __( "Insert into Team Member", "" ),
        "uploaded_to_this_item" => __( "Uploaded to this Team Member", "" ),
        "filter_items_list" => __( "Filter Team Members list", "" ),
        "items_list_navigation" => __( "Team Members list navigation", "" ),
        "items_list" => __( "Team Members list", "" ),
        "attributes" => __( "Team Members attributes", "" ),
        );

    $args = array(
        "label" => __( "Team Members", "" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "team_member",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "team_members", "with_front" => true ),
        "query_var" => true,
        'supports' => array('title', 'editor', 'thumbnail')
        );

    register_post_type( "team_members", $args );

    /**
     * Post Type: Quotes.
     */

    // $labels = array(
    //     "name" => __( "Quotes", "" ),
    //     "singular_name" => __( "Quote", "" ),
    //     "menu_name" => __( "Quotes", "" ),
    //     "all_items" => __( "All Quotes", "" ),
    //     "add_new" => __( "Add new", "" ),
    //     "add_new_item" => __( "Add new Quote", "" ),
    //     "edit_item" => __( "Edit Quote", "" ),
    //     "new_item" => __( "New Quote", "" ),
    //     "view_item" => __( "View Quote", "" ),
    //     "view_items" => __( "View Quotes", "" ),
    //     "search_items" => __( "Search Quote", "" ),
    //     "not_found" => __( "No Quotes found", "" ),
    //     "not_found_in_trash" => __( "No Quotes found in Trash", "" ),
    //     "parent_item_colon" => __( "Parent Quote:", "" ),
    //     "featured_image" => __( "Featured image for this Quote", "" ),
    //     "set_featured_image" => __( "Set featured image for this Quote", "" ),
    //     "remove_featured_image" => __( "Remove featured image for this Quote", "" ),
    //     "use_featured_image" => __( "Use as featured image for this Quote", "" ),
    //     "archives" => __( "Quote archives", "" ),
    //     "insert_into_item" => __( "Insert into Quote", "" ),
    //     "uploaded_to_this_item" => __( "Uplaaded to this Quote", "" ),
    //     "filter_items_list" => __( "Filter Quotes list", "" ),
    //     "items_list_navigation" => __( "Quotes list navigation", "" ),
    //     "items_list" => __( "Quotes list", "" ),
    //     "attributes" => __( "Quotes Attributes", "" ),
    //     "parent_item_colon" => __( "Parent Quote:", "" ),
    //     );

    // $args = array(
    //     "label" => __( "Quotes", "" ),
    //     "labels" => $labels,
    //     "description" => "Quotes said by other people.",
    //     "public" => true,
    //     "publicly_queryable" => true,
    //     "show_ui" => true,
    //     "show_in_rest" => false,
    //     "rest_base" => "",
    //     "has_archive" => false,
    //     "show_in_menu" => true,
    //     "exclude_from_search" => true,
    //     "capability_type" => "quote",
    //     "map_meta_cap" => true,
    //     "hierarchical" => false,
    //     "rewrite" => array( "slug" => "quotes", "with_front" => true ),
    //     "query_var" => true,
    //     "supports" => array( "title" ),
    //     "taxonomies" => array( "theme_group"),
    //     );
    //
    // register_post_type( "quotes", $args );

    /**
     * Post Type: Jobs.
     */

    $labels = array(
        "name" => __( "Jobs", "" ),
        "singular_name" => __( "Job", "" ),
        "menu_name" => __( "Jobs", "" ),
        "all_items" => __( "All Jobs", "" ),
        "add_new" => __( "Add new", "" ),
        "add_new_item" => __( "Add new Job", "" ),
        "edit_item" => __( "Edit Job", "" ),
        "new_item" => __( "New Job", "" ),
        "view_item" => __( "View Job", "" ),
        "view_items" => __( "View Jobs", "" ),
        "search_items" => __( "Search Job", "" ),
        "not_found" => __( "No Jobs Found", "" ),
        "not_found_in_trash" => __( "No Jobs found in Trash", "" ),
        "parent_item_colon" => __( "Parent Job", "" ),
        "featured_image" => __( "Featured image for this Job", "" ),
        "set_featured_image" => __( "Set featured image for this Job", "" ),
        "remove_featured_image" => __( "Remove featured image for this Job", "" ),
        "use_featured_image" => __( "Use as featured image for this Job", "" ),
        "archives" => __( "Job archives", "" ),
        "insert_into_item" => __( "Insert into Job", "" ),
        "uploaded_to_this_item" => __( "Uploaded to this Job", "" ),
        "filter_items_list" => __( "Filter Jobs list", "" ),
        "items_list_navigation" => __( "Jobs list navigation", "" ),
        "items_list" => __( "Jobs list", "" ),
        "attributes" => __( "Jobs attributes", "" ),
        "parent_item_colon" => __( "Parent Job", "" ),
        );

    $args = array(
        "label" => __( "Jobs", "" ),
        "labels" => $labels,
        "description" => "Pilots sometimes have open positions for jobs, this is for those.",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "job",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "jobs", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail" ),
        );

    register_post_type( "jobs", $args );

    /**
     * Post Type: Online Learning Tools.
     */

    $labels = array(
        "name" => __( "Online Learning Tools", "" ),
        "singular_name" => __( "Online Learning Tool", "" ),
        "menu_name" => __( "Online Learning Tools", "" ),
        "all_items" => __( "All Online Learning Tools", "" ),
        "add_new" => __( "Add new", "" ),
        "add_new_item" => __( "Add new Online Learning Tool", "" ),
        "edit_item" => __( "Edit Online Learning Tool", "" ),
        "new_item" => __( "New Online Learning Tool", "" ),
        "view_item" => __( "View Online Learning Tool", "" ),
        "view_items" => __( "View Online Learning Tools", "" ),
        "search_items" => __( "Search Online Learning Tool", "" ),
        "not_found" => __( "No Online Learning Tools found", "" ),
        "not_found_in_trash" => __( "No Online Learning Tools found in Trash", "" ),
        "parent_item_colon" => __( "Parent Online Learning Tool:", "" ),
        "featured_image" => __( "Featured image for this Online Learning Tool", "" ),
        "set_featured_image" => __( "Set featured image for this Online Learning Tool", "" ),
        "remove_featured_image" => __( "Remove featured image for this Online Learning Tool", "" ),
        "use_featured_image" => __( "Use as featured image for this Online Learning Tool", "" ),
        "archives" => __( "Online Learning Tool archives", "" ),
        "insert_into_item" => __( "Insert into Online Learning Tool", "" ),
        "uploaded_to_this_item" => __( "Uploaded to this Online Learning Tool", "" ),
        "filter_items_list" => __( "Filter Online Learning Tools list", "" ),
        "items_list_navigation" => __( "Online Learning Tools list navigation", "" ),
        "items_list" => __( "Online Learning Tools list", "" ),
        "attributes" => __( "Online Learning Tools attributes", "" ),
        "parent_item_colon" => __( "Parent Online Learning Tool:", "" ),
        );

    $args = array(
        "label" => __( "Online Learning Tools", "" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "online_learning_tool",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "online_learning_tool", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "thumbnail" ),
        );

    register_post_type( "online_learning_tool", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

function cptui_register_my_taxes() {

    /**
     * Taxonomy: Theme Groups.
     */

    $labels = array(
        "name" => __( "Theme Groups", "" ),
        "singular_name" => __( "Theme Group", "" ),
        );

    $args = array(
        "label" => __( "Theme Groups", "" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => false,
        "label" => "Theme Groups",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'theme_group', 'with_front' => true, ),
        "show_admin_column" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => false,
        'capabilities' => array(
            'manage_terms' => 'manage_theme_group',
            'edit_terms' => 'edit_theme_group',
            'delete_terms' => 'delete_theme_group',
            'assign_terms' => 'assign_theme_group',
            )
        );
    register_taxonomy( "theme_group", array( "pilot" ), $args );


    /**
    * taxonomy: year
    */
    $labels = array(
        "name" => __( "Years", "" ),
        "singular_name" => __( "Year", "" ),
        );

    $args = array(
        "label" => __( "Years", "" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => false,
        "label" => "Years",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'year', 'with_front' => true, ),
        "show_admin_column" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => false,
        'capabilities' => array(
            'manage_terms' => 'manage_post_tags',
            'edit_terms' => 'edit_post_tags',
            'delete_terms' => 'delete_post_tags',
            'assign_terms' => 'assign_post_tags',
            )
        );
    register_taxonomy( "year", array( "pilot" ), $args );


    /**
    * taxonomy: school
    */

    $labels = array(
        "name" => __( "Schools", "" ),
        "singular_name" => __( "School", "" ),
        );

    $args = array(
        "label" => __( "Schools", "" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => false,
        "label" => "Schools",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'school', 'with_front' => true, ),
        "show_admin_column" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => false,
        'capabilities' => array(
            'manage_terms' => 'manage_post_tags',
            'edit_terms' => 'edit_post_tags',
            'delete_terms' => 'delete_post_tags',
            'assign_terms' => 'assign_post_tags',
            )
        );
    register_taxonomy( "school", array( "pilot" ), $args );




    /**
     * Taxonomy: Quote Categories.
     */

    $labels = array(
        "name" => __( "Quote Categories", "" ),
        "singular_name" => __( "Quote Category", "" ),
        );

    $args = array(
        "label" => __( "Quote Categories", "" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => false,
        "label" => "Quote Categories",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'quote_category', 'with_front' => true, ),
        "show_admin_column" => false,
        "show_in_rest" => false,
        "rest_base" => "",
        "show_in_quick_edit" => false,
        );
    register_taxonomy( "quote_category", array( "quotes" ), $args );

    //and of course that EM manager has a arbitrarily dedicated function to recover the normal tag taxonomy.
    register_taxonomy_for_object_type('post_tag',EM_POST_TYPE_EVENT);
    register_taxonomy_for_object_type('post_tag','event-recurring');

}

add_action( 'init', 'cptui_register_my_taxes' );




////// Custom post types end




// from https://gist.github.com/brenna/7377802
// Idea being: whenever a new Theme Group post is added, it also adds it to the Theme Group taxonomy (so that they'll stay in sync)

function update_custom_terms($post_id) {

    // only update terms if it's a theme group post
   if ( 'theme_group' != get_post_type($post_id)) {
      return;
  }

    // don't create or update terms for system generated posts
  if (get_post_status($post_id) == 'auto-draft') {
      return;
  }

    /*
    * Grab the post title and slug to use as the new
    * or updated term name and slug
    */
    $term_title = get_the_title($post_id);
    $term_slug = get_post( $post_id )->post_name;

    /*
    * Check if a corresponding term already exists by comparing
    * the post ID to all existing term descriptions.
    */
    $existing_terms = get_terms('theme_group', array(
    	'hide_empty' => false
    	)
    );

    foreach($existing_terms as $term) {
    	if ($term->description == $post_id) {
            //term already exists, so update it and we're done
    		wp_update_term($term->term_id, 'theme_group', array(
    			'name' => $term_title,
    			'slug' => $term_slug
    			)
    		);
    		return;
    	}
    }

    /*
    * If we didn't find a match above, this is a new post,
    * so create a new term.
    */
    wp_insert_term($term_title, 'theme_group', array(
    	'slug' => $term_slug,
    	'description' => $post_id
    	)
    );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_custom_terms');


add_image_size( 'square-large', 300, 300, true); // name, width, height, crop

add_image_size( 'pilot-showcase', 430, 190, true); // name, width, height, crop

add_image_size( 'single-pilot-banner', 1200, 500, true); // name, width, height, crop

add_image_size( 'feed-thumbnail', 500, 180, true); // name, width, height, crop

add_image_size( 'event-thumbnail', 790, 270, true); // name, width, height, crop


// Custom excerpt for feed posts on front page
// Source: https://stackoverflow.com/a/24160854

function wpse_allowedtags() {
    // Add custom tags to this string
    return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>';
}

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) :

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
        global $post;
        $raw_excerpt = $wpse_excerpt;
        if ( '' == $wpse_excerpt ) {

            $wpse_excerpt = get_the_content('');
            $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
            $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
            $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
            //$wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

            //Set the excerpt word count and only break after sentence is complete.
            $excerpt_word_count = 75;
            $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
            $tokens = array();
            $excerptOutput = '';
            $count = 0;

                // Divide the string into tokens; HTML tags, or words, followed by any whitespace
            preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

            foreach ($tokens[0] as $token) {

                if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
                    // Limit reached, continue until , ; ? . or ! occur at the end
                    $excerptOutput .= trim($token);
                    break;
                }

                    // Add words to complete sentence
                $count++;

                    // Append what's left of the token
                $excerptOutput .= $token;
            }

            $wpse_excerpt = trim(force_balance_tags($excerptOutput));

            $excerpt_end = ' <a class="read-more-link" href="'. esc_url( get_permalink() ) . '">Read more...</a>';
            $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

                //$pos = strrpos($wpse_excerpt, '</');
                //if ($pos !== false)
                // Inside last HTML tag
                //$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
                //else
                // After the content
            $wpse_excerpt .= $excerpt_end; /*Add read more in new paragraph */

            return $wpse_excerpt;

        }
        return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

    endif;

    remove_filter('get_the_excerpt', 'wp_trim_excerpt');
    add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');


//Get the excerpt with ID:
//Source: https://wordpress.stackexchange.com/a/12503

    function get_the_excerpt_by_id($post_id) {
      global $post;
      $save_post = $post;
      $post = get_post($post_id);
      $output = get_the_excerpt();
      $post = $save_post;
      return $output;
  }

  function get_the_content_by_id($post_id) {
      $post = get_post($post_id);
      $content_arr = get_extended($post->post_content);
      return apply_filters('the_content', $content_arr['main']);
  }

  function get_all_event_info($em_event) {
    $event_information = [];
    $post_id = $em_event->post_id;
    $post_info = get_post($post_id);
    $em_event->the_permalink = get_permalink($post_id);
    $em_event->custom_fields = get_fields($post_id); //Add all the fields from ACF to the post info object
    $em_event->location = em_get_location($em_event->location_id);
    $em_event->event_categories = wp_get_post_terms( $em_event->post_id, "event-categories");

    // The $event_information variable is divided into two objects, "post" and "event". The former contains the post information (that
    // has been fetched with get_post) and the latter contains the event information (gotten with EM_Events::get())
    $event_information["post"]=$post_info;
    $event_information["event"]=$em_event;


    return $event_information;
}



// Add a custom ACF menu to edit the footer

// function add_theme_settings_page(){
//   if( function_exists('acf_add_options_page') ) {
//
//     acf_add_options_page(array(
//         'page_title'    => 'Theme General Settings',
//         'menu_title'    => 'Theme Settings',
//         'menu_slug'     => 'theme-general-settings',
//         'capability'    => 'edit_theme_settings',
//         'redirect'      => false
//         ));
//
// }
// }


// Enable ACF PRO even in the WP-signup page
add_action ('activate_header', 'load_site_specific_plugin') ;

function load_site_specific_plugin ()
{
    if (wp_installing ()) {
        require_once (WP_PLUGIN_DIR . 'advanced-custom-fields-pro') ;
        }

    return ;
}


add_action( 'init', 'add_theme_settings_page' );

// Add rest api support to events

function wpsd_add_cpt_args() {
    global $wp_post_types;
    // if(! defined("wp_post_types")){
    //     $wp_post_types=[];
    //     $wp_post_types=['event']->Object();
    // }
    $wp_post_types['event']->show_in_rest = true;
    $wp_post_types['event']->rest_base = 'events';
    $wp_post_types['event']->rest_controller_class = 'WP_REST_Posts_Controller';
}
add_action( 'init', 'wpsd_add_cpt_args', 30 );

// Add message to Dashboard

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
    global $wp_meta_boxes;

    wp_add_dashboard_widget('custom_help_widget', 'How to use this theme?', 'custom_dashboard_help');
}

function custom_dashboard_help() {
    echo '<p>Need help? find these help files:
    <ul>
      <li>
        <b>Original guide:</b>
        <a href="https://aalto-online-learning.github.io/aole2017-wordpress-documentation/">
          https://aalto-online-learning.github.io/aole2017-wordpress-documentation/
        </a>
      </li>
      <li>
        <b>New guide:</b>
        <a href="https://onlinelearning.aalto.fi/wp-content/Aole2019-documentation">
          https://onlinelearning.aalto.fi/wp-content/Aole2019-documentation
        </a>
      </li>
    </ul>
    <br><br>
    You\'ll find answer to questions such as:
    <ul>
        <li>- How do I change the content of the front page?</li>
        <li>- How do I change the core team members on the about page?</li>
        <li>- How do I add a picture on this page/post?</li>
    </ul>
    Have fun with the site!
    ';
}

// MAINTENANCE MODE

// Activate WordPress Maintenance Mode
function wp_maintenance_mode(){
    if(!current_user_can('edit_themes') || !is_user_logged_in()){
        wp_die('<h1 style="font-family:Georgia, serif; font-style:italic; font-weight:bold; color:#338FD4;">Website under maintenance</h1><br />We are performing scheduled maintenance. We will be back online shortly!');
    }
}
//add_action('get_header', 'wp_maintenance_mode');
// function my_format_TinyMCE( $in ) {
// 	$in['keep_styles'] = true;
// 	return $in;
// }
// add_filter( 'tiny_mce_before_init', 'my_format_TinyMCE' );
