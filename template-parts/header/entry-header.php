<?php
/**
 * Displays the post header
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

$title = get_the_title();
$md5 = hash('md5', $title);
$hex = substr($md5, 0 , 2);
$val = hexdec($hex);
$hue = ($val/255) * 360;

?>
<h1 class="header-title">
  <span class="header-title-outline">
    <?php echo $title; ?>
    <br />
    <?php echo $title; ?>
    <br />
  </span>
  <?php echo $title; ?>
</h1>
<style>
:root{
  --header-gradient-hue: <?php echo $hue; ?>;
}
</style>