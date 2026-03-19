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

    <!-- ============================================================
         SECTION 2: TRAVEL ESSENTIALS TIPS
         Layout: image-left | content-right — 2 posts
         Data: Appearance > Home Sections > Travel Essentials
         Background: off-white với vân gỗ subtle
    ============================================================ -->

    <?php
    $essentials_query = wl_get_section_query('travel_essentials', array(
        'posts_per_page' => 2,
    ));
    ?>

    <section class="wl-essentials-section">

        <!-- Wood grain background pattern (CSS only, no image needed) -->
        <div class="wl-essentials-bg" aria-hidden="true"></div>

        <div class="wl-essentials-inner">

            <!-- ── Section Header ── -->
            <div class="wl-essentials-header">
                <span class="wl-essentials-tagline">
                    <?php esc_html_e('Lorem ipsum dolor', 'wanderland'); ?>
                </span>
                <h2 class="wl-essentials-title">
                    <?php esc_html_e('Travel essentials', 'wanderland'); ?>
                    <span class="wl-essentials-highlight-wrap">
                        <span class="wl-essentials-highlight-text">
                            <?php esc_html_e('tips', 'wanderland'); ?>
                        </span>
                        <!-- Painted highlight shape (2 SVG tabs + middle stretch) -->
                        <span class="wl-essentials-highlight-shape" aria-hidden="true">
                            <svg viewBox="0 0 16 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <polygon fill="currentColor"
                                    points="2.6,1 0.7,3.3 2,5.8 2.3,7.6 2.9,8.7 4.4,10.5 3.9,10.8 4.4,11.9 4.4,12.8 4.1,13.8 3.3,14.7 3.9,15.8 4.4,16.8 4,17.5 3.5,18.1 2.2,20.2 3.4,21.5 4.2,24.1 3.4,25.4 2.5,27.4 2.5,27.8 3.2,28.3 4.1,28.5 4.9,29 15,29 15,1" />
                            </svg>
                            <span class="wl-essentials-highlight-mid"></span>
                            <svg viewBox="0 0 14 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <polygon fill="currentColor"
                                    points="10,1 10.2,2.1 10.6,2.9 10.6,3.3 10.8,3.7 10.8,4.3 11,5 11,5.7 11,6.3 10.5,6.7 10.8,7.3 11,7.8 11.6,8.3 11.6,8.6 11.5,8.9 11.6,9.9 11.6,10.5 12.4,11.6 12.1,12 12.4,12.2 11.8,12.8 11.4,13.5 11.6,13.7 11.9,13.7 12,13.9 11.5,15.1 10.8,16 9.1,17.7 9.7,18.2 9.3,19 9.7,19.8 9.6,20.6 9.7,21.5 9.6,21.9 9.6,22.3 10.1,22.8 9.6,23.6 9.7,24 9.7,24.2 9.9,24.4 9.5,24.7 9.3,25.4 9.3,25.9 8.8,26.2 8.5,27.1 8.8,27.8 9.4,28.6 7.8,29 1,29 1,1" />
                            </svg>
                        </span>
                    </span>
                </h2>
            </div><!-- .wl-essentials-header -->

            <!-- ── Post List ── -->
            <?php if ($essentials_query && $essentials_query->have_posts()): ?>
            <div class="wl-essentials-list">

                <?php
                    $item_index = 0;
                    while ($essentials_query->have_posts()):
                        $essentials_query->the_post();
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'wl-card');
                        if (!$img_url)
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $category = get_the_category();
                        $cat_name = !empty($category) ? esc_html($category[0]->name) : '';
                        $cat_url = !empty($category) ? esc_url(get_category_link($category[0]->term_id)) : '#';
                        $excerpt = get_the_excerpt();
                        if (!$excerpt)
                            $excerpt = wp_trim_words(get_the_content(), 28, '...');
                        $is_reversed = ($item_index % 2 !== 0) ? ' is-reversed' : '';
                        ?>

                <article class="wl-essentials-item<?php echo $is_reversed; ?>" itemscope
                    itemtype="https://schema.org/BlogPosting">

                    <!-- Image column -->
                    <div class="wl-essentials-image-col">
                        <a href="<?php the_permalink(); ?>" class="wl-essentials-img-link" tabindex="0">
                            <?php if ($img_url): ?>
                            <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>"
                                loading="lazy" itemprop="image">
                            <?php else: ?>
                            <div class="wl-essentials-img-placeholder"></div>
                            <?php endif; ?>
                        </a>

                        <?php if ($cat_name): ?>
                        <!-- Category badge with highlight shape -->
                        <a href="<?php echo $cat_url; ?>" class="wl-essentials-cat-badge">
                            <span class="wl-essentials-cat-icon" aria-hidden="true">
                                <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1h5.5l5.5 5.5L6.5 13 1 7.5V1z" stroke="currentColor" stroke-width="1.2"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                            <?php echo $cat_name; ?>
                        </a>
                        <?php endif; ?>
                    </div><!-- .wl-essentials-image-col -->

                    <!-- Content column -->
                    <div class="wl-essentials-content-col">

                        <!-- Meta: date + author -->
                        <div class="wl-essentials-meta">
                            <span class="wl-essentials-meta-item">
                                <svg class="wl-ess-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                    <rect x="1" y="2" width="12" height="11" rx="1.5" stroke="currentColor"
                                        stroke-width="1.2" />
                                    <path d="M1 5.5h12" stroke="currentColor" stroke-width="1.2" />
                                    <path d="M4.5 1v2M9.5 1v2" stroke="currentColor" stroke-width="1.2"
                                        stroke-linecap="round" />
                                </svg>
                                <time itemprop="datePublished" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                    <?php echo get_the_date('F j, Y'); ?>
                                </time>
                            </span>
                            <span class="wl-essentials-meta-item">
                                <svg class="wl-ess-icon" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                    <path d="M7 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                        stroke-width="1.2" />
                                    <path d="M1.5 13c0-2 2.5-3.5 5.5-3.5S12.5 11 12.5 13" stroke="currentColor"
                                        stroke-width="1.2" stroke-linecap="round" />
                                </svg>
                                <span itemprop="author">
                                    <?php esc_html_e('by', 'wanderland'); ?>
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </span>
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="wl-essentials-post-title" itemprop="headline">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <!-- Excerpt -->
                        <div class="wl-essentials-excerpt" itemprop="description">
                            <p><?php echo esc_html(wp_trim_words($excerpt, 28, '...')); ?></p>
                        </div>

                        <!-- Read More -->
                        <a href="<?php the_permalink(); ?>" class="wl-essentials-readmore">
                            <span><?php esc_html_e('Read More', 'wanderland'); ?></span>
                            <svg class="wl-readmore-icon" viewBox="0 0 17 17" fill="none" aria-hidden="true">
                                <line x1="1.5" y1="15.5" x2="15" y2="2" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" />
                                <polyline points="5,2 15,2 15,12" stroke="currentColor" stroke-width="1.4"
                                    stroke-linecap="round" stroke-linejoin="round" fill="none" />
                            </svg>
                        </a>

                    </div><!-- .wl-essentials-content-col -->

                </article><!-- .wl-essentials-item -->

                <?php
                        $item_index++;
                    endwhile;
                    wp_reset_postdata(); ?>

            </div><!-- .wl-essentials-list -->

            <?php else: ?>
            <?php if (current_user_can('edit_theme_options')): ?>
            <div class="wl-essentials-empty">
                <p>
                    <?php esc_html_e('Chưa có bài viết. ', 'wanderland'); ?>
                    <a
                        href="<?php echo esc_url(admin_url('themes.php?page=wl-home-sections&tab=travel_essentials')); ?>">
                        <?php esc_html_e('Cài đặt →', 'wanderland'); ?>
                    </a>
                </p>
            </div>
            <?php endif; ?>
            <?php endif; ?>

        </div><!-- .wl-essentials-inner -->

    </section><!-- .wl-essentials-section -->

    <!-- ============================================================
         SECTION 3: NEWSLETTER
         Layout: image-left | title+form-right (50/50)
         Background: beige/cream + brush stroke edges top & bottom
    ============================================================ -->

    <section class="wl-newsletter-section">

        <!-- Top brush stroke (white → beige transition) -->
        <div class="wl-nl-brush wl-nl-brush--top" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 40" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,0
                         L0,18   L72,22
                         L72,0   L72,14   L160,10
                         L160,0  L160,16  L255,12
                         L255,0  L255,20  L348,15
                         L348,0  L348,18  L440,13
                         L440,0  L440,22  L530,16
                         L530,0  L530,20  L615,14
                         L615,0  L615,24  L705,18
                         L705,0  L705,20  L790,13
                         L790,0  L790,22  L878,16
                         L878,0  L878,18  L965,12
                         L965,0  L965,20  L1055,15
                         L1055,0 L1055,22 L1140,17
                         L1140,0 L1140,18 L1230,12
                         L1230,0 L1230,20 L1315,15
                         L1315,0 L1315,16 L1390,20
                         L1390,0 L1390,14 L1440,18
                         L1440,0 Z" />
            </svg>
        </div>

        <div class="wl-nl-inner">
            <div class="wl-nl-grid">

                <!-- Left: decorative image -->
                <div class="wl-nl-image-col">
                    <?php
                    $nl_image_id = get_theme_mod('wl_newsletter_image');
                    $nl_image_url = $nl_image_id
                        ? wp_get_attachment_image_url($nl_image_id, 'large')
                        : '';
                    ?>
                    <?php if ($nl_image_url): ?>
                    <img src="<?php echo esc_url($nl_image_url); ?>"
                        alt="<?php esc_attr_e('Newsletter illustration', 'wanderland'); ?>" loading="lazy"
                        class="wl-nl-deco-img">
                    <?php else: ?>
                    <!-- Placeholder decorative block when no image set -->
                    <div class="wl-nl-img-placeholder">
                        <span class="dashicons dashicons-format-image"></span>
                        <?php if (current_user_can('edit_theme_options')): ?>
                        <p>
                            <?php esc_html_e('Thêm ảnh trang trí tại ', 'wanderland'); ?>
                            <a
                                href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=wl_newsletter')); ?>">
                                <?php esc_html_e('Customizer', 'wanderland'); ?>
                            </a>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div><!-- .wl-nl-image-col -->

                <!-- Right: content + form -->
                <div class="wl-nl-content-col">

                    <div class="wl-nl-content">

                        <!-- Tagline -->
                        <span class="wl-nl-tagline">
                            <?php echo esc_html(get_theme_mod('wl_newsletter_tagline', 'Lorem ipsum dolor')); ?>
                        </span>

                        <!-- Title with highlight -->
                        <h2 class="wl-nl-title">
                            <?php echo esc_html(get_theme_mod('wl_newsletter_title', 'Finding the perfect trails to hike is easy with')); ?>
                            <span class="wl-nl-highlight-wrap">
                                <span class="wl-nl-highlight-text">
                                    <?php echo esc_html(get_theme_mod('wl_newsletter_highlight', 'newsletter')); ?>
                                </span>
                                <span class="wl-nl-highlight-shape" aria-hidden="true">
                                    <svg viewBox="0 0 16 30" fill="none">
                                        <polygon fill="currentColor"
                                            points="2.6,1 0.7,3.3 2,5.8 2.3,7.6 2.9,8.7 4.4,10.5 3.9,10.8 4.4,11.9 4.4,12.8 4.1,13.8 3.3,14.7 3.9,15.8 4.4,16.8 4,17.5 3.5,18.1 2.2,20.2 3.4,21.5 4.2,24.1 3.4,25.4 2.5,27.4 2.5,27.8 3.2,28.3 4.1,28.5 4.9,29 15,29 15,1" />
                                    </svg>
                                    <span class="wl-nl-highlight-mid"></span>
                                    <svg viewBox="0 0 14 30" fill="none">
                                        <polygon fill="currentColor"
                                            points="10,1 10.2,2.1 10.6,2.9 10.6,3.3 10.8,3.7 10.8,4.3 11,5 11,5.7 11,6.3 10.5,6.7 10.8,7.3 11,7.8 11.6,8.3 11.6,8.6 11.5,8.9 11.6,9.9 11.6,10.5 12.4,11.6 12.1,12 12.4,12.2 11.8,12.8 11.4,13.5 11.6,13.7 11.9,13.7 12,13.9 11.5,15.1 10.8,16 9.1,17.7 9.7,18.2 9.3,19 9.7,19.8 9.6,20.6 9.7,21.5 9.6,21.9 9.6,22.3 10.1,22.8 9.6,23.6 9.7,24 9.7,24.2 9.9,24.4 9.5,24.7 9.3,25.4 9.3,25.9 8.8,26.2 8.5,27.1 8.8,27.8 9.4,28.6 7.8,29 1,29 1,1" />
                                    </svg>
                                </span>
                            </span>
                        </h2>

                        <!-- Body text -->
                        <p class="wl-nl-body">
                            <?php echo esc_html(get_theme_mod('wl_newsletter_text', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididu nt ut labore et dolore minim veniam, quism.')); ?>
                        </p>

                        <!-- Newsletter Form -->
                        <form class="wl-nl-form" method="post"
                            action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>

                            <?php wp_nonce_field('wl_newsletter_subscribe', 'wl_nl_nonce'); ?>
                            <input type="hidden" name="action" value="wl_newsletter_subscribe">
                            <input type="hidden" name="redirect_to"
                                value="<?php echo esc_url(home_url('/?nl=success')); ?>">

                            <div class="wl-nl-form-row">
                                <input type="text" name="nl_name" class="wl-nl-input"
                                    placeholder="<?php esc_attr_e('Name', 'wanderland'); ?>" autocomplete="name"
                                    required>

                                <input type="email" name="nl_email" class="wl-nl-input"
                                    placeholder="<?php esc_attr_e('E-mail', 'wanderland'); ?>" autocomplete="email"
                                    required>

                                <button type="submit" class="wl-nl-submit">
                                    <span><?php esc_html_e('Subscribe', 'wanderland'); ?></span>
                                    <svg viewBox="0 0 17 17" fill="none" aria-hidden="true">
                                        <line x1="1.5" y1="15.5" x2="15" y2="2" stroke="currentColor" stroke-width="1.4"
                                            stroke-linecap="round" />
                                        <polyline points="5,2 15,2 15,12" stroke="currentColor" stroke-width="1.4"
                                            stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                </button>
                            </div>

                            <?php if (isset($_GET['nl']) && $_GET['nl'] === 'success'): ?>
                            <p class="wl-nl-success">
                                <?php esc_html_e('✓ Đăng ký thành công! Cảm ơn bạn.', 'wanderland'); ?>
                            </p>
                            <?php endif; ?>

                        </form>

                    </div><!-- .wl-nl-content -->

                </div><!-- .wl-nl-content-col -->

            </div><!-- .wl-nl-grid -->
        </div><!-- .wl-nl-inner -->

        <!-- Bottom brush stroke (beige → white transition) -->
        <div class="wl-nl-brush wl-nl-brush--bottom" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 40" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,40
                         L0,22   L68,18
                         L68,40  L68,26  L158,22
                         L158,40 L158,28 L248,24
                         L248,40 L248,20 L340,25
                         L340,40 L340,22 L428,27
                         L428,40 L428,18 L518,22
                         L518,40 L518,26 L608,20
                         L608,40 L608,24 L698,28
                         L698,40 L698,16 L788,22
                         L788,40 L788,26 L875,20
                         L875,40 L875,18 L962,24
                         L962,40 L962,28 L1050,22
                         L1050,40 L1050,16 L1138,20
                         L1138,40 L1138,24 L1228,18
                         L1228,40 L1228,22 L1318,26
                         L1318,40 L1318,20 L1390,24
                         L1390,40 L1390,18 L1440,22
                         L1440,40 Z" />
            </svg>
        </div>

    </section><!-- .wl-newsletter-section -->

    <!-- ============================================================
         SECTION 4: FEATURED BLOG POSTS — 3-up slider
         Data: Appearance > Home Sections > Featured Posts
         Layout: 3 cards visible, arrows left/right, slide 1 at a time
    ============================================================ -->

    <?php
    $featured_posts_count = (int) get_theme_mod('wl_featured_posts_count', 6);
    $featured_query = wl_get_section_query('featured_posts', array(
        'posts_per_page' => $featured_posts_count,
    ));
    ?>

    <section class="wl-featured-section">

        <!-- Wood grain bg -->
        <div class="wl-featured-bg" aria-hidden="true"></div>

        <div class="wl-featured-inner">

            <!-- Section Header -->
            <div class="wl-featured-header">
                <span class="wl-featured-tagline">
                    <?php esc_html_e('Lorem ipsum dolore', 'wanderland'); ?>
                </span>
                <h2 class="wl-featured-title">
                    <?php esc_html_e('Featured blog', 'wanderland'); ?>
                    <span class="wl-featured-highlight-wrap">
                        <span class="wl-featured-highlight-text">
                            <?php esc_html_e('posts', 'wanderland'); ?>
                        </span>
                        <span class="wl-featured-highlight-shape" aria-hidden="true">
                            <svg viewBox="0 0 16 30" fill="none">
                                <polygon fill="currentColor"
                                    points="2.6,1 0.7,3.3 2,5.8 2.3,7.6 2.9,8.7 4.4,10.5 3.9,10.8 4.4,11.9 4.4,12.8 4.1,13.8 3.3,14.7 3.9,15.8 4.4,16.8 4,17.5 3.5,18.1 2.2,20.2 3.4,21.5 4.2,24.1 3.4,25.4 2.5,27.4 2.5,27.8 3.2,28.3 4.1,28.5 4.9,29 15,29 15,1" />
                            </svg>
                            <span class="wl-featured-highlight-mid"></span>
                            <svg viewBox="0 0 14 30" fill="none">
                                <polygon fill="currentColor"
                                    points="10,1 10.2,2.1 10.6,2.9 10.6,3.3 10.8,3.7 10.8,4.3 11,5 11,5.7 11,6.3 10.5,6.7 10.8,7.3 11,7.8 11.6,8.3 11.6,8.6 11.5,8.9 11.6,9.9 11.6,10.5 12.4,11.6 12.1,12 12.4,12.2 11.8,12.8 11.4,13.5 11.6,13.7 11.9,13.7 12,13.9 11.5,15.1 10.8,16 9.1,17.7 9.7,18.2 9.3,19 9.7,19.8 9.6,20.6 9.7,21.5 9.6,21.9 9.6,22.3 10.1,22.8 9.6,23.6 9.7,24 9.7,24.2 9.9,24.4 9.5,24.7 9.3,25.4 9.3,25.9 8.8,26.2 8.5,27.1 8.8,27.8 9.4,28.6 7.8,29 1,29 1,1" />
                            </svg>
                        </span>
                    </span>
                </h2>
            </div>

            <!-- Slider wrapper -->
            <?php if ($featured_query && $featured_query->have_posts()):
                $all_posts = $featured_query->posts;
                $total_posts = count($all_posts);
                wp_reset_postdata();
                ?>

            <div class="wl-featured-slider-wrap" data-total="<?php echo esc_attr($total_posts); ?>" data-visible="3">

                <!-- Arrow Prev -->
                <button class="wl-featured-arrow wl-featured-arrow--prev" type="button"
                    aria-label="<?php esc_attr_e('Previous posts', 'wanderland'); ?>">
                    <svg viewBox="0 0 44 44" fill="none">
                        <path d="M25 14L17 22L25 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <!-- Overflow mask -->
                <div class="wl-featured-viewport">
                    <div class="wl-featured-track">

                        <?php foreach ($all_posts as $post):
                                setup_postdata($post);
                                $img_url = get_the_post_thumbnail_url($post->ID, 'wl-card');
                                if (!$img_url)
                                    $img_url = get_the_post_thumbnail_url($post->ID, 'full');
                                $category = get_the_category($post->ID);
                                $cat_name = !empty($category) ? esc_html($category[0]->name) : '';
                                $cat_url = !empty($category) ? esc_url(get_category_link($category[0]->term_id)) : '#';
                                ?>

                        <article class="wl-featured-card" itemscope itemtype="https://schema.org/BlogPosting">

                            <!-- Image -->
                            <div class="wl-featured-card-image">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="wl-featured-img-link">
                                    <?php if ($img_url): ?>
                                    <img src="<?php echo esc_url($img_url); ?>"
                                        alt="<?php echo esc_attr($post->post_title); ?>" loading="lazy"
                                        itemprop="image">
                                    <?php else: ?>
                                    <div class="wl-featured-img-placeholder"></div>
                                    <?php endif; ?>
                                </a>

                                <!-- Category badge -->
                                <?php if ($cat_name): ?>
                                <a href="<?php echo $cat_url; ?>" class="wl-featured-cat-badge">
                                    <span class="wl-featured-cat-icon" aria-hidden="true">
                                        <svg viewBox="0 0 14 14" fill="none">
                                            <path d="M1 1h5.5l5.5 5.5L6.5 13 1 7.5V1z" stroke="currentColor"
                                                stroke-width="1.2" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <?php echo $cat_name; ?>
                                </a>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="wl-featured-card-content">

                                <!-- Meta -->
                                <div class="wl-featured-meta">
                                    <span class="wl-featured-meta-item">
                                        <svg class="wl-feat-icon" viewBox="0 0 14 14" fill="none">
                                            <rect x="1" y="2" width="12" height="11" rx="1.5" stroke="currentColor"
                                                stroke-width="1.2" />
                                            <path d="M1 5.5h12M4.5 1v2M9.5 1v2" stroke="currentColor" stroke-width="1.2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <time itemprop="datePublished"
                                            datetime="<?php echo get_the_date('Y-m-d', $post->ID); ?>">
                                            <?php echo get_the_date('F j, Y', $post->ID); ?>
                                        </time>
                                    </span>
                                    <span class="wl-featured-meta-item">
                                        <svg class="wl-feat-icon" viewBox="0 0 14 14" fill="none">
                                            <path d="M7 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                                stroke-width="1.2" />
                                            <path d="M1.5 13c0-2 2.5-3.5 5.5-3.5S12.5 11 12.5 13" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" />
                                        </svg>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                            itemprop="author">
                                            <?php echo esc_html('by ' . get_the_author()); ?>
                                        </a>
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="wl-featured-card-title" itemprop="headline">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                        <?php echo esc_html($post->post_title); ?>
                                    </a>
                                </h3>

                            </div><!-- .wl-featured-card-content -->

                        </article><!-- .wl-featured-card -->

                        <?php endforeach;
                            wp_reset_postdata(); ?>

                    </div><!-- .wl-featured-track -->
                </div><!-- .wl-featured-viewport -->

                <!-- Arrow Next -->
                <button class="wl-featured-arrow wl-featured-arrow--next" type="button"
                    aria-label="<?php esc_attr_e('Next posts', 'wanderland'); ?>">
                    <svg viewBox="0 0 44 44" fill="none">
                        <path d="M19 14L27 22L19 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

            </div><!-- .wl-featured-slider-wrap -->

            <?php else: ?>
            <?php if (current_user_can('edit_theme_options')): ?>
            <div class="wl-featured-empty">
                <p>
                    <?php esc_html_e('Chưa có bài viết. ', 'wanderland'); ?>
                    <a href="<?php echo esc_url(admin_url('themes.php?page=wl-home-sections&tab=featured_posts')); ?>">
                        <?php esc_html_e('Cài đặt →', 'wanderland'); ?>
                    </a>
                </p>
            </div>
            <?php endif; ?>
            <?php endif; ?>

        </div><!-- .wl-featured-inner -->

    </section><!-- .wl-featured-section -->

    <!-- ============================================================
         SECTION 5: DESTINATIONS TIMELINE
         Data: WP Admin → Destinations (CPT wl_destination)
         Layout: horizontal scroll, items alternate above/below dashed line
         Background: off-white + SVG topographic map contours
    ============================================================ -->

    <?php
    $dest_count = (int) get_theme_mod('wl_destinations_count', 9);
    $dest_query = wl_get_destinations($dest_count);
    ?>

    <section class="wl-dest-section">

        <!-- Topographic map background (SVG inline) -->
        <div class="wl-dest-topo" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 420" preserveAspectRatio="xMidYMid slice">
                <!-- Concentric topo contour rings — organic, map-like -->
                <g fill="none" stroke="rgba(180,165,140,0.18)" stroke-width="1">
                    <ellipse cx="720" cy="210" rx="680" ry="170" />
                    <ellipse cx="720" cy="210" rx="580" ry="140" />
                    <ellipse cx="720" cy="210" rx="480" ry="112" />
                    <ellipse cx="720" cy="210" rx="380" ry="86" />
                    <ellipse cx="720" cy="210" rx="280" ry="62" />
                    <ellipse cx="720" cy="210" rx="180" ry="40" />
                    <!-- Secondary cluster left -->
                    <ellipse cx="200" cy="280" rx="320" ry="130" />
                    <ellipse cx="200" cy="280" rx="240" ry="96" />
                    <ellipse cx="200" cy="280" rx="160" ry="64" />
                    <ellipse cx="200" cy="280" rx="80" ry="32" />
                    <!-- Secondary cluster right -->
                    <ellipse cx="1240" cy="150" rx="300" ry="120" />
                    <ellipse cx="1240" cy="150" rx="220" ry="88" />
                    <ellipse cx="1240" cy="150" rx="140" ry="56" />
                    <ellipse cx="1240" cy="150" rx="60" ry="24" />
                </g>
            </svg>
        </div>

        <!-- Watermark "DESTINATIONS" -->
        <div class="wl-dest-watermark" aria-hidden="true">DESTINATIONS</div>

        <div class="wl-dest-inner">

            <?php if ($dest_query->have_posts()):
                $destinations = $dest_query->posts;
                $total_dest = count($destinations);
                wp_reset_postdata();
                ?>

            <!-- Scrollable timeline container -->
            <div class="wl-dest-scroll-wrap" tabindex="0" role="region"
                aria-label="<?php esc_attr_e('Destinations timeline', 'wanderland'); ?>">

                <div class="wl-dest-track">

                    <!-- SVG dashed wavy line + pin dots -->
                    <svg class="wl-dest-line-svg" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                        preserveAspectRatio="none">
                        <!-- JS will draw this path dynamically based on item positions -->
                        <path class="wl-dest-path" fill="none" stroke="#b0a898" stroke-width="1.5"
                            stroke-dasharray="6 5" />
                        <!-- Pin dots injected by JS -->
                        <g class="wl-dest-pins"></g>
                    </svg>

                    <!-- Destination items -->
                    <?php foreach ($destinations as $i => $dest_post):
                            setup_postdata($dest_post);
                            $img_url = get_the_post_thumbnail_url($dest_post->ID, 'thumbnail');
                            $lat = get_post_meta($dest_post->ID, '_wl_dest_lat', true);
                            $lng = get_post_meta($dest_post->ID, '_wl_dest_lng', true);
                            $ext_url = get_post_meta($dest_post->ID, '_wl_dest_url', true);
                            $link_url = $ext_url ?: get_permalink($dest_post->ID);
                            $excerpt = get_the_excerpt($dest_post->ID);
                            if (!$excerpt)
                                $excerpt = wp_trim_words($dest_post->post_content, 8, '');
                            // Alternate: even items go above line, odd below
                            $position = ($i % 2 === 0) ? 'above' : 'below';
                            ?>

                    <a class="wl-dest-item wl-dest-item--<?php echo $position; ?>"
                        href="<?php echo esc_url($link_url); ?>" data-index="<?php echo esc_attr($i); ?>">

                        <div class="wl-dest-item-inner">

                            <!-- Image (flag or photo) -->
                            <?php if ($img_url): ?>
                            <div class="wl-dest-img-wrap">
                                <img src="<?php echo esc_url($img_url); ?>"
                                    alt="<?php echo esc_attr($dest_post->post_title); ?>" loading="lazy">
                            </div>
                            <?php endif; ?>

                            <!-- Text -->
                            <div class="wl-dest-text">
                                <h6 class="wl-dest-title">
                                    <?php echo esc_html($dest_post->post_title); ?>
                                </h6>
                                <?php if ($excerpt): ?>
                                <p class="wl-dest-desc">
                                    <?php echo esc_html(wp_trim_words($excerpt, 6, '')); ?>
                                </p>
                                <?php endif; ?>
                                <?php if ($lat && $lng): ?>
                                <p class="wl-dest-coord"><?php echo esc_html($lat); ?></p>
                                <p class="wl-dest-coord"><?php echo esc_html($lng); ?></p>
                                <?php endif; ?>
                            </div>

                        </div><!-- .wl-dest-item-inner -->

                        <!-- Pin connector dot -->
                        <span class="wl-dest-pin" aria-hidden="true"></span>

                    </a><!-- .wl-dest-item -->

                    <?php endforeach;
                        wp_reset_postdata(); ?>

                </div><!-- .wl-dest-track -->

            </div><!-- .wl-dest-scroll-wrap -->

            <?php else: ?>
            <?php if (current_user_can('edit_posts')): ?>
            <div class="wl-dest-empty">
                <p>
                    <?php esc_html_e('Chưa có địa danh nào. ', 'wanderland'); ?>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=wl_destination')); ?>">
                        <?php esc_html_e('Thêm ngay →', 'wanderland'); ?>
                    </a>
                </p>
            </div>
            <?php endif; ?>
            <?php endif; ?>

        </div><!-- .wl-dest-inner -->

    </section><!-- .wl-dest-section -->

    <!-- ============================================================
         SECTION 6: DESTINATION CATEGORIES + LATEST POSTS + CTA
         Top: 6 destination categories (taxonomy icons + count)
         Bottom-left: 4 latest posts 2×2 grid
         Bottom-right: tagline + title + body + social share
    ============================================================ -->

    <section class="wl-dcl-section">

        <!-- Wood grain bg -->
        <div class="wl-dcl-bg" aria-hidden="true"></div>

        <div class="wl-dcl-inner">

            <!-- ── Part 1: Destination Category Icons ── -->
            <?php
            // Get destination categories (taxonomy: wl_destination_cat or category)
            // Using destination CPT's built-in categories — or fallback to post categories
            $dest_cats = get_terms(array(
                'taxonomy' => 'wl_destination_cat',
                'hide_empty' => false,
                'number' => 6,
                'orderby' => 'name',
            ));

            // Fallback to regular post categories if custom taxonomy not registered yet
            if (is_wp_error($dest_cats) || empty($dest_cats)) {
                $dest_cats = get_terms(array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                    'number' => 6,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'exclude' => array(get_option('default_category')),
                ));
            }
            ?>

            <?php if (!empty($dest_cats) && !is_wp_error($dest_cats)): ?>
            <div class="wl-dcl-cats-row">
                <?php foreach ($dest_cats as $cat):
                        $cat_url = get_term_link($cat);
                        $cat_count = $cat->count;
                        // Thumbnail from term meta (set via customizer or term edit)
                        $cat_img = get_term_meta($cat->term_id, '_wl_cat_image', true);
                        $cat_img_h = get_term_meta($cat->term_id, '_wl_cat_image_hover', true);
                        ?>
                <a class="wl-dcl-cat-item" href="<?php echo esc_url(is_wp_error($cat_url) ? '#' : $cat_url); ?>">
                    <div class="wl-dcl-cat-image">
                        <?php if ($cat_img): ?>
                        <img class="wl-dcl-cat-img-default" src="<?php echo esc_url($cat_img); ?>"
                            alt="<?php echo esc_attr($cat->name); ?>" loading="lazy">
                        <?php if ($cat_img_h): ?>
                        <img class="wl-dcl-cat-img-hover" src="<?php echo esc_url($cat_img_h); ?>" alt="" loading="lazy"
                            aria-hidden="true">
                        <?php endif; ?>
                        <?php else: ?>
                        <!-- SVG placeholder icon -->
                        <svg class="wl-dcl-cat-icon-svg" viewBox="0 0 92 74" fill="none" aria-hidden="true">
                            <rect x="8" y="12" width="76" height="50" rx="4" stroke="currentColor" stroke-width="2" />
                            <path d="M8 28h76M28 12v16M64 12v16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <circle cx="46" cy="46" r="8" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <?php endif; ?>

                        <!-- Highlight shape (SVG tabs) -->
                        <span class="wl-dcl-cat-highlight" aria-hidden="true">
                            <svg viewBox="0 0 14 28" fill="none">
                                <polygon fill="currentColor"
                                    points="1.9,0 0,2.3 1.3,4.8 1.6,6.6 2.2,7.7 3.7,9.5 3.2,9.8 3.7,10.9 3.7,11.8 3.4,12.8 2.6,13.7 3.2,14.8 3.7,15.8 3.3,16.5 2.8,17.1 1.5,19.2 2.7,20.5 3.5,23.1 2.7,24.4 1.8,26.4 1.8,26.8 2.5,27.3 3.4,27.5 4.2,28 14,28 14,0" />
                            </svg>
                            <span class="wl-dcl-hl-mid"></span>
                            <svg viewBox="0 0 12 28" fill="none">
                                <polygon fill="currentColor"
                                    points="9.1,0 9.3,1.1 9.7,1.9 9.7,2.3 9.9,2.7 9.9,3.3 10.1,4 10.1,4.7 10.1,5.3 9.6,5.7 9.9,6.3 10.1,6.8 10.7,7.3 10.7,7.6 10.6,7.9 10.7,8.9 10.7,9.5 11.5,10.6 11.2,11 11.5,11.2 10.9,11.8 10.5,12.5 10.7,12.7 11,12.7 11.1,12.9 10.6,14.1 9.9,15 8.2,16.7 8.8,17.2 8.4,18 8.8,18.8 8.7,19.6 8.8,20.5 8.7,20.9 8.7,21.3 9.2,21.8 8.7,22.6 8.8,23 8.8,23.2 9,23.4 8.6,23.7 8.4,24.4 8.4,24.9 7.9,25.2 7.6,26.1 7.9,26.8 8.5,27.6 6.9,28 0,28 0,0" />
                            </svg>
                        </span>
                    </div>
                    <div class="wl-dcl-cat-text">
                        <p class="wl-dcl-cat-count">
                            <?php echo esc_html($cat_count . ' ' . ($cat_count === 1 ? __('destination', 'wanderland') : __('destinations', 'wanderland'))); ?>
                        </p>
                        <h6 class="wl-dcl-cat-name"><?php echo esc_html($cat->name); ?></h6>
                    </div>
                </a>
                <?php endforeach; ?>
            </div><!-- .wl-dcl-cats-row -->
            <?php endif; ?>

            <!-- ── Part 2: Posts Grid + CTA ── -->
            <div class="wl-dcl-bottom-row">

                <!-- Left: 2×2 post grid -->
                <div class="wl-dcl-posts-col">
                    <?php
                    $dcl_query = wl_get_section_query('dcl_posts', array(
                        'posts_per_page' => 4,
                    ));
                    ?>
                    <?php if ($dcl_query && $dcl_query->have_posts()): ?>
                    <div class="wl-dcl-posts-grid">
                        <?php while ($dcl_query->have_posts()):
                                $dcl_query->the_post();
                                $img_url = get_the_post_thumbnail_url(get_the_ID(), 'wl-card');
                                if (!$img_url)
                                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                ?>
                        <article class="wl-dcl-post-item" itemscope itemtype="https://schema.org/BlogPosting">
                            <a class="wl-dcl-post-img-link" href="<?php the_permalink(); ?>">
                                <?php if ($img_url): ?>
                                <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>"
                                    loading="lazy" itemprop="image">
                                <?php else: ?>
                                <div class="wl-dcl-post-img-placeholder"></div>
                                <?php endif; ?>
                            </a>
                            <div class="wl-dcl-post-content">
                                <div class="wl-dcl-post-meta">
                                    <span class="wl-dcl-meta-item">
                                        <svg viewBox="0 0 14 14" fill="none" class="wl-dcl-meta-icon">
                                            <rect x="1" y="2" width="12" height="11" rx="1.5" stroke="currentColor"
                                                stroke-width="1.2" />
                                            <path d="M1 5.5h12M4.5 1v2M9.5 1v2" stroke="currentColor" stroke-width="1.2"
                                                stroke-linecap="round" />
                                        </svg>
                                        <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                            <?php echo get_the_date('F j, Y'); ?>
                                        </time>
                                    </span>
                                    <span class="wl-dcl-meta-item">
                                        <svg viewBox="0 0 14 14" fill="none" class="wl-dcl-meta-icon">
                                            <path d="M7 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                                stroke-width="1.2" />
                                            <path d="M1.5 13c0-2 2.5-3.5 5.5-3.5S12.5 11 12.5 13" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" />
                                        </svg>
                                        <a
                                            href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo esc_html('by ' . get_the_author()); ?>
                                        </a>
                                    </span>
                                </div>
                                <h5 class="wl-dcl-post-title" itemprop="headline">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h5>
                            </div>
                        </article>
                        <?php endwhile;
                            wp_reset_postdata(); ?>
                    </div><!-- .wl-dcl-posts-grid -->
                    <?php else: ?>
                    <?php if (current_user_can('edit_theme_options')): ?>
                    <p class="wl-dcl-empty">
                        <?php esc_html_e('Chưa có bài viết.', 'wanderland'); ?>
                        <a href="<?php echo esc_url(admin_url('themes.php?page=wl-home-sections')); ?>">
                            <?php esc_html_e('Cài đặt →', 'wanderland'); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                    <?php endif; ?>
                </div><!-- .wl-dcl-posts-col -->

                <!-- Right: CTA text + social share -->
                <div class="wl-dcl-cta-col">
                    <div class="wl-dcl-cta-inner">
                        <span class="wl-dcl-cta-tagline">
                            <?php echo esc_html(get_theme_mod('wl_dcl_tagline', 'Lorem ipsum dolor sit amet')); ?>
                        </span>
                        <h3 class="wl-dcl-cta-title">
                            <?php echo esc_html(get_theme_mod('wl_dcl_title', 'HOW TO FIND YOUR DIGITAL RESORT')); ?>
                        </h3>
                        <p class="wl-dcl-cta-body">
                            <?php echo esc_html(get_theme_mod('wl_dcl_body', 'Lorem ipsum dolor sit amet, conse ctetur nus adipisic ing elit, sed do eiusmod tempor incididu nt ut labore et dolore magna aliqua. Ut enim ad minim veniam.')); ?>
                        </p>

                        <!-- Social share -->
                        <div class="wl-dcl-social">
                            <ul class="wl-dcl-social-list">
                                <li>
                                    <a href="#" aria-label="Share on Facebook"
                                        onclick="window.open('https://www.facebook.com/sharer.php?u=<?php echo urlencode(home_url('/')); ?>','sharer','toolbar=0,status=0,width=620,height=280'); return false;">
                                        <i class="ion-social-facebook" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="Share on Twitter"
                                        onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo urlencode(home_url('/')); ?>','popupwindow','scrollbars=yes,width=800,height=400'); return false;">
                                        <i class="ion-social-twitter" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="Share on Pinterest"
                                        onclick="window.open('https://pinterest.com/pin/create/button/?url=<?php echo urlencode(home_url('/')); ?>','popupwindow','scrollbars=yes,width=800,height=400'); return false;">
                                        <i class="ion-social-pinterest" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" aria-label="Share on Tumblr"
                                        onclick="window.open('https://www.tumblr.com/share/link?url=<?php echo urlencode(home_url('/')); ?>','popupwindow','scrollbars=yes,width=800,height=400'); return false;">
                                        <i class="ion-social-tumblr" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                            <span class="wl-dcl-social-label"><?php esc_html_e('Share', 'wanderland'); ?></span>
                        </div>

                    </div>
                </div><!-- .wl-dcl-cta-col -->

            </div><!-- .wl-dcl-bottom-row -->

        </div><!-- .wl-dcl-inner -->

    </section><!-- .wl-dcl-section -->

</main>

<?php get_footer(); ?>