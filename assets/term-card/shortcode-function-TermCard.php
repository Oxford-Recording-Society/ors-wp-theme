<?php
    // Author: Alexandru G. Apetrei
    // Contact: alex_apetrei@outlook.com
    // Description: A program that utilises functionality from The Events Calendar by Knowledgebase (a wordpress plugin) to generate a term card.

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

            // Labelling and text to display
            $labelling_colours = array('type-speakers' => '#6360f4', 'type-panels' => '#140e5f', 'type-workshops' => '#55412e',
            'type-club-nights' => '#1b8bc3', 'type-gigs-performances' => '#84aa2d', 'type-socials' => '#0c6586', 'type-information' => '#b32593');

            $type_text = array('type-speakers' => 'Speakers', 'type-panels' => 'Panels', 'type-workshops' => 'Workshops', 'type-club-nights' => 'Club Nights',
            'type-gigs-performances' => 'Gigs & Performances', 'type-socials' => 'Socials', 'type-information' => 'Information');

            $topic_text = array('topic-music-making-performing' => 'Music Making & Performing', 'topic-music-art-media' => 'Music Art & Media',
            'topic-music-business' => 'Music Business', 'topic-music-events-management' => 'Music Events Management');


            // Generate the first dropdown
            $html_output = "<h4>Filter by Event Type:</h4>";

            $html_output .= "<div class='dropdown' data-control='checkbox-dropdown'><label class='dropdown-label'>All Selected</label>
            <div class='dropdown-list'><a href='#' data-toggle='check-all' class='dropdown-option check-all'>Check All </a>";

            foreach ($type_text as $key => $value) {
                $html_output .= "<label class='dropdown-option'>
                <input type='checkbox' name='dropdown-group' value=$key>$value<div class='boxlabel' style='background-color: $labelling_colours[$key];
                margin-left: 0.5em;'>
                </div></label>";
            }

            $html_output .= "</div></div>";

            //second dropdown
            $html_output .= "<h4>Filter by Event Topic:</h4>";

            $html_output .= "<div class='dropdown' data-control='checkbox-dropdown'><label class='dropdown-label'>All Selected</label>
            <div class='dropdown-list'><a href='#' data-toggle='check-all' class='dropdown-option check-all'>Check All </a>";

            foreach ($topic_text as $key => $value) {
                $html_output .= "<label class='dropdown-option'>
                <input type='checkbox' name='dropdown-group' value=$key>$value</label>";
            }

            $html_output .= "</div></div>";

            //Generate key for the colour coding
            $html_output .= "<div>";

            foreach ($type_text as $key => $value) {
                $html_output .= $value;
                $html_output .= "<div class='boxlabel' style='background-color: $labelling_colours[$key]; margin-left: 0.5em; margin-right: 1.5em'>
                </div>";
            }

            $html_output .= "</div>";

            // Generate the table
            $html_output .= "<figure class='wp-block-table alignfull'><table><tbody><tr><td style='width:5.5%'></td><td style='width:13.5%'>Sunday</td>
            <td style='width:13.5%'>Monday</td><td style='width:13.5%'>Tuesday</td><td style='width:13.5%'>Wednesday</td><td style='width:13.5%'>
            Thursday</td><td style='width:13.5%'>Friday</td><td style='width:13.5%'>Saturday</td>";

            $day_count = 0;

            for ($i = 0; $i < 64; $i++) {

                if ($i % 8 == 0) {
                    $html_output .= "</tr><tr><td>Wk". ($i+8)/8 . "</td>";
                } else {
                    $html_output .= "<td>";

                    foreach ( $events_array[$day_count] as $event ) {
                        $descriptors = $event['descriptors'];

                        if (substr($descriptors[0],0,5) == 'topic') {
                            $topic = $descriptors[0];
                            $type = $descriptors[1];

                        } else {
                            $type = $descriptors[0];
                            $topic = $descriptors[1];
                        }

                        $html_output .= "<div class= '$descriptors[0] $descriptors[1]'> <a href='$event[link]'>$event[title]</a><br>
                        $event[start_time]-$event[end_time] <br> <p style='font-size: 75%;'> $topic_text[$topic]</p><div class='boxlabel'
                        style='background-color: $labelling_colours[$type];'></div></div>";
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