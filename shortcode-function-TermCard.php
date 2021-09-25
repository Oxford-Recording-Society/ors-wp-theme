<?php
    function Draw_TermCard() {
        $events = tribe_get_events();

        $num_of_days = 56;

        // Create an empty array to write events into
        $events_array = array_fill(0, $num_of_days, array());

        $start_date_object = null;
        
        // Find the date on which the term starts
        foreach ( $events as $event ) {
            if ($event -> post_title == "Term Start") {
                $details = tribe_get_event($event);

                $start_date_object = new DateTime(date($details -> start_date));
                break;        
            }
        }

        
        if ( $start_date_object !== null ) {
            
            // Extract all details and place them in events_array, which is ordered by day
            foreach ( $events as $event ) {
                $details = tribe_get_event($event);
                

                $diff = $start_date_object -> diff(new DateTime(date($details -> start_date)));
                $days_diff = $diff -> days;

                //Check if the event happens in the current term before placing it in the array
                if (0 <= $days_diff && $days_diff < $num_of_days && $event -> post_title !== "Term Start") {
                    $start_time = substr($details -> start_date, -8,5);
                    $end_time = substr($details -> end_date, -8,5);
                    $unparsed_link = tribe_get_event_website_link($event);
                    $link = "";
                    if ($unparsed_link !== "") {
                        $XML = new SimpleXMLElement(tribe_get_event_website_link($event));
                        $link = $XML["href"];
                    }

                    $descriptors = tribe_get_event_cat_slugs( $event -> ID );
                    
                    $content = array("title" => $event -> post_title, "start_time" => $start_time, "end_time" => $end_time,
                    "descriptors" => $descriptors, "link" => $link);

                    array_push($events_array[$days_diff], $content); 
                }

            }
            
            // html output

            $labelling_colours = array('type-speakers' => '#ed1111', 'type-panels' => '#06c920', 'type-workshops' => '#959696');

            $type_text = array('type-speakers' => 'Speakers', 'type-panels' => 'Panels', 'type-workshops' => 'Workshops');

            $topic_text = array('topic-music-making-performing' => 'Music Making & Performing', 'topic-music-art-media' => 'Music Art & Media',
            'topic-music-business' => 'Music Business');

            // Generate the first dropdown
            $html_output = "<div class='dropdown' data-control='checkbox-dropdown'><label class='dropdown-label'>Select</label>
            <div class='dropdown-list'><a href='#' data-toggle='check-all' class='dropdown-option check-all'>Check All </a>";

            foreach ($type_text as $key => $value) {
                $html_output .= "<label class='dropdown-option'>
                <input type='checkbox' name='dropdown-group' value=$key>$value</label>";
            }

            $html_output .= "</div></div>";

            //second dropdown

            $html_output .= "<div class='dropdown' data-control='checkbox-dropdown'><label class='dropdown-label'>Select</label>
            <div class='dropdown-list'><a href='#' data-toggle='check-all' class='dropdown-option check-all'>Check All </a>";

            foreach ($topic_text as $key => $value) {
                $html_output .= "<label class='dropdown-option'>
                <input type='checkbox' name='dropdown-group' value=$key>$value</label>";
            }

            $html_output .= "</div></div>";

            // Generate the table
            $html_output .= "<figure class='wp-block-table alignfull'><table><tbody><tr><td></td><td>Sunday</td><td>Monday</td><td>Tuesday</td>
            <td>Wednesday</td><td>Thursday</td><td>Friday</td><td>Saturday</td>";

            $day_count = 0;
            
            for ($i = 0; $i < 64; $i++) {
                
                if ($i % 8 == 0) {
                    $html_output .= "</tr><tr><td>Wk". ($i+8)/8 . "</td>";
                } else {
                    $html_output .= "<td>";
                    // style=\"background-color: $labelling_colours[$event_category];\ $event[title]"

                    foreach ( $events_array[$day_count] as $event ) {
                        $descriptors = $event['descriptors'];
                        $type = $descriptors[1];
                        $topic = $descriptors[0];
                        $html_output .= "<div class= '$descriptors[0] $descriptors[1]'> <a href='$event[link]'>$event[title]</a><br>
                        $event[start_time]-$event[end_time] <br> $topic_text[$topic] <div class='boxlabel' style='background-color: $labelling_colours[$type];'>
                        </div></div>";
                    }

                    $html_output .= "</td>";
                    $day_count += 1;
                }

            }
            
            $html_output .= "</tr></tbody></table></figure>";

            return $html_output;
        }
        

    }

    add_shortcode( 'Term_Card', 'Draw_TermCard');

?>