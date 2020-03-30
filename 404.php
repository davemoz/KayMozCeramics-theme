<?php

/**
 * The template for displaying 404 pages (not found).
 *
 * @package kmc
 */

get_header(); ?>

<div class="content-width">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'kmc'); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e('It looks like nothing was found at this location. Try one of the links below or a search?', 'kmc'); ?></p>
					<p>
						<a href="<?php $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) ); echo $shop_page_url; ?>"><button><?php esc_html_e('Check out the shop', 'kmc'); ?></button></a>
					</p>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .content-width -->

<?php get_footer(); ?>
