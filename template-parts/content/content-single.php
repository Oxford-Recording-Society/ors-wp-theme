<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header alignwide">
			<h1 class="header-title">
			<span class="header-title-outline">
				OPPORTUNITIES
				<br />
				OPPORTUNITIES
				<br />
			</span>
			OPPORTUNITIES
		</h1>
	</header><!-- .entry-header -->
	<?php twenty_twenty_one_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_title( '<h2 class="ors-opportunity-title">', '</h2>' );

			$authorid = get_the_author_ID();
			$authoravatar = get_avatar($authorid);

			echo '<h3 class="ors-opportunity-author"><a href="/user/'
				. get_the_author_nickname() . '">'
				. $authoravatar
				. '<span class="author_title">'
				. get_the_author()
				. '</span></a></h3>';

		the_content();

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
