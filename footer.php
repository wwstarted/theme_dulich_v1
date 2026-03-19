<footer class="mkdf-page-footer mkdf-light-footer">

    <!-- ============================================================
         FOOTER TOP: Logo + Bio Description
         ============================================================ -->
    <div class="mkdf-footer-top-holder">
        <div class="mkdf-footer-top-inner mkdf-grid">
            <div class="mkdf-grid-row mkdf-footer-top-alignment-left">

                <!-- Logo -->
                <div class="mkdf-column-content mkdf-grid-col-3">
                    <div class="mkdf-footer-column-1">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="mkdf-footer-logo-link">
                            <?php
                            $footer_logo_id = get_theme_mod('wl_logo_footer');
                            if ($footer_logo_id):
                                ?>
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($footer_logo_id, 'full')); ?>"
                                alt="<?php bloginfo('name'); ?>" class="mkdf-footer-logo">
                            <?php else: ?>
                            <span class="mkdf-footer-site-name"><?php bloginfo('name'); ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>

                <!-- Bio text -->
                <div class="mkdf-column-content mkdf-grid-col-9">
                    <div class="mkdf-footer-column-2 mkdf-footer-bio">
                        <p class="mkdf-footer-bio-text">
                            <?php echo wp_kses_post(get_theme_mod(
                                'wl_footer_bio',
                                'Based in Utah, USA, Wanderland is a blog by Markus <span class="mkdf-footer-highlight">Thompson.</span> His posts<br>explore outdoor experiences through photos and diaries with tips &amp; tricks.'
                            )); ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- .mkdf-footer-top-holder -->


    <!-- ============================================================
         FOOTER MIDDLE: 4 columns
         ============================================================ -->
    <div class="mkdf-footer-middle-holder">
        <div class="mkdf-footer-middle-inner mkdf-grid">
            <div class="mkdf-grid-row mkdf-footer-middle-alignment-left">

                <!-- Col 1: About the blog -->
                <div class="mkdf-column-content mkdf-grid-col-3">
                    <div class="mkdf-footer-widget">
                        <div class="mkdf-widget-title-holder">
                            <h6 class="mkdf-widget-title"><?php esc_html_e('About the blog', 'wanderland'); ?></h6>
                        </div>
                        <div class="mkdf-footer-about-text">
                            <p><?php echo wp_kses_post(get_theme_mod('wl_footer_about', 'Lorem ipsum dolor sit amet, conse ctetur adipisicing elit, sed do eiusmod mas.')); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Col 2: Subscribe to newsletter -->
                <div class="mkdf-column-content mkdf-grid-col-3">
                    <div class="mkdf-footer-widget mkdf-footer-newsletter">
                        <div class="mkdf-widget-title-holder">
                            <h6 class="mkdf-widget-title">
                                <?php esc_html_e('Subscribe to newsletter', 'wanderland'); ?>
                            </h6>
                        </div>
                        <form class="mkdf-footer-newsletter-form" method="post"
                            action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                            <input type="hidden" name="action" value="wl_newsletter_subscribe">
                            <?php wp_nonce_field('wl_newsletter_nonce', 'wl_newsletter_nonce_field'); ?>
                            <div class="mkdf-newsletter-fields">
                                <div class="mkdf-newsletter-field-wrap">
                                    <input type="text" name="your-name" class="mkdf-newsletter-input"
                                        placeholder="<?php esc_attr_e('Your name...', 'wanderland'); ?>" required>
                                </div>
                                <div class="mkdf-newsletter-field-wrap">
                                    <input type="email" name="your-email" class="mkdf-newsletter-input"
                                        placeholder="<?php esc_attr_e('Your e-mail...', 'wanderland'); ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="mkdf-newsletter-btn">
                                <span class="mkdf-btn-text"><?php esc_html_e('subscribe', 'wanderland'); ?></span>
                                <span class="mkdf-btn-arrow">↗</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Col 3: Recent news -->
                <div class="mkdf-column-content mkdf-grid-col-3">
                    <div class="mkdf-footer-widget">
                        <div class="mkdf-widget-title-holder">
                            <h6 class="mkdf-widget-title"><?php esc_html_e('Recent news', 'wanderland'); ?></h6>
                        </div>
                        <div class="mkdf-footer-recent-posts">
                            <?php
                            $recent_posts = new WP_Query(array(
                                'post_type' => 'post',
                                'posts_per_page' => 3,
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'no_found_rows' => true,
                            ));
                            if ($recent_posts->have_posts()):
                                while ($recent_posts->have_posts()):
                                    $recent_posts->the_post();
                                    ?>
                            <div class="mkdf-footer-post-item">
                                <div class="mkdf-footer-post-date">
                                    <span class="mkdf-icon-font-elegant icon_calendar"></span>
                                    <a
                                        href="<?php echo esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </a>
                                </div>
                                <p class="mkdf-footer-post-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </p>
                            </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Col 4: Instagram feed -->
                <div class="mkdf-column-content mkdf-grid-col-3">
                    <div class="mkdf-footer-widget mkdf-footer-instagram">
                        <div class="mkdf-widget-title-holder">
                            <h6 class="mkdf-widget-title"><?php esc_html_e('Instagram feed', 'wanderland'); ?></h6>
                        </div>
                        <?php
                        // Render Instagram widget if active, or placeholder grid
                        if (is_active_widget(false, false, 'mkdf_instagram_widget', true)) {
                            the_widget('mkdf_instagram_widget');
                        } else {
                            // Placeholder grid — 6 squares
                            echo '<div class="mkdf-instagram-placeholder">';
                            for ($i = 0; $i < 6; $i++) {
                                echo '<div class="mkdf-instagram-placeholder-item"></div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- .mkdf-footer-middle-holder -->


    <!-- ============================================================
         FOOTER BOTTOM: Socials + Copyright
         ============================================================ -->
    <div class="mkdf-footer-bottom-holder">
        <div class="mkdf-footer-bottom-inner mkdf-grid">
            <div class="mkdf-grid-row">

                <!-- Social icons -->
                <div class="mkdf-grid-col-6">
                    <div class="mkdf-footer-socials">
                        <span class="mkdf-footer-socials-label"><?php esc_html_e('Socials', 'wanderland'); ?></span>

                        <?php if ($instagram = get_theme_mod('wl_social_instagram')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($instagram); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <span class="mkdf-social-icon-widget ion-social-instagram"></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($twitter = get_theme_mod('wl_social_twitter')): ?>
                        <a class="mkdf-social-icon-widget-holder" href="<?php echo esc_url($twitter); ?>"
                            target="_blank" rel="noopener noreferrer" aria-label="Twitter">
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

                <!-- Copyright -->
                <div class="mkdf-grid-col-6">
                    <div class="mkdf-footer-copyright">
                        <p>
                            &copy; <?php echo esc_html(date('Y')); ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                            <span><?php esc_html_e(', All Rights Reserved', 'wanderland'); ?></span>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div><!-- .mkdf-footer-bottom-holder -->

</footer><!-- .mkdf-page-footer -->


<!-- ============================================================
     BACK TO TOP BUTTON
     ============================================================ -->
<a id="mkdf-back-to-top" href="#" aria-label="<?php esc_attr_e('Back to top', 'wanderland'); ?>">
    <span class="mkdf-icon-stack">
        <!-- Arrow SVG (shown first — slides up on hover) -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.3 22.3" class="mkdf-btt-arrow mkdf-btt-arrow-primary"
            aria-hidden="true">
            <g>
                <line x1="10.8" y1="20.9" x2="10.8" y2="2"></line>
                <line x1="10.8" y1="2" x2="0.9" y2="11.9"></line>
                <line x1="10.8" y1="2" x2="20.7" y2="12"></line>
            </g>
        </svg>
        <!-- Arrow SVG (hover duplicate — slides in from below) -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22.3 22.3" class="mkdf-btt-arrow mkdf-btt-arrow-hover"
            aria-hidden="true">
            <g>
                <line x1="10.8" y1="20.9" x2="10.8" y2="2"></line>
                <line x1="10.8" y1="2" x2="0.9" y2="11.9"></line>
                <line x1="10.8" y1="2" x2="20.7" y2="12"></line>
            </g>
        </svg>
    </span>
</a>

<?php wp_footer(); ?>

</body>

</html>