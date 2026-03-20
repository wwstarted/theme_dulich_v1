<?php
/**
 * WANDERLAND — home.php
 * Main blog posts page (all posts, filter All by default)
 * Triggered when: Settings → Reading → Posts page is set
 */

get_header();

global $wpdb;

// ── Categories for filter ────────────────────────────────────
$all_cats = get_categories(array(
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
));

// ── Filter state from URL ────────────────────────────────────
$current_cat = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$current_sort = isset($_GET['sort']) ? sanitize_key($_GET['sort']) : 'date_desc';
$current_year = isset($_GET['year']) ? intval($_GET['year']) : 0;

$sort_map = array(
    'date_desc' => array('orderby' => 'date', 'order' => 'DESC'),
    'date_asc' => array('orderby' => 'date', 'order' => 'ASC'),
    'title_asc' => array('orderby' => 'title', 'order' => 'ASC'),
    'popular' => array('orderby' => 'comment_count', 'order' => 'DESC'),
);
$sort = isset($sort_map[$current_sort]) ? $sort_map[$current_sort] : $sort_map['date_desc'];

// ── Available years ──────────────────────────────────────────
$years = $wpdb->get_col(
    "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts}
     WHERE post_status = 'publish' AND post_type = 'post'
     ORDER BY post_date DESC"
);

// ── Paged ────────────────────────────────────────────────────
$paged = max(1, get_query_var('paged') ?: (get_query_var('page') ?: 1));

// ── Custom query ─────────────────────────────────────────────
$query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
    'orderby' => $sort['orderby'],
    'order' => $sort['order'],
);

if ($current_cat)
    $query_args['cat'] = $current_cat;
if ($current_year)
    $query_args['year'] = $current_year;

$archive_query = new WP_Query($query_args);
$total_posts = $archive_query->found_posts;
$max_pages = $archive_query->max_num_pages;

// ── Page title — home.php luôn là "Blog" ────────────────────
if ($current_cat && ($filter_cat_obj = get_category($current_cat))) {
    $page_title = $filter_cat_obj->name;
    $page_desc = $filter_cat_obj->description;
} else {
    $page_title = __('Blog', 'wanderland');
    $page_desc = '';
}

// Base URL for links (preserve non-paging params)
$base_url = strtok($_SERVER['REQUEST_URI'], '?');
?>

<main id="wl-archive-main" class="wl-archive-main">

    <!-- ============================================================
         PAGE HEADER — breadcrumb + title + desc
    ============================================================ -->
    <div class="wl-archive-page-header">
        <div class="wl-archive-container">

            <!-- Breadcrumb -->
            <nav class="wl-archive-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'wanderland'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php esc_html_e('Home', 'wanderland'); ?>
                </a>
                <span class="wl-bc-sep" aria-hidden="true">/</span>
                <?php if ($current_cat && isset($filter_cat_obj)): ?>
                <a href="<?php echo esc_url($base_url); ?>">
                    <?php esc_html_e('Blog', 'wanderland'); ?>
                </a>
                <span class="wl-bc-sep" aria-hidden="true">/</span>
                <span>
                    <?php echo esc_html($filter_cat_obj->name); ?>
                </span>
                <?php else: ?>
                <span>
                    <?php esc_html_e('Blog', 'wanderland'); ?>
                </span>
                <?php endif; ?>
            </nav>
            <!-- Title -->
            <h1 class="wl-archive-page-title">
                <?php echo esc_html($page_title); ?>
            </h1>
            <?php if ($page_desc): ?>
            <p class="wl-archive-page-desc">
                <?php echo esc_html($page_desc); ?>
            </p>
            <?php endif; ?>

        </div>
    </div>

    <!-- ============================================================
         FILTER BAR
    ============================================================ -->
    <div class="wl-archive-filter-wrap">
        <div class="wl-archive-container">
            <div class="wl-archive-filter-bar">

                <!-- Category pills -->
                <div class="wl-filter-cats">
                    <a class="wl-filter-cat <?php echo !$current_cat ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(
                               array_filter(array('sort' => $current_sort !== 'date_desc' ? $current_sort : null, 'year' => $current_year ?: null)),
                               $base_url
                           )); ?>">
                        <?php esc_html_e('All', 'wanderland'); ?>
                    </a>
                    <?php foreach ($all_cats as $cat_item): ?>
                    <a class="wl-filter-cat <?php echo $current_cat === $cat_item->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(
                                     array_filter(array(
                                         'cat' => $cat_item->term_id,
                                         'sort' => $current_sort !== 'date_desc' ? $current_sort : null,
                                         'year' => $current_year ?: null,
                                     )),
                                     $base_url
                                 )); ?>">
                        <?php echo esc_html($cat_item->name); ?>
                        <span class="wl-filter-count">
                            <?php echo intval($cat_item->count); ?>
                        </span>
                    </a>
                    <?php endforeach; ?>
                </div>

                <!-- Controls: sort + year + count -->
                <div class="wl-filter-controls">

                    <?php if (!empty($years)): ?>
                    <div class="wl-filter-select-wrap">
                        <select class="wl-filter-select"
                            aria-label="<?php esc_attr_e('Filter by year', 'wanderland'); ?>"
                            onchange="wlFilterChange('year', this.value)">
                            <option value="">
                                <?php esc_html_e('All Years', 'wanderland'); ?>
                            </option>
                            <?php foreach ($years as $y): ?>
                            <option value="<?php echo esc_attr($y); ?>" <?php selected($current_year, intval($y)); ?>>
                                <?php echo esc_html($y); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="ion-ios-arrow-down wl-select-arrow" aria-hidden="true"></i>
                    </div>
                    <?php endif; ?>

                    <div class="wl-filter-select-wrap">
                        <select class="wl-filter-select" aria-label="<?php esc_attr_e('Sort posts', 'wanderland'); ?>"
                            onchange="wlFilterChange('sort', this.value)">
                            <option value="date_desc" <?php selected($current_sort, 'date_desc'); ?>>
                                <?php esc_html_e('Newest', 'wanderland'); ?>
                            </option>
                            <option value="date_asc" <?php selected($current_sort, 'date_asc'); ?>>
                                <?php esc_html_e('Oldest', 'wanderland'); ?>
                            </option>
                            <option value="title_asc" <?php selected($current_sort, 'title_asc'); ?>>
                                <?php esc_html_e('A → Z', 'wanderland'); ?>
                            </option>
                            <option value="popular" <?php selected($current_sort, 'popular'); ?>>
                                <?php esc_html_e('Popular', 'wanderland'); ?>
                            </option>
                        </select>
                        <i class="ion-ios-arrow-down wl-select-arrow" aria-hidden="true"></i>
                    </div>

                    <span class="wl-filter-total">
                        <?php printf(
                            esc_html(_n('%s post', '%s posts', $total_posts, 'wanderland')),
                            '<strong>' . number_format_i18n($total_posts) . '</strong>'
                        ); ?>
                    </span>

                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         POST GRID + PAGINATION
    ============================================================ -->
    <div class="wl-archive-container wl-archive-body">

        <!-- AJAX wrapper — JS sẽ swap nội dung này khi đổi trang -->
        <div id="wl-ajax-wrap" data-current-page="<?php echo esc_attr($paged); ?>"
            data-max-pages="<?php echo esc_attr($max_pages); ?>" data-base-url="<?php echo esc_url($base_url); ?>"
            data-cat="<?php echo esc_attr($current_cat); ?>" data-sort="<?php echo esc_attr($current_sort); ?>"
            data-year="<?php echo esc_attr($current_year); ?>">

            <?php if ($archive_query->have_posts()): ?>

            <div class="wl-archive-grid" id="wl-archive-grid">
                <?php while ($archive_query->have_posts()):
                        $archive_query->the_post(); ?>
                <?php
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'wl-card');
                        if (!$img_url)
                            $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                        $post_cats = get_the_category();
                        $post_cat = !empty($post_cats) ? $post_cats[0] : null;
                        $excerpt = get_the_excerpt() ?: wp_trim_words(get_the_content(), 22, '...');
                        $a_author_url = get_author_posts_url(get_the_author_meta('ID'));
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
                            <a class="wl-archive-author" href="<?php echo esc_url($a_author_url); ?>">
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
                            <?php if (get_comments_number() > 0): ?>
                            <span class="wl-archive-meta-dot" aria-hidden="true"></span>
                            <span class="wl-archive-comments">
                                <svg viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                    <path d="M2 2h10v8H8l-2 2-2-2H2V2z" stroke="currentColor" stroke-width="1.2"
                                        stroke-linejoin="round" />
                                </svg>
                                <?php echo get_comments_number(); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="wl-archive-title" itemprop="headline">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
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

            <!-- ── Pagination ── -->
            <?php if ($max_pages > 1): ?>
            <nav class="wl-archive-pagination" aria-label="<?php esc_attr_e('Page navigation', 'wanderland'); ?>">
                <div class="wl-pagination-inner">

                    <!-- Prev -->
                    <?php if ($paged > 1): ?>
                    <a class="wl-page-btn wl-page-prev" href="<?php echo esc_url(add_query_arg(
                                    array_filter(array(
                                        'cat' => $current_cat ?: null,
                                        'sort' => $current_sort !== 'date_desc' ? $current_sort : null,
                                        'year' => $current_year ?: null,
                                        'paged' => $paged > 2 ? $paged - 1 : null,
                                    )),
                                    $base_url
                                )); ?>" aria-label="<?php esc_attr_e('Previous page', 'wanderland'); ?>">
                        <i class="ion-ios-arrow-thin-left"></i>
                    </a>
                    <?php else: ?>
                    <span class="wl-page-btn wl-page-prev is-disabled" aria-disabled="true">
                        <i class="ion-ios-arrow-thin-left"></i>
                    </span>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <div class="wl-page-numbers">
                        <?php
                                // Show: first, ellipsis, window around current, ellipsis, last
                                $range = 2; // pages shown each side of current
                                $start = max(1, $paged - $range);
                                $end = min($max_pages, $paged + $range);

                                if ($start > 1): ?>
                        <a class="wl-page-num" href="<?php echo esc_url(add_query_arg(
                                        array_filter(array('cat' => $current_cat ?: null, 'sort' => $current_sort !== 'date_desc' ? $current_sort : null, 'year' => $current_year ?: null)),
                                        $base_url
                                    )); ?>">1</a>
                        <?php if ($start > 2): ?>
                        <span class="wl-page-dots" aria-hidden="true">&hellip;</span>
                        <?php endif; ?>
                        <?php endif;

                                for ($i = $start; $i <= $end; $i++): ?>
                        <?php if ($i === $paged): ?>
                        <span class="wl-page-num is-current" aria-current="page">
                            <?php echo $i; ?>
                        </span>
                        <?php else: ?>
                        <a class="wl-page-num" href="<?php echo esc_url(add_query_arg(
                                            array_filter(array(
                                                'cat' => $current_cat ?: null,
                                                'sort' => $current_sort !== 'date_desc' ? $current_sort : null,
                                                'year' => $current_year ?: null,
                                                'paged' => $i > 1 ? $i : null,
                                            )),
                                            $base_url
                                        )); ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endif; ?>
                        <?php endfor;

                                if ($end < $max_pages): ?>
                        <?php if ($end < $max_pages - 1): ?>
                        <span class="wl-page-dots" aria-hidden="true">&hellip;</span>
                        <?php endif; ?>
                        <a class="wl-page-num" href="<?php echo esc_url(add_query_arg(
                                        array_filter(array(
                                            'cat' => $current_cat ?: null,
                                            'sort' => $current_sort !== 'date_desc' ? $current_sort : null,
                                            'year' => $current_year ?: null,
                                            'paged' => $max_pages,
                                        )),
                                        $base_url
                                    )); ?>">
                            <?php echo $max_pages; ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Next -->
                    <?php if ($paged < $max_pages): ?>
                    <a class="wl-page-btn wl-page-next" href="<?php echo esc_url(add_query_arg(
                                    array_filter(array(
                                        'cat' => $current_cat ?: null,
                                        'sort' => $current_sort !== 'date_desc' ? $current_sort : null,
                                        'year' => $current_year ?: null,
                                        'paged' => $paged + 1,
                                    )),
                                    $base_url
                                )); ?>" aria-label="<?php esc_attr_e('Next page', 'wanderland'); ?>">
                        <i class="ion-ios-arrow-thin-right"></i>
                    </a>
                    <?php else: ?>
                    <span class="wl-page-btn wl-page-next is-disabled" aria-disabled="true">
                        <i class="ion-ios-arrow-thin-right"></i>
                    </span>
                    <?php endif; ?>

                </div>

                <!-- Page info -->
                <p class="wl-pagination-info">
                    <?php printf(
                                esc_html__('Page %1$s of %2$s', 'wanderland'),
                                '<strong>' . $paged . '</strong>',
                                '<strong>' . $max_pages . '</strong>'
                            ); ?>
                </p>
            </nav>
            <?php endif; ?>

            <?php else: ?>

            <!-- Empty state -->
            <div class="wl-archive-empty">
                <div class="wl-archive-empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 64 64" fill="none">
                        <rect x="8" y="14" width="48" height="36" rx="3" stroke="currentColor" stroke-width="2" />
                        <path d="M8 22h48M20 14V8M44 14V8" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                        <path d="M24 34h16M24 40h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>
                <h3 class="wl-archive-empty-title">
                    <?php esc_html_e('No posts found', 'wanderland'); ?>
                </h3>
                <p class="wl-archive-empty-text">
                    <?php esc_html_e('Try adjusting your filters or browse all posts.', 'wanderland'); ?>
                </p>
                <a class="wl-archive-empty-btn" href="<?php echo esc_url($base_url); ?>">
                    <?php esc_html_e('View All Posts', 'wanderland'); ?>
                </a>
            </div>

            <?php endif; ?>

        </div><!-- #wl-ajax-wrap -->

    </div>

</main>

<script>
function wlFilterChange(key, value) {
    var url = new URL(window.location.href);
    url.searchParams.delete('paged');
    value ? url.searchParams.set(key, value) : url.searchParams.delete(key);
    window.location.href = url.toString();
}
</script>

<?php get_footer(); ?>