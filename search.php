<?php
/**
 * WANDERLAND — search.php
 * Search results page — same design as archive
 */

get_header();

global $wpdb;

// ── Search keyword ───────────────────────────────────────────
$keyword = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';

// ── Sort filter ──────────────────────────────────────────────
$current_sort = isset($_GET['sort']) ? sanitize_key($_GET['sort']) : 'relevance';

$sort_map = array(
    'relevance' => array('orderby' => 'relevance', 'order' => 'DESC'),
    'date_desc' => array('orderby' => 'date', 'order' => 'DESC'),
    'date_asc' => array('orderby' => 'date', 'order' => 'ASC'),
    'title_asc' => array('orderby' => 'title', 'order' => 'ASC'),
);
$sort = isset($sort_map[$current_sort]) ? $sort_map[$current_sort] : $sort_map['relevance'];

// ── Paged ────────────────────────────────────────────────────
$paged = max(1, get_query_var('paged') ?: (get_query_var('page') ?: 1));

// ── Search query ─────────────────────────────────────────────
$search_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
    'orderby' => $sort['orderby'],
    'order' => $sort['order'],
);

if (!empty($keyword)) {
    $search_args['s'] = $keyword;
}

$search_query = new WP_Query($search_args);
$total_posts = $search_query->found_posts;
$max_pages = $search_query->max_num_pages;

// Base URL — keep keyword, reset paged
$base_url = home_url('/?s=' . urlencode($keyword));
?>

<main id="wl-search-main" class="wl-archive-main wl-search-main">

    <!-- ============================================================
         PAGE HEADER
    ============================================================ -->
    <div class="wl-archive-page-header">
        <div class="wl-archive-container">

            <!-- Breadcrumb -->
            <nav class="wl-archive-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'wanderland'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php esc_html_e('Home', 'wanderland'); ?>
                </a>
                <span class="wl-bc-sep" aria-hidden="true">/</span>
                <span><?php esc_html_e('Search', 'wanderland'); ?></span>
            </nav>

            <!-- Title + inline search form -->
            <div class="wl-search-header-row">
                <div class="wl-search-header-text">
                    <?php if (!empty($keyword)): ?>
                        <h1 class="wl-archive-page-title">
                            <?php
                            printf(
                                esc_html__('Results for "%s"', 'wanderland'),
                                '<span class="wl-search-keyword">' . esc_html($keyword) . '</span>'
                            );
                            ?>
                        </h1>
                        <p class="wl-archive-page-desc">
                            <?php
                            printf(
                                esc_html(_n('Found %s result', 'Found %s results', $total_posts, 'wanderland')),
                                '<strong>' . number_format_i18n($total_posts) . '</strong>'
                            );
                            ?>
                        </p>
                    <?php else: ?>
                        <h1 class="wl-archive-page-title">
                            <?php esc_html_e('Search', 'wanderland'); ?>
                        </h1>
                        <p class="wl-archive-page-desc">
                            <?php esc_html_e('Enter a keyword to search our blog.', 'wanderland'); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Inline search form -->
                <form class="wl-search-form" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
                    <div class="wl-search-form-inner">
                        <input type="text" name="s" class="wl-search-input" value="<?php echo esc_attr($keyword); ?>"
                            placeholder="<?php esc_attr_e('Search posts…', 'wanderland'); ?>" autocomplete="off"
                            aria-label="<?php esc_attr_e('Search', 'wanderland'); ?>">
                        <button type="submit" class="wl-search-submit"
                            aria-label="<?php esc_attr_e('Submit search', 'wanderland'); ?>">
                            <i class="ion-ios-search"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <?php if (!empty($keyword) && $search_query->have_posts()): ?>

        <!-- ============================================================
         SORT BAR
    ============================================================ -->
        <div class="wl-archive-filter-wrap">
            <div class="wl-archive-container">
                <div class="wl-search-sort-bar">

                    <p class="wl-search-sort-label">
                        <?php esc_html_e('Sort by:', 'wanderland'); ?>
                    </p>

                    <!-- Sort pills -->
                    <div class="wl-search-sort-pills">
                        <?php
                        $sort_labels = array(
                            'relevance' => __('Relevance', 'wanderland'),
                            'date_desc' => __('Newest First', 'wanderland'),
                            'date_asc' => __('Oldest First', 'wanderland'),
                            'title_asc' => __('A → Z', 'wanderland'),
                        );
                        foreach ($sort_labels as $val => $label):
                            ?>
                            <a class="wl-sort-pill <?php echo $current_sort === $val ? 'is-active' : ''; ?>"
                                href="<?php echo esc_url(add_query_arg(array('sort' => $val), $base_url)); ?>">
                                <?php echo esc_html($label); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Result count -->
                    <span class="wl-filter-total">
                        <?php printf(
                            esc_html(_n('%s result', '%s results', $total_posts, 'wanderland')),
                            '<strong>' . number_format_i18n($total_posts) . '</strong>'
                        ); ?>
                    </span>

                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- ============================================================
         RESULTS GRID + PAGINATION
    ============================================================ -->
    <div class="wl-archive-container wl-archive-body">

        <div id="wl-search-ajax-wrap" data-current-page="<?php echo esc_attr($paged); ?>"
            data-max-pages="<?php echo esc_attr($max_pages); ?>" data-keyword="<?php echo esc_attr($keyword); ?>"
            data-sort="<?php echo esc_attr($current_sort); ?>">

            <?php if (!empty($keyword) && $search_query->have_posts()): ?>

                <div class="wl-archive-grid" id="wl-search-grid">
                    <?php while ($search_query->have_posts()):
                        $search_query->the_post(); ?>
                        <?php
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'wl-card');
                        if (!$img_url)
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $post_cats = get_the_category();
                        $post_cat = !empty($post_cats) ? $post_cats[0] : null;
                        $excerpt = get_the_excerpt() ?: wp_trim_words(get_the_content(), 20, '...');
                        $s_author_url = get_author_posts_url(get_the_author_meta('ID'));
                        ?>
                        <article class="wl-archive-card" itemscope itemtype="https://schema.org/BlogPosting">

                            <!-- Image -->
                            <a class="wl-archive-img-link" href="<?php the_permalink(); ?>">
                                <?php if ($img_url): ?>
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"
                                        decoding="async" class="wl-archive-img" itemprop="image">
                                <?php else: ?>
                                    <div class="wl-archive-img-placeholder"></div>
                                <?php endif; ?>
                                <div class="wl-archive-img-overlay" aria-hidden="true"></div>
                                <?php if ($post_cat): ?>
                                    <span class="wl-archive-cat-badge">
                                        <svg viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                            <path d="M1 1h5.5l5.5 5.5L6.5 13 1 7.5V1z" stroke="currentColor" stroke-width="1.2"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo esc_html($post_cat->name); ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- Content -->
                            <div class="wl-archive-content">
                                <div class="wl-archive-meta">
                                    <a class="wl-archive-author" href="<?php echo esc_url($s_author_url); ?>">
                                        <svg viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                            <path d="M7 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                                stroke-width="1.2" />
                                            <path d="M1.5 13c0-2 2.5-3.5 5.5-3.5S12.5 11 12.5 13" stroke="currentColor"
                                                stroke-width="1.2" stroke-linecap="round" />
                                        </svg>
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                    <span class="wl-archive-meta-dot" aria-hidden="true"></span>
                                    <time class="wl-archive-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                        <?php echo get_the_date('M j, Y'); ?>
                                    </time>
                                </div>

                                <h2 class="wl-archive-title" itemprop="headline">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <p class="wl-archive-excerpt" itemprop="description">
                                    <?php echo esc_html(wp_trim_words($excerpt, 18, '...')); ?>
                                </p>

                                <a class="wl-archive-readmore" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e('Read More', 'wanderland'); ?>
                                    <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                        <path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.4"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>

                        </article>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div>

                <!-- Pagination -->
                <?php if ($max_pages > 1): ?>
                    <nav class="wl-archive-pagination"
                        aria-label="<?php esc_attr_e('Search results navigation', 'wanderland'); ?>">
                        <div class="wl-pagination-inner">

                            <!-- Prev -->
                            <?php if ($paged > 1): ?>
                                <a class="wl-page-btn wl-page-prev" data-page="<?php echo $paged - 1; ?>"
                                    href="<?php echo esc_url(add_query_arg(array('sort' => $current_sort !== 'relevance' ? $current_sort : null, 'paged' => $paged > 2 ? $paged - 1 : null), $base_url)); ?>"
                                    aria-label="<?php esc_attr_e('Previous page', 'wanderland'); ?>">
                                    <i class="ion-ios-arrow-thin-left"></i>
                                </a>
                            <?php else: ?>
                                <span class="wl-page-btn wl-page-prev is-disabled" aria-disabled="true">
                                    <i class="ion-ios-arrow-thin-left"></i>
                                </span>
                            <?php endif; ?>

                            <!-- Numbers -->
                            <div class="wl-page-numbers">
                                <?php
                                $range = 2;
                                $start = max(1, $paged - $range);
                                $end = min($max_pages, $paged + $range);

                                if ($start > 1):
                                    ?>
                                    <a class="wl-page-num" data-page="1"
                                        href="<?php echo esc_url(add_query_arg(array('sort' => $current_sort !== 'relevance' ? $current_sort : null), $base_url)); ?>">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="wl-page-dots">&hellip;</span>
                                    <?php endif; ?>
                                <?php endif;

                                for ($i = $start; $i <= $end; $i++):
                                    $pg_url = add_query_arg(array_filter(array(
                                        'sort' => $current_sort !== 'relevance' ? $current_sort : null,
                                        'paged' => $i > 1 ? $i : null,
                                    )), $base_url);
                                    ?>
                                    <?php if ($i === $paged): ?>
                                        <span class="wl-page-num is-current" aria-current="page"><?php echo $i; ?></span>
                                    <?php else: ?>
                                        <a class="wl-page-num" data-page="<?php echo $i; ?>"
                                            href="<?php echo esc_url($pg_url); ?>"><?php echo $i; ?></a>
                                    <?php endif; ?>
                                <?php endfor;

                                if ($end < $max_pages):
                                    if ($end < $max_pages - 1): ?>
                                        <span class="wl-page-dots">&hellip;</span>
                                    <?php endif; ?>
                                    <a class="wl-page-num" data-page="<?php echo $max_pages; ?>"
                                        href="<?php echo esc_url(add_query_arg(array_filter(array('sort' => $current_sort !== 'relevance' ? $current_sort : null, 'paged' => $max_pages)), $base_url)); ?>">
                                        <?php echo $max_pages; ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <!-- Next -->
                            <?php if ($paged < $max_pages): ?>
                                <a class="wl-page-btn wl-page-next" data-page="<?php echo $paged + 1; ?>"
                                    href="<?php echo esc_url(add_query_arg(array_filter(array('sort' => $current_sort !== 'relevance' ? $current_sort : null, 'paged' => $paged + 1)), $base_url)); ?>"
                                    aria-label="<?php esc_attr_e('Next page', 'wanderland'); ?>">
                                    <i class="ion-ios-arrow-thin-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="wl-page-btn wl-page-next is-disabled" aria-disabled="true">
                                    <i class="ion-ios-arrow-thin-right"></i>
                                </span>
                            <?php endif; ?>

                        </div>

                        <p class="wl-pagination-info">
                            <?php printf(
                                esc_html__('Page %1$s of %2$s', 'wanderland'),
                                '<strong>' . $paged . '</strong>',
                                '<strong>' . $max_pages . '</strong>'
                            ); ?>
                        </p>
                    </nav>
                <?php endif; ?>

            <?php elseif (!empty($keyword)): ?>

                <!-- No results -->
                <div class="wl-archive-empty">
                    <div class="wl-archive-empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none">
                            <circle cx="28" cy="28" r="18" stroke="currentColor" stroke-width="2" />
                            <path d="M41 41l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            <path d="M20 28h16M28 20v16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                opacity="0.4" />
                        </svg>
                    </div>
                    <h3 class="wl-archive-empty-title">
                        <?php printf(
                            esc_html__('No results for "%s"', 'wanderland'),
                            esc_html($keyword)
                        ); ?>
                    </h3>
                    <p class="wl-archive-empty-text">
                        <?php esc_html_e('Try different keywords or browse all posts.', 'wanderland'); ?>
                    </p>
                    <a class="wl-archive-empty-btn"
                        href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/blog/')); ?>">
                        <?php esc_html_e('Browse All Posts', 'wanderland'); ?>
                    </a>
                </div>

            <?php endif; ?>

        </div><!-- #wl-search-ajax-wrap -->
    </div>

</main>

<?php get_footer(); ?>