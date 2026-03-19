<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <!-- ============================================================
     TOP BAR
     ============================================================ -->
    <div class="mkdf-top-bar">
        <div class="mkdf-vertical-align-containers">

            <!-- Left: Phone + Email -->
            <div class="mkdf-position-left">
                <div class="mkdf-position-left-inner">

                    <a class="mkdf-icon-widget-holder"
                        href="tel:<?php echo esc_attr(get_theme_mod('wl_phone', '+12345677789')); ?>" target="_blank">
                        <span class="mkdf-icon-element ion-android-call"></span>
                        <span class="mkdf-icon-text">
                            <?php echo esc_html(get_theme_mod('wl_phone', '+123 45677 789')); ?>
                        </span>
                    </a>

                    <a class="mkdf-icon-widget-holder"
                        href="mailto:<?php echo esc_attr(get_theme_mod('wl_email', 'wanderland@example.com')); ?>"
                        target="_blank" style="margin: 0 0 0 25px;">
                        <span class="mkdf-icon-element ion-ios-email-outline"></span>
                        <span class="mkdf-icon-text">
                            <?php echo esc_html(get_theme_mod('wl_email', 'wanderland@example.com')); ?>
                        </span>
                    </a>

                </div>
            </div><!-- .mkdf-position-left -->

            <!-- Right: Social Icons -->
            <div class="mkdf-position-right">
                <div class="mkdf-position-right-inner">

                    <div class="mkdf-social-icons-group-widget">

                        <?php if ($instagram = get_theme_mod('wl_social_instagram')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($instagram); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <span class="mkdf-social-icon-widget ion-social-instagram"></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($twitter = get_theme_mod('wl_social_twitter')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($twitter); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="Twitter / X">
                            <span class="mkdf-social-icon-widget ion-social-twitter"></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($facebook = get_theme_mod('wl_social_facebook')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($facebook); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <span class="mkdf-social-icon-widget ion-social-facebook"></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($youtube = get_theme_mod('wl_social_youtube')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($youtube); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                            <span class="mkdf-social-icon-widget ion-social-youtube"></span>
                        </a>
                        <?php endif; ?>

                    </div>

                </div>
            </div><!-- .mkdf-position-right -->

        </div><!-- .mkdf-vertical-align-containers -->
    </div><!-- .mkdf-top-bar -->


    <!-- ============================================================
     DESKTOP MAIN HEADER  (Divided: left nav | logo | right nav)
     ============================================================ -->
    <header class="mkdf-page-header" role="banner">
        <div class="mkdf-menu-area">
            <div class="mkdf-vertical-align-containers">

                <!-- LEFT SIDE: Destinations icon + Left nav -->
                <div class="mkdf-position-left">

                    <!-- Destinations quick link -->
                    <div class="mkdf-divided-left-widget-area">
                        <div class="mkdf-divided-left-widget-area-inner">
                            <div class="mkdf-position-left-inner-wrap">
                                <a class="mkdf-icon-widget-holder"
                                    href="<?php echo esc_url(home_url('/destination-list/')); ?>">
                                    <span class="mkdf-icon-element ion-map"></span>
                                    <span class="mkdf-icon-text">
                                        <?php esc_html_e('Destinations', 'wanderland'); ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Left navigation (Home, Pages, Destinations) -->
                    <div class="mkdf-position-left-inner">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'header-left',
                            'menu_class' => 'mkdf-main-menu mkdf-drop-down mkdf-divided-left-part mkdf-default-nav clearfix',
                            'container' => 'nav',
                            'container_class' => '',
                            'container_attr' => array('aria-label' => __('Left Header Menu', 'wanderland')),
                            'depth' => 3,
                            'walker' => new WL_Header_Walker(),
                            'fallback_cb' => false,
                        ));
                        ?>
                    </div>

                </div><!-- .mkdf-position-left -->


                <!-- CENTER: Logo -->
                <div class="mkdf-position-center">
                    <div class="mkdf-position-center-inner">
                        <div class="mkdf-logo-wrapper">
                            <a itemprop="url" href="<?php echo esc_url(home_url('/')); ?>">

                                <?php
                                $logo_id = get_theme_mod('custom_logo');
                                $logo_dark_id = get_theme_mod('wl_logo_dark');
                                $logo_light_id = get_theme_mod('wl_logo_light');

                                // Normal / default logo
                                if ($logo_id):
                                    $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                                    ?>
                                <img itemprop="image" class="mkdf-normal-logo" src="<?php echo esc_url($logo_url); ?>"
                                    alt="<?php bloginfo('name'); ?>">
                                <?php endif; ?>

                                <?php if ($logo_dark_id): ?>
                                <img itemprop="image" class="mkdf-dark-logo"
                                    src="<?php echo esc_url(wp_get_attachment_image_url($logo_dark_id, 'full')); ?>"
                                    alt="<?php bloginfo('name'); ?>">
                                <?php endif; ?>

                                <?php if ($logo_light_id): ?>
                                <img itemprop="image" class="mkdf-light-logo"
                                    src="<?php echo esc_url(wp_get_attachment_image_url($logo_light_id, 'full')); ?>"
                                    alt="<?php bloginfo('name'); ?>">
                                <?php endif; ?>

                            </a>
                        </div>
                    </div>
                </div><!-- .mkdf-position-center -->


                <!-- RIGHT SIDE: Right nav + Search -->
                <div class="mkdf-position-right">

                    <!-- Right navigation (Blog, Shop, Landing) -->
                    <div class="mkdf-position-right-inner">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'header-right',
                            'menu_class' => 'mkdf-main-menu mkdf-drop-down mkdf-divided-right-part mkdf-default-nav clearfix',
                            'container' => 'nav',
                            'container_class' => '',
                            'container_attr' => array('aria-label' => __('Right Header Menu', 'wanderland')),
                            'depth' => 3,
                            'walker' => new WL_Header_Walker(),
                            'fallback_cb' => false,
                        ));
                        ?>
                    </div>

                    <!-- Search -->
                    <div class="mkdf-divided-right-widget-area">
                        <div class="mkdf-divided-right-widget-area-inner">
                            <div class="mkdf-search-opener-holder">

                                <!-- Search form (hidden, opens on click) -->
                                <form action="<?php echo esc_url(home_url('/')); ?>" class="mkdf-on-side-search-form"
                                    method="get" role="search">
                                    <div class="mkdf-form-holder">
                                        <div class="mkdf-form-holder-inner">
                                            <div class="mkdf-field-holder">
                                                <input type="text"
                                                    placeholder="<?php esc_attr_e('Search', 'wanderland'); ?>" name="s"
                                                    class="mkdf-search-field" autocomplete="off" required>
                                            </div>
                                            <button class="mkdf-onside-btn" type="submit"
                                                aria-label="<?php esc_attr_e('Submit search', 'wanderland'); ?>">
                                                <span class="mkdf-onside-btn-icon ion-ios-search"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Search toggle button -->
                                <a class="mkdf-search-opener" href="javascript:void(0)"
                                    aria-label="<?php esc_attr_e('Toggle search', 'wanderland'); ?>">
                                    <span class="mkdf-search-opener-wrapper">
                                        <span class="mkdf-search-icon-text">
                                            <?php esc_html_e('Search', 'wanderland'); ?>
                                        </span>
                                        <i class="ion-ios-search"></i>
                                    </span>
                                </a>

                            </div><!-- .mkdf-search-opener-holder -->
                        </div>
                    </div><!-- .mkdf-divided-right-widget-area -->

                </div><!-- .mkdf-position-right -->

            </div><!-- .mkdf-vertical-align-containers -->
        </div><!-- .mkdf-menu-area -->
    </header><!-- .mkdf-page-header -->


    <!-- ============================================================
     MOBILE HEADER
     ============================================================ -->
    <header class="mkdf-mobile-header" role="banner">
        <div class="mkdf-mobile-header-inner">
            <div class="mkdf-mobile-header-holder">
                <div class="mkdf-grid">
                    <div class="mkdf-vertical-align-containers">

                        <!-- Mobile logo -->
                        <div class="mkdf-position-left">
                            <div class="mkdf-position-left-inner">
                                <div class="mkdf-mobile-logo-wrapper">
                                    <a itemprop="url" href="<?php echo esc_url(home_url('/')); ?>">
                                        <?php
                                        $mobile_logo_id = get_theme_mod('wl_logo_mobile');
                                        if ($mobile_logo_id):
                                            ?>
                                        <img loading="lazy" itemprop="image"
                                            src="<?php echo esc_url(wp_get_attachment_image_url($mobile_logo_id, 'full')); ?>"
                                            alt="<?php bloginfo('name'); ?>">
                                        <?php elseif ($logo_id): ?>
                                        <img loading="lazy" itemprop="image"
                                            src="<?php echo esc_url(wp_get_attachment_image_url($logo_id, 'full')); ?>"
                                            alt="<?php bloginfo('name'); ?>">
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Hamburger toggle -->
                        <div class="mkdf-position-right">
                            <div class="mkdf-position-right-inner">
                                <div class="mkdf-mobile-menu-opener mkdf-mobile-menu-opener-predefined">
                                    <a href="javascript:void(0)"
                                        aria-label="<?php esc_attr_e('Toggle menu', 'wanderland'); ?>"
                                        aria-expanded="false">
                                        <h5 class="mkdf-mobile-menu-text">
                                            <?php esc_html_e('Menu', 'wanderland'); ?>
                                        </h5>
                                        <span class="mkdf-mobile-menu-icon">
                                            <span class="mkdf-hm-lines">
                                                <span class="mkdf-hm-line mkdf-line-1"></span>
                                                <span class="mkdf-hm-line mkdf-line-2"></span>
                                                <span class="mkdf-hm-line mkdf-line-3"></span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div><!-- .mkdf-vertical-align-containers -->
                </div><!-- .mkdf-grid -->
            </div><!-- .mkdf-mobile-header-holder -->

            <!-- Mobile nav panel -->
            <nav class="mkdf-mobile-nav" role="navigation"
                aria-label="<?php esc_attr_e('Mobile Menu', 'wanderland'); ?>">
                <div class="mkdf-grid">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'menu_class' => '',
                        'container' => false,
                        'depth' => 3,
                        'walker' => new WL_Mobile_Walker(),
                        'fallback_cb' => false,
                    ));
                    ?>
                </div>
            </nav>


        </div>
    </header>