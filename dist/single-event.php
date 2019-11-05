<?php
/*
part for single posts
*/

get_header(); ?>
<?php if(is_user_logged_in()) { ?> <!-- template part: <?php echo dirname(__FILE__).'/'.basename(__FILE__);  ?> --> <?php } ?>

<?php if(is_user_logged_in()) { ?> <!-- template part: <?php echo dirname(__FILE__).'/'.basename(__FILE__);  ?> --> <?php } ?>


<?php while (have_posts()): the_post();?>
    <div class="section-container section-post-header-container">
        <?php

        /* event posts have three facets in this context:
            * standard wp_post data
                * acquired through normal wp_ functions
            * custom_fields related data
                * acquired using get_field functions
            * EventManager related data
                * will be stored in the $eventManagerData object

        */

        $eventManagerData = EM_Events::get(
            array("scope"=>"all", "post_id"=>get_the_ID())
        )[0];
        $date = new DateTime($eventManagerData->event_start_date);


        ?>

        <div class="item-post-data-container">
        <p class="item-post-type-container">Event</p>
        <h1 class="item-title-container"><?php the_title(); ?></h1>
        <?php
        $facilitators=get_field('facilitators');
        if ($facilitators) {
            ?>
            <ul class="item-facilitators-container item-author-container">
                <?php
                foreach($facilitators as $num => $item){
                    echo '<li>'.$item['facilitator'].'</li>';
                }
                ?>
            </ul>
            <?php
        }
        ?>
        <ul class="items-categories-wrapper">
            <?php foreach($event["event"]->event_categories as $cat){ echo "<li>".$cat->name."</li>"; } ?>
        </ul>

        <?php
        $echo = get_field('subtitle');
        if ($echo) {
            ?>
            <span class="item-subtitle-container">
                <?php
                    $max = 30;
                        $echo = strlen($echo) > $max ? substr($echo, 0, $max) . "..." : $echo;
                        echo $echo;
                        ?>
            </span>
        <?php } ?>
        <div class="item-tags-container">
            <?php
            $tags = get_the_tags();
            // print_r($tags);
            foreach ($tags as $key => $tag) {
            echo '<span class="tag tag-'.$tag->slug.'">'.$tag->name.'</span>';
            }
            ?>
        </div>
        </div>
        <div class="item-date-container">
        <span class="weekday"><?php echo $date->format('l'); ?></span>
        <span class="monthday"><?php echo $date->format('d'); ?></span>
        <span class="month"><?php echo $date->format('M'); ?><span>
        </div>
    </div>
    <?php if(has_post_thumbnail()){ ?>
        <div class="section-container section-post-thumbnail-container">
            <?php
                $img=get_post_thumbnail_url_or_fallback(get_the_ID(), "full", 'pilot');
            ?>
            <img src="<?php echo $img['src']?>" alt="<?php echo $img['alt']?>" class="post-thumbnail"/>
        </div>
    <?php } ?>

    <div class="section-container section-post-container">
        <div class="item-side-data-container">
            <h2 class="post_title">
                <?php the_title(); ?>
            </h2>
            <span class="event-coordinates start_date">
                <?php
                echo $date->format('l, d M Y');
                ?>
            </span>
            <?php
            include_once locate_template('./includes/date-string-remove-seconds.php');
            ?>
            <span class="event-coordinates time">
                <?php
                echo string_remove_seconds($eventManagerData->start_time);
                ?> - <?php
                echo string_remove_seconds($eventManagerData->end_time);
                ?>
            </span>
            <span class="event-coordinates location-name">
                <?php
                // print_r($eventManagerData->location);
                if($eventManagerData->location){
                    if($eventManagerData->location->location_name){
                        echo $eventManagerData->location->location_name;
                    }
                ?>
            </span>
            <span class="event-coordinates location-address">
                <?php
                    if($eventManagerData->location->location_address){
                        echo $eventManagerData->location->location_address;
                    }
                }
                ?>
            </span>
            <span class="event-coordinates event-ispublic">
                <?php if (get_field("only_for_pilots") == 1) { echo "Event for pilots"; } else { echo "Public event"; }; ?>
            </span>
            <?php if(get_field("registration_link")){ ?>
                <a class="button singn-up-button" href="<?php echo get_field("registration_link"); ?>">Sign up here</a>
            <?php } ?>

            <!-- <span class="ical-export-button ">
            <a href="<?php //echo do_shortcode("[event post_id='".$event["post"]->ID."']#_EVENTICALURL[/event]");?>">Export calendar event</a>
            </span> -->
            <br>
        </div>
        <div class="item-post-content-container">
        <?php
        if($eventManagerData->post_content){
            ?>
            <span class="item-content-container">
                <?php echo apply_filters('the_content',$eventManagerData->post_content); ?>
            </span>
            <?php
        }
        ?>
        <?php edit_post_link('Edit', '<span class="edit-link">', '</span>');?>
        </div>
    </div>

    <div class="section-container section-after-post-container">
    <hr/>
    <div class="items-wrapper items-other-posts-wrapper">
        <?php

        /*
        the documentation of eventmanager is barely existing to the date I wrote this.
        The following bunch of code is all just to get the next and previous event links
        */
        // $verbose=true;
        $other_events = EM_Events::get( array("scope"=>"all") );//,"orderby"=>"event_start_date"

        if($verbose){ echo "<pre>"; }

        $currentEventId=$eventManagerData->event_id;

        $currentEventArrayIndex=null;
        $event_next=false;
        $event_prev=false;

        foreach($other_events as $index => $other_event){
            if($verbose){
            echo $other_event -> event_id . " - ";
            echo $other_event -> post_title .  " - ";
            echo  ( new DateTime($other_event->event_start_date) ) -> format('d M') . "\n";
            }
            if($other_event -> event_id == $currentEventId){
            $currentEventArrayIndex=$index;


            $event_next = $other_events[$index+1];
            $event_prev = $other_events[$index-1];
            if($verbose){
                echo $other_event -> event_id . " -- CURRENT!\n";
                echo "    " . $event_next -> event_id . ": next \n";
                echo "    " . $event_prev -> event_id . ": prev \n";
            }

            break;
            }
        }

        if($event_prev){
            ?>
            <a href="<?php echo $event_prev->guid ?>" class="previous-post-link">
                <span class="post-arrow post-prev-arrow"> &lt;</span>
                <span class="link-head"> previous event </span>
                <span class="title">
                    <?php
                    echo (new DateTime($event_prev -> event_start_date)) -> format('d M');
                    echo ": ";
                    echo $event_prev -> post_title;
                    ?>
                </span>
            </a>

            <?php
        }
        $other_event = $other_events[$currentEventArrayIndex+1];


        if($event_next){
            ?>
            <a href="<?php echo $event_next->guid ?>" class="next-post-link">
                <span class="post-arrow post-next-arrow"> &gt;</span>
                <span class="link-head"> next event </span>
                <span class="title">
                    <?php
                    echo (new DateTime($event_next -> event_start_date)) -> format('d M');

                    echo ": ";
                    echo $event_next->post_title;
                    ?>
                </span>
            </a>
            <?php
        }

        ?>
    </div>
    </div>


    <?php if($verbose && is_user_logged_in()){ ?>
        <div class="section-container section-dev-container">
            <h2>Custom Fields:</h2>
            <textarea style="width:100%; height:300px">
                <?php print_r( get_fields() ) ?>
            </textarea>

            <h3>Post:</h3>
            <textarea style="width:100%; height:300px">
                <?php print_r( get_post() ) ?>
            </textarea>

            <h3>Event:</h3>
            <textarea style="width:100%; height:300px">
                <?php
                if (class_exists('EM_Events')) {
                    print_r( $eventManagerData );
                }
                ?>
            </textarea>
        </div>
    <?php } ?>

<?php endwhile;?>


<?php get_sidebar(); ?>

<?php get_footer();
