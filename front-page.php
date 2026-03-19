<?php
/**
 * WANDERLAND — front-page.php  (v3 — centered hero layout)
 * Section 1: Hero Slider
 */

get_header();
?>

<main id="wl-main" class="wl-main-content">

    <?php
    $hero_query = wl_get_section_query('hero_slider');

    if ($hero_query && $hero_query->have_posts()):
        $slide_count = $hero_query->post_count;
        ?>

    <section class="wl-hero-slider" aria-label="<?php esc_attr_e('Featured Posts', 'wanderland'); ?>"
        data-total="<?php echo esc_attr($slide_count); ?>">

        <div class="wl-hero-track" role="list">

            <?php
                $slide_index = 0;
                while ($hero_query->have_posts()):
                    $hero_query->the_post();

                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'wl-hero');
                    if (!$img_url)
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');

                    $excerpt = get_the_excerpt();
                    if (!$excerpt) {
                        $excerpt = wp_trim_words(get_the_content(), 22, '...');
                    }
                    ?>

            <div class="wl-hero-slide <?php echo $slide_index === 0 ? 'is-active' : ''; ?>" role="listitem"
                aria-hidden="<?php echo $slide_index === 0 ? 'false' : 'true'; ?>"
                data-index="<?php echo esc_attr($slide_index); ?>"
                style="background-image: url('<?php echo esc_url($img_url); ?>');">

                <!-- Gradient overlay -->
                <div class="wl-hero-overlay" aria-hidden="true"></div>

                <!-- Content — centered -->
                <div class="wl-hero-content-wrap">
                    <div class="wl-hero-content">

                        <!-- Row 1: Date + Author với icon -->
                        <div class="wl-hero-meta">
                            <span class="wl-hero-meta-item">
                                <svg class="wl-meta-icon" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <rect x="1.5" y="2.5" width="13" height="12" rx="1.5" stroke="currentColor"
                                        stroke-width="1.2" />
                                    <path d="M1.5 6h13" stroke="currentColor" stroke-width="1.2" />
                                    <path d="M5 1v3M11 1v3" stroke="currentColor" stroke-width="1.2"
                                        stroke-linecap="round" />
                                </svg>
                                <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                    <?php echo get_the_date('F j, Y'); ?>
                                </time>
                            </span>

                            <span class="wl-hero-meta-div" aria-hidden="true"></span>

                            <span class="wl-hero-meta-item">
                                <svg class="wl-meta-icon" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor"
                                        stroke-width="1.2" />
                                    <path d="M2 14c0-2.21 2.686-4 6-4s6 1.79 6 4" stroke="currentColor"
                                        stroke-width="1.2" stroke-linecap="round" />
                                </svg>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                    tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                    <?php esc_html_e('by', 'wanderland'); ?>
                                    <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </span>
                        </div>

                        <!-- Row 2: Title -->
                        <h2 class="wl-hero-title">
                            <a href="<?php the_permalink(); ?>"
                                tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <!-- Row 3: Excerpt -->
                        <p class="wl-hero-excerpt">
                            <?php echo esc_html(wp_trim_words($excerpt, 22, '...')); ?>
                        </p>

                        <!-- Row 4: CTA Button -->
                        <div class="wl-hero-btn-wrap">
                            <a class="wl-hero-btn" href="<?php the_permalink(); ?>"
                                tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                <span><?php esc_html_e('Read More', 'wanderland'); ?></span>
                                <svg class="wl-btn-icon" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                    </div><!-- .wl-hero-content -->
                </div><!-- .wl-hero-content-wrap -->

            </div><!-- .wl-hero-slide -->

            <?php
                    $slide_index++;
                endwhile;
                wp_reset_postdata();
                ?>

        </div><!-- .wl-hero-track -->

        <!-- Arrow Prev -->
        <button class="wl-hero-arrow wl-hero-arrow--prev"
            aria-label="<?php esc_attr_e('Previous slide', 'wanderland'); ?>" type="button">
            <span class="wl-arrow-inner" aria-hidden="true">
                <svg viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="21.5" stroke="currentColor" stroke-opacity="0.5" />
                    <path d="M25 14L17 22L25 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </button>

        <!-- Arrow Next -->
        <button class="wl-hero-arrow wl-hero-arrow--next" aria-label="<?php esc_attr_e('Next slide', 'wanderland'); ?>"
            type="button">
            <span class="wl-arrow-inner" aria-hidden="true">
                <svg viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="21.5" stroke="currentColor" stroke-opacity="0.5" />
                    <path d="M19 14L27 22L19 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </button>

        <!-- Dot Pagination -->
        <div class="wl-hero-dots" role="tablist" aria-label="<?php esc_attr_e('Slide navigation', 'wanderland'); ?>">
            <?php for ($d = 0; $d < $slide_count; $d++): ?>
            <button class="wl-hero-dot <?php echo $d === 0 ? 'is-active' : ''; ?>" role="tab"
                aria-label="<?php printf(esc_attr__('Go to slide %d', 'wanderland'), $d + 1); ?>"
                aria-selected="<?php echo $d === 0 ? 'true' : 'false'; ?>" data-index="<?php echo esc_attr($d); ?>"
                type="button">
            </button>
            <?php endfor; ?>
        </div>

        <!-- Slide Counter -->
        <div class="wl-hero-counter" aria-live="polite" aria-atomic="true">
            <span class="wl-hero-counter-current">01</span>
            <span class="wl-hero-counter-sep" aria-hidden="true">/</span>
            <span class="wl-hero-counter-total">
                <?php echo str_pad($slide_count, 2, '0', STR_PAD_LEFT); ?>
            </span>
        </div>

        <!-- Progress Bar -->
        <div class="wl-hero-progress" aria-hidden="true">
            <div class="wl-hero-progress-bar"></div>
        </div>

        <!-- ── Paint Brush Stroke — asymmetric angled blocks ── -->
        <div class="wl-hero-brush" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 36" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,36
                         L0,22   L78,18
                         L78,36  L78,26   L155,22
                         L155,36 L155,20  L245,24
                         L245,36 L245,14  L360,18
                         L360,36 L360,22  L445,17
                         L445,36 L445,10  L540,14
                         L540,36 L540,18  L618,13
                         L618,36 L618,8   L720,12
                         L720,36 L720,16  L800,10
                         L800,36 L800,4   L895,9
                         L895,36 L895,14  L975,8
                         L975,36 L975,6   L1065,11
                         L1065,36 L1065,16 L1150,10
                         L1150,36 L1150,4  L1245,8
                         L1245,36 L1245,14 L1320,18
                         L1320,36 L1320,10 L1390,15
                         L1390,36 L1390,20 L1440,16
                         L1440,36 Z" />
            </svg>
        </div><!-- .wl-hero-brush -->

    </section>

    <?php else: ?>
    <section class="wl-hero-slider wl-hero-slider--empty">
        <div class="wl-hero-empty-msg">
            <?php if (current_user_can('edit_theme_options')): ?>
            <p>
                <?php esc_html_e('Chưa có bài viết nào.', 'wanderland'); ?>
                <a href="<?php echo esc_url(admin_url('themes.php?page=wl-home-sections')); ?>">
                    <?php esc_html_e('Cài đặt ngay →', 'wanderland'); ?>
                </a>
            </p>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>