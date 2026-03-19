<?php
/**
 * WANDERLAND — front-page.php  (v3 — centered hero layout)
 * Section 1: Hero Slider
 */

get_header();
?>

<main id="wl-main" class="wl-main-content">

    <?php
    $hero_query = wl_get_section_query( 'hero_slider' );

    if ( $hero_query && $hero_query->have_posts() ) :
        $slide_count = $hero_query->post_count;
    ?>

    <section class="wl-hero-slider" aria-label="<?php esc_attr_e( 'Featured Posts', 'wanderland' ); ?>"
        data-total="<?php echo esc_attr( $slide_count ); ?>">

        <div class="wl-hero-track" role="list">

            <?php
            $slide_index = 0;
            while ( $hero_query->have_posts() ) :
                $hero_query->the_post();

                $img_url = get_the_post_thumbnail_url( get_the_ID(), 'wl-hero' );
                if ( ! $img_url ) $img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );

                $excerpt = get_the_excerpt();
                if ( ! $excerpt ) {
                    $excerpt = wp_trim_words( get_the_content(), 22, '...' );
                }
            ?>

            <div class="wl-hero-slide <?php echo $slide_index === 0 ? 'is-active' : ''; ?>" role="listitem"
                aria-hidden="<?php echo $slide_index === 0 ? 'false' : 'true'; ?>"
                data-index="<?php echo esc_attr( $slide_index ); ?>"
                style="background-image: url('<?php echo esc_url( $img_url ); ?>');">

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
                                <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>">
                                    <?php echo get_the_date( 'F j, Y' ); ?>
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
                                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                                    tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                    <?php esc_html_e( 'by', 'wanderland' ); ?>
                                    <?php echo esc_html( get_the_author() ); ?>
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
                            <?php echo esc_html( wp_trim_words( $excerpt, 22, '...' ) ); ?>
                        </p>

                        <!-- Row 4: CTA Button -->
                        <div class="wl-hero-btn-wrap">
                            <a class="wl-hero-btn" href="<?php the_permalink(); ?>"
                                tabindex="<?php echo $slide_index === 0 ? '0' : '-1'; ?>">
                                <span><?php esc_html_e( 'Read More', 'wanderland' ); ?></span>
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
            aria-label="<?php esc_attr_e( 'Previous slide', 'wanderland' ); ?>" type="button">
            <span class="wl-arrow-inner" aria-hidden="true">
                <svg viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="21.5" stroke="currentColor" stroke-opacity="0.5" />
                    <path d="M25 14L17 22L25 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </button>

        <!-- Arrow Next -->
        <button class="wl-hero-arrow wl-hero-arrow--next"
            aria-label="<?php esc_attr_e( 'Next slide', 'wanderland' ); ?>" type="button">
            <span class="wl-arrow-inner" aria-hidden="true">
                <svg viewBox="0 0 44 44" fill="none">
                    <circle cx="22" cy="22" r="21.5" stroke="currentColor" stroke-opacity="0.5" />
                    <path d="M19 14L27 22L19 30" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </button>

        <!-- Dot Pagination -->
        <div class="wl-hero-dots" role="tablist" aria-label="<?php esc_attr_e( 'Slide navigation', 'wanderland' ); ?>">
            <?php for ( $d = 0; $d < $slide_count; $d++ ) : ?>
            <button class="wl-hero-dot <?php echo $d === 0 ? 'is-active' : ''; ?>" role="tab"
                aria-label="<?php printf( esc_attr__( 'Go to slide %d', 'wanderland' ), $d + 1 ); ?>"
                aria-selected="<?php echo $d === 0 ? 'true' : 'false'; ?>" data-index="<?php echo esc_attr( $d ); ?>"
                type="button">
            </button>
            <?php endfor; ?>
        </div>

        <!-- Slide Counter -->
        <div class="wl-hero-counter" aria-live="polite" aria-atomic="true">
            <span class="wl-hero-counter-current">01</span>
            <span class="wl-hero-counter-sep" aria-hidden="true">/</span>
            <span class="wl-hero-counter-total">
                <?php echo str_pad( $slide_count, 2, '0', STR_PAD_LEFT ); ?>
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

    <?php else : ?>
    <section class="wl-hero-slider wl-hero-slider--empty">
        <div class="wl-hero-empty-msg">
            <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
            <p>
                <?php esc_html_e( 'Chưa có bài viết nào.', 'wanderland' ); ?>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=wl-home-sections' ) ); ?>">
                    <?php esc_html_e( 'Cài đặt ngay →', 'wanderland' ); ?>
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
    $essentials_query = wl_get_section_query( 'travel_essentials', array(
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
                    <?php esc_html_e( 'Lorem ipsum dolor', 'wanderland' ); ?>
                </span>
                <h2 class="wl-essentials-title">
                    <?php esc_html_e( 'Travel essentials', 'wanderland' ); ?>
                    <span class="wl-essentials-highlight-wrap">
                        <span class="wl-essentials-highlight-text">
                            <?php esc_html_e( 'tips', 'wanderland' ); ?>
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
            <?php if ( $essentials_query && $essentials_query->have_posts() ) : ?>
            <div class="wl-essentials-list">

                <?php
                $item_index = 0;
                while ( $essentials_query->have_posts() ) : $essentials_query->the_post();
                    $img_url   = get_the_post_thumbnail_url( get_the_ID(), 'wl-card' );
                    if ( ! $img_url ) $img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                    $category  = get_the_category();
                    $cat_name  = ! empty( $category ) ? esc_html( $category[0]->name ) : '';
                    $cat_url   = ! empty( $category ) ? esc_url( get_category_link( $category[0]->term_id ) ) : '#';
                    $excerpt   = get_the_excerpt();
                    if ( ! $excerpt ) $excerpt = wp_trim_words( get_the_content(), 28, '...' );
                    $is_reversed = ( $item_index % 2 !== 0 ) ? ' is-reversed' : '';
                ?>

                <article class="wl-essentials-item<?php echo $is_reversed; ?>" itemscope
                    itemtype="https://schema.org/BlogPosting">

                    <!-- Image column -->
                    <div class="wl-essentials-image-col">
                        <a href="<?php the_permalink(); ?>" class="wl-essentials-img-link" tabindex="0">
                            <?php if ( $img_url ) : ?>
                            <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php the_title_attribute(); ?>"
                                loading="lazy" itemprop="image">
                            <?php else : ?>
                            <div class="wl-essentials-img-placeholder"></div>
                            <?php endif; ?>
                        </a>

                        <?php if ( $cat_name ) : ?>
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
                                    <?php esc_html_e( 'by', 'wanderland' ); ?>
                                    <a
                                        href="<?php echo esc_url( get_author_posts_url( get_the_author_meta('ID') ) ); ?>">
                                        <?php echo esc_html( get_the_author() ); ?>
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
                            <p><?php echo esc_html( wp_trim_words( $excerpt, 28, '...' ) ); ?></p>
                        </div>

                        <!-- Read More -->
                        <a href="<?php the_permalink(); ?>" class="wl-essentials-readmore">
                            <span><?php esc_html_e( 'Read More', 'wanderland' ); ?></span>
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
                endwhile; wp_reset_postdata(); ?>

            </div><!-- .wl-essentials-list -->

            <?php else : ?>
            <?php if ( current_user_can('edit_theme_options') ) : ?>
            <div class="wl-essentials-empty">
                <p>
                    <?php esc_html_e( 'Chưa có bài viết. ', 'wanderland' ); ?>
                    <a
                        href="<?php echo esc_url( admin_url('themes.php?page=wl-home-sections&tab=travel_essentials') ); ?>">
                        <?php esc_html_e( 'Cài đặt →', 'wanderland' ); ?>
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
                    $nl_image_id  = get_theme_mod( 'wl_newsletter_image' );
                    $nl_image_url = $nl_image_id
                        ? wp_get_attachment_image_url( $nl_image_id, 'large' )
                        : '';
                    ?>
                    <?php if ( $nl_image_url ) : ?>
                    <img src="<?php echo esc_url( $nl_image_url ); ?>"
                        alt="<?php esc_attr_e( 'Newsletter illustration', 'wanderland' ); ?>" loading="lazy"
                        class="wl-nl-deco-img">
                    <?php else : ?>
                    <!-- Placeholder decorative block when no image set -->
                    <div class="wl-nl-img-placeholder">
                        <span class="dashicons dashicons-format-image"></span>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                        <p>
                            <?php esc_html_e( 'Thêm ảnh trang trí tại ', 'wanderland' ); ?>
                            <a
                                href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=wl_newsletter' ) ); ?>">
                                <?php esc_html_e( 'Customizer', 'wanderland' ); ?>
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
                            <?php echo esc_html( get_theme_mod( 'wl_newsletter_tagline', 'Lorem ipsum dolor' ) ); ?>
                        </span>

                        <!-- Title with highlight -->
                        <h2 class="wl-nl-title">
                            <?php echo esc_html( get_theme_mod( 'wl_newsletter_title', 'Finding the perfect trails to hike is easy with' ) ); ?>
                            <span class="wl-nl-highlight-wrap">
                                <span class="wl-nl-highlight-text">
                                    <?php echo esc_html( get_theme_mod( 'wl_newsletter_highlight', 'newsletter' ) ); ?>
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
                            <?php echo esc_html( get_theme_mod( 'wl_newsletter_text', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididu nt ut labore et dolore minim veniam, quism.' ) ); ?>
                        </p>

                        <!-- Newsletter Form -->
                        <form class="wl-nl-form" method="post"
                            action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" novalidate>

                            <?php wp_nonce_field( 'wl_newsletter_subscribe', 'wl_nl_nonce' ); ?>
                            <input type="hidden" name="action" value="wl_newsletter_subscribe">
                            <input type="hidden" name="redirect_to"
                                value="<?php echo esc_url( home_url('/?nl=success') ); ?>">

                            <div class="wl-nl-form-row">
                                <input type="text" name="nl_name" class="wl-nl-input"
                                    placeholder="<?php esc_attr_e( 'Name', 'wanderland' ); ?>" autocomplete="name"
                                    required>

                                <input type="email" name="nl_email" class="wl-nl-input"
                                    placeholder="<?php esc_attr_e( 'E-mail', 'wanderland' ); ?>" autocomplete="email"
                                    required>

                                <button type="submit" class="wl-nl-submit">
                                    <span><?php esc_html_e( 'Subscribe', 'wanderland' ); ?></span>
                                    <svg viewBox="0 0 17 17" fill="none" aria-hidden="true">
                                        <line x1="1.5" y1="15.5" x2="15" y2="2" stroke="currentColor" stroke-width="1.4"
                                            stroke-linecap="round" />
                                        <polyline points="5,2 15,2 15,12" stroke="currentColor" stroke-width="1.4"
                                            stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                    </svg>
                                </button>
                            </div>

                            <?php if ( isset($_GET['nl']) && $_GET['nl'] === 'success' ) : ?>
                            <p class="wl-nl-success">
                                <?php esc_html_e( '✓ Đăng ký thành công! Cảm ơn bạn.', 'wanderland' ); ?>
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

</main>

<?php get_footer(); ?>