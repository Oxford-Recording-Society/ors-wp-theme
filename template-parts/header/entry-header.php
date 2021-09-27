<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

$title = get_the_title();

ors_draw_outlined_header($title);
ors_draw_marquee($title);
ors_output_header_hue($title);
?>
