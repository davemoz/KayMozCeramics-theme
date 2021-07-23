<?php

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package kmc
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <style>
    html {
      visibility: hidden;
      opacity: 0;
    }
  </style>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/favicon-16x16.png">
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/site.webmanifest">
  <link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/safari-pinned-tab.svg" color="#85d0cf">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/inc/imgs/favicon.ico">
  <meta name="msapplication-TileColor" content="#85d0cf">
  <meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/inc/imgs/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="hfeed site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'kmc'); ?></a>

    <?php
    if (is_front_page()) {
    ?>
      <header id="masthead" class="vid-beneath" role="banner">
        <div class="content-width">
          <div class="site-branding">
            <div class="logo logo--white">
              <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Go to Kay Moz Ceramics homepage">
                <svg id="logo-svg">
                  <use xlink:href="#kmc-logo"></use>
                </svg>
              </a>
            </div>
            <!--<h2 class="site-description"><?php bloginfo('description'); ?></h2>-->

          </div><!-- .site-branding -->

          <nav id="site-navigation" class="main-navigation" aria-label="Primary">
            <div id="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
              <div class="menu-icon hover-target">
                <span class="menu-icon__line menu-icon__line-left"></span>
                <span class="menu-icon__line"></span>
                <span class="menu-icon__line menu-icon__line-right"></span>
              </div>
            </div>
            <div id="primary-menu-wrap">
              <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s"><div class="content-width">%3$s</div></ul>', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
              <?php wp_nav_menu(array('theme_location' => 'social', 'menu_id' => 'social-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s"><div class="content-width">%3$s</div></ul>', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
            </div>
          </nav><!-- #site-navigation -->

          <div class="cart-navigation" aria-label="Cart">
            <?php wp_nav_menu(array('theme_location' => 'cart', 'menu_id' => 'cart-nav', 'container' => 'div', 'container_id' => 'cart-menu-wrap', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
          </div>
        </div><!-- .content-width -->
      </header><!-- #masthead -->
      <div class="intro--video">
        <video playsinline autoplay="" preload="none" controls="none" loop muted id="intro__video" poster="<?php echo get_template_directory_uri(); ?>/inc/vid/video-poster-04.jpg">
          <source src="<?php echo get_template_directory_uri(); ?>/inc/vid/KMC-homepage-vid-05.webm" type="video/webm" />
          <source src="<?php echo get_template_directory_uri(); ?>/inc/vid/KMC-homepage-vid-05.ogg" type="video/ogg" />
          <source src="<?php echo get_template_directory_uri(); ?>/inc/vid/KMC-homepage-vid-05.mp4" type="video/mp4" />
          <p class="no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video.</p>
        </video>
        <!--<div class="logo--white">
			<svg id="logo"><use class="logo-svg-use" xlink:href="#kmc-logo"></use></svg>
		</div>-->

        <div class="video-text-layer">
          <!--<h1><span>Handmade fine art ceramics</span> made to bring joy to your home and hope to our community.</h1>-->
        </div>

        <!--<div class="intro__video--controls">
			<span id="audio_button">
				<span class="audio-on"><i id="audio-icon-on" class="fas fa-volume-up"></i></span>
				<span class="audio-off"><i id="audio-icon-off" class="fas fa-volume-off"></i></span>
			</span>
		</div>-->
      </div><!-- .intro--video -->
    <?php
    } else {
    ?>
      <header id="masthead" class="" role="banner">
        <div class="content-width">
          <div class="site-branding">
            <div class="content-width">
              <div class="logo logo--black logo-morph">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="Go to Kay Moz Ceramics homepage">
                  <svg id="logo-svg">
                    <use xlink:href="#kmc-monogram"></use>
                  </svg>
                </a>
              </div>
              <!--<h2 class="site-description"><?php bloginfo('description'); ?></h2>-->
            </div><!-- .content-width -->
          </div><!-- .site-branding -->

          <nav id="site-navigation" class="main-navigation" aria-label="Primary">
            <div id="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
              <div class="menu-icon hover-target">
                <span class="menu-icon__line menu-icon__line-left"></span>
                <span class="menu-icon__line"></span>
                <span class="menu-icon__line menu-icon__line-right"></span>
              </div>
            </div>
            <div id="primary-menu-wrap">
              <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s"><div class="content-width">%3$s</div></ul>', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
              <?php wp_nav_menu(array('theme_location' => 'social', 'menu_id' => 'social-menu', 'items_wrap' => '<ul id="%1$s" class="%2$s"><div class="content-width">%3$s</div></ul>', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
            </div>
          </nav><!-- #site-navigation -->

          <div class="cart-navigation" aria-label="Cart">
            <?php wp_nav_menu(array('theme_location' => 'cart', 'menu_id' => 'cart-nav', 'container' => 'div', 'container_id' => 'cart-menu-wrap', 'walker' => new KayMozCeramics_Nav_Walker(),)); ?>
          </div>
        </div>
      </header><!-- #masthead -->
    <?php
    }
    ?>

    <div id="content" class="page-content">