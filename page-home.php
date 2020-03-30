<?php
/**
 * Template Name: Home
 *
 * @package kmc
 */

get_header(); ?>

<section id="home-bethany-section" class="section__content accent-bg">
	<div class="content-width">

			<?php /* Use this later once custom blocks are created
	
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>
			
			*/
			
			?>

			<?php the_content() ?>

	</div><!-- .content-width -->
</section><!-- .section__content .accent-bg -->

<section id="section-about" class="section__content parallax">
	<div class="content-width">
		<main id="main" class="site-main" role="main">
			<a href="/about/" class="btn-link"><button class="accent-second">Learn more about Kay</button></a>
		</main>
	</div>
</section><!-- .parallax -->

<section id="instagram-feed" class="section__content">
	<div class="content-width">
		<?php
			echo do_shortcode( '[instagram-feed]', true );
		?>
	</div>
</section>

<?php get_footer(); ?>
