<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package kmc
 */

?>

	</div><!-- #content --> 

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="content-width">
			<?php if( is_page( array( 'Contact', 106, 5807 ) ) ){ ?>
				<section class="instagram-feed">
					<?php echo do_shortcode( '[instagram-feed]', false ); ?>
				</section>
			<?php } ?>
			<?php if( !is_cart() && !is_checkout() && !is_account_page() && !is_page( array( 106, 5807 ) ) ){ ?>
				<section class="newsletter-signup">
					<?php echo do_shortcode( '[contact-form-7 id="215" title="Newsletter Signup"]', false ); ?>
				</section>
			<?php } ?>
			<?php if( is_shop() || is_product() || is_cart() || is_checkout() ){ ?>
				<section class="payment-methods">
					<i class="pf pf-american-express"></i>
					<!--<i class="pf pf-apple-pay"></i>-->
					<i class="pf pf-discover"></i>
					<i class="pf pf-mastercard-alt"></i>
					<i class="pf pf-paypal"></i>
					<i class="pf pf-visa"></i>
				</section>
			<?php } ?>
			<section class="privacy">
				<span><a href="/terms-of-use/">Terms of Use</a></span>
				<span class="sep"> | </span>
				<span><a href="/privacy-policy/">Privacy Policy</a></span>
				<span class="sep"> | </span>
				<span><a href="/privacy-tools/">Privacy Tools</a></span>
				<span class="sep"> | </span>
				<span><a href="/return-policy/">Return Policy</a></span>
			</section>
			<section class="site-info">
				<span class="copyright">Copyright © <?php echo date("Y"); ?> Kay Moz Ceramics</span>
				<span class="sep"> | </span>
				<span class="powered-by-wp">Proudly powered by <a href="<?php echo esc_url( __( 'https://wordpress.com/alp/?aff=3656&cid=2370454', 'kmc' ) ); ?>"><?php printf( esc_html__( '%s', 'kmc' ), 'WordPress' ); ?></a> and <a href="<?php echo esc_url( __( 'https://woocommerce.com/?aff=3656&cid=2370454', 'kmc' ) ); ?>"><?php printf( esc_html__( '%s', 'kmc' ), 'WooCommerce' ); ?></a></span>
				<span class="sep"> | </span>
				<span class="site-credit"><?php printf( esc_html__( 'Site by %s', 'kmc' ), '<a href="https://www.davejmoz.com/" rel="designer">davemoz.dev</a>' ); ?></span>
			</section><!-- .site-info -->
		</div><!-- .content-width -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
