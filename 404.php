<?php
/**
 * WANDERLAND — 404.php
 * Full-screen 404: header floats above bg image, no footer, no scroll
 */

get_header();
?>

<main id="wl-404-main" class="wl-404-main" role="main">

    <div class="wl-404-scene"
        style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/images/404.jpg');">

        <!-- Overlay -->
        <div class="wl-404-overlay" aria-hidden="true"></div>

        <!-- Content -->
        <div class="wl-404-content">

            <!-- Big number -->
            <div class="wl-404-number" aria-hidden="true">
                <span>4</span>
                <!-- Compass as the 0 -->
                <span class="wl-404-compass">
                    <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="54" stroke="currentColor" stroke-width="1.5" opacity="0.3" />
                        <circle cx="60" cy="60" r="44" stroke="currentColor" stroke-width="1" opacity="0.15"
                            stroke-dasharray="3 5" />
                        <!-- Cardinal ticks -->
                        <line x1="60" y1="6" x2="60" y2="18" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" />
                        <line x1="60" y1="102" x2="60" y2="114" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" />
                        <line x1="6" y1="60" x2="18" y2="60" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" />
                        <line x1="102" y1="60" x2="114" y2="60" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" />
                        <!-- Labels -->
                        <text x="60" y="29" text-anchor="middle" font-size="10" font-family="Jost,sans-serif"
                            font-weight="700" fill="currentColor" letter-spacing="1">N</text>
                        <text x="60" y="99" text-anchor="middle" font-size="10" font-family="Jost,sans-serif"
                            font-weight="700" fill="currentColor" opacity="0.45">S</text>
                        <text x="98" y="64" text-anchor="middle" font-size="10" font-family="Jost,sans-serif"
                            font-weight="700" fill="currentColor" opacity="0.45">E</text>
                        <text x="22" y="64" text-anchor="middle" font-size="10" font-family="Jost,sans-serif"
                            font-weight="700" fill="currentColor" opacity="0.45">W</text>
                        <!-- Needle north — accent color -->
                        <polygon class="wl-404-needle-n" points="60,18 56,60 60,64 64,60"
                            fill="var(--color-accent,#b89d6e)" />
                        <!-- Needle south -->
                        <polygon class="wl-404-needle-s" points="60,102 56,60 60,64 64,60"
                            fill="rgba(255,255,255,0.25)" />
                        <!-- Center -->
                        <circle cx="60" cy="62" r="5" fill="white" opacity="0.9" />
                        <circle cx="60" cy="62" r="2.5" fill="var(--color-primary,#2c2c2c)" />
                    </svg>
                </span>
                <span>4</span>
            </div>

            <!-- Text -->
            <p class="wl-404-tagline">
                <?php esc_html_e('Oops! You seem lost on the map.', 'wanderland'); ?>
            </p>
            <h1 class="wl-404-title">
                <?php esc_html_e('Page Not Found', 'wanderland'); ?>
            </h1>

            <!-- Search -->
            <form class="wl-404-search" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
                <div class="wl-404-search-inner">
                    <input type="text" name="s" class="wl-404-search-input"
                        placeholder="<?php esc_attr_e('Search for something...', 'wanderland'); ?>" autocomplete="off"
                        aria-label="<?php esc_attr_e('Search', 'wanderland'); ?>">
                    <button type="submit" class="wl-404-search-btn"
                        aria-label="<?php esc_attr_e('Search', 'wanderland'); ?>">
                        <i class="ion-ios-search"></i>
                    </button>
                </div>
            </form>

            <!-- Buttons -->
            <div class="wl-404-btns">
                <a class="wl-404-btn wl-404-btn--primary" href="<?php echo esc_url(home_url('/')); ?>">
                    <svg viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <path d="M2 9L9 2l7 7M4 7.5V15h4v-4h2v4h4V7.5" stroke="currentColor" stroke-width="1.6"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <?php esc_html_e('Back to Home', 'wanderland'); ?>
                </a>
                <?php
                $blog_url = ($p = get_option('page_for_posts')) ? get_permalink($p) : home_url('/blog/');
                ?>
                <a class="wl-404-btn wl-404-btn--secondary" href="<?php echo esc_url($blog_url); ?>">
                    <svg viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <rect x="2" y="2" width="14" height="14" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <path d="M5 6h8M5 9h6M5 12h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <?php esc_html_e('Browse Blog', 'wanderland'); ?>
                </a>
            </div>

        </div><!-- .wl-404-content -->

    </div><!-- .wl-404-scene -->

</main>

<?php
// NO get_footer() — intentionally omitted for full-screen 404
wp_footer();
?>
</body>

</html>