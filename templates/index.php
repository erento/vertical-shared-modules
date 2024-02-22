<?php get_header(); ?>
	<div class="container page-container">
		<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>

		<?php else: ?>
			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
		<?php endif; ?>
	</div>
<?php get_footer(); ?>
