<?php
/**
 * WANDERLAND — single.php
 * Single post template — blog hero + featured image + content/sidebar
 */

get_header();
?>

<?php while (have_posts()):
    the_post(); ?>

<?php
    $post_id = get_the_ID();
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'wl-wide');
    if (!$thumbnail_url)
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
    $categories = get_the_category();
    $cat = !empty($categories) ? $categories[0] : null;
    $tags = get_the_tags();
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author();
    $author_bio = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);

    // Avatar với fallback về ảnh local
    $default_avatar = get_template_directory_uri() . '/images/author-avtfallback.png';
    $author_avatar_raw = get_avatar_url($author_id, ['size' => 120, 'default' => '404']);
    if (!empty($author_avatar_raw) && strpos($author_avatar_raw, 'gravatar.com') !== false) {
        $check = wp_remote_head($author_avatar_raw, ['timeout' => 3]);
        $author_avatar = (!is_wp_error($check) && wp_remote_retrieve_response_code($check) !== 404)
            ? $author_avatar_raw
            : $default_avatar;
    } else {
        $author_avatar = !empty($author_avatar_raw) ? $author_avatar_raw : $default_avatar;
    }

    // Bio fallback
    $author_bio = $author_bio ?: __('A passionate traveler and storyteller, sharing adventures one destination at a time.', 'wanderland');
    $post_url = urlencode(get_permalink());
    $post_title_enc = urlencode(get_the_title());
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    // Blog hero uses the fixed blog-image.jpg
    $hero_bg = get_template_directory_uri() . '/images/blog-image.jpg';
    ?>

<main id="wl-single-main" class="wl-single-main">

    <!-- ============================================================
         BLOG HERO BANNER — full screen, header + topbar float above
    ============================================================ -->
    <section class="wl-blog-hero" style="background-image: url('<?php echo esc_url($hero_bg); ?>');">
        <div class="wl-blog-hero-overlay"></div>
        <div class="wl-blog-hero-inner">
            <h2 class="wl-blog-hero-title"><?php esc_html_e('Blog', 'wanderland'); ?></h2>
        </div>
        <!-- Brush stroke bottom -->
        <div class="wl-blog-hero-brush" aria-hidden="true">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/h1-rev-bottom.png" alt=""
                loading="eager" decoding="async">
        </div>
    </section>

    <!-- ============================================================
         POST FEATURED IMAGE — contained, below hero
    ============================================================ -->
    <?php if ($thumbnail_url): ?>
    <div class="wl-single-featured-wrap">
        <div class="wl-single-container">
            <div class="wl-single-featured-inner">
                <?php if ($cat): ?>
                <a class="wl-single-cat-badge" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                    <span class="wl-single-cat-icon" aria-hidden="true">
                        <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1h5.5l5.5 5.5L6.5 13 1 7.5V1z" stroke="currentColor" stroke-width="1.2"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <?php echo esc_html($cat->name); ?>
                </a>
                <?php endif; ?>
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>"
                    class="wl-single-featured-img" loading="eager" decoding="async">
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ============================================================
         POST HEADER — left-aligned
         Row 1: Breadcrumb
         Row 2: Author avatar + Name + Dot + Date
         Row 3: Title
    ============================================================ -->
    <div class="wl-single-header-wrap">
        <div class="wl-single-container">
            <div class="wl-single-header">

                <!-- Breadcrumb -->
                <nav class="wl-single-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'wanderland'); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'wanderland'); ?></a>
                    <span class="wl-bc-sep" aria-hidden="true">/</span>
                    <?php
                        $blog_page = get_option('page_for_posts');
                        $blog_url = $blog_page ? get_permalink($blog_page) : home_url('/blog/');
                        ?>
                    <a href="<?php echo esc_url($blog_url); ?>"><?php esc_html_e('Blog', 'wanderland'); ?></a>
                    <?php if ($cat): ?>
                    <span class="wl-bc-sep" aria-hidden="true">/</span>
                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                    <?php endif; ?>
                </nav>

                <!-- Author + Date row -->
                <div class="wl-single-meta-row">
                    <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>"
                        class="wl-single-author-avatar" loading="lazy">
                    <a class="wl-single-author-name" href="<?php echo esc_url($author_url); ?>">
                        <?php echo esc_html($author_name); ?>
                    </a>
                    <span class="wl-meta-dot" aria-hidden="true"></span>
                    <time class="wl-single-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                        <?php echo get_the_date('F j, Y'); ?>
                    </time>
                </div>

                <!-- Title -->
                <h1 class="wl-single-title"><?php the_title(); ?></h1>

            </div>
        </div>
    </div>

    <!-- ============================================================
         CONTENT + SIDEBAR
    ============================================================ -->
    <div class="wl-single-container">
        <div class="wl-single-layout">

            <article class="wl-single-content" itemscope itemtype="https://schema.org/BlogPosting">

                <!-- Entry content — TOC sẽ được JS inject sau paragraph đầu tiên -->
                <div class="wl-single-entry" itemprop="articleBody">
                    <!-- TOC placeholder — JS sẽ move vào đây sau p đầu tiên -->
                    <div class="wl-toc" id="wl-toc" aria-label="<?php esc_attr_e('Table of Contents', 'wanderland'); ?>"
                        style="display:none;">
                        <div class="wl-toc-header">
                            <span class="wl-toc-title"><?php esc_html_e('Table of Contents', 'wanderland'); ?></span>
                            <button class="wl-toc-toggle" aria-expanded="true" aria-controls="wl-toc-list"
                                aria-label="<?php esc_attr_e('Toggle table of contents', 'wanderland'); ?>">
                                <i class="ion-ios-arrow-up wl-toc-arrow"></i>
                            </button>
                        </div>
                        <nav class="wl-toc-body" id="wl-toc-list">
                            <ol class="wl-toc-list"></ol>
                        </nav>
                    </div>
                    <?php the_content(); ?>
                </div>

                <!-- Tags + Share -->
                <footer class="wl-single-footer">
                    <?php if ($tags): ?>
                    <div class="wl-single-tags">
                        <?php foreach ($tags as $tag): ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="wl-tag">
                            <?php echo esc_html($tag->name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="wl-single-share">
                        <span class="wl-share-label"><?php esc_html_e('Share', 'wanderland'); ?></span>
                        <div class="wl-share-links">
                            <a href="https://www.facebook.com/sharer.php?u=<?php echo $post_url; ?>" target="_blank"
                                rel="noopener noreferrer" class="wl-share-btn" aria-label="Facebook">
                                <i class="ion-social-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title_enc; ?>"
                                target="_blank" rel="noopener noreferrer" class="wl-share-btn" aria-label="Twitter">
                                <i class="ion-social-twitter"></i>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>"
                                target="_blank" rel="noopener noreferrer" class="wl-share-btn" aria-label="Pinterest">
                                <i class="ion-social-pinterest"></i>
                            </a>
                            <a href="https://www.tumblr.com/share/link?url=<?php echo $post_url; ?>" target="_blank"
                                rel="noopener noreferrer" class="wl-share-btn" aria-label="Tumblr">
                                <i class="ion-social-tumblr"></i>
                            </a>
                        </div>
                    </div>
                </footer>

                <!-- Prev / Next Navigation -->
                <div class="wl-single-nav">
                    <div class="wl-single-nav-inner">

                        <?php if ($prev_post): ?>
                        <?php $prev_thumb = get_the_post_thumbnail_url($prev_post->ID, array(76, 50)); ?>
                        <a class="wl-nav-item wl-nav-prev" href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>"
                            itemprop="url">
                            <?php if ($prev_thumb): ?>
                            <div class="wl-nav-thumb">
                                <img src="<?php echo esc_url($prev_thumb); ?>"
                                    alt="<?php echo esc_attr($prev_post->post_title); ?>" width="76" height="50"
                                    loading="lazy">
                            </div>
                            <?php endif; ?>
                            <span class="wl-nav-mark">
                                <i class="ion-ios-arrow-thin-left"></i>
                            </span>
                            <span class="wl-nav-label"><?php esc_html_e('previous post', 'wanderland'); ?></span>
                        </a>
                        <?php endif; ?>

                        <?php if ($next_post): ?>
                        <?php $next_thumb = get_the_post_thumbnail_url($next_post->ID, array(76, 50)); ?>
                        <a class="wl-nav-item wl-nav-next" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>"
                            itemprop="url">
                            <span class="wl-nav-label"><?php esc_html_e('next post', 'wanderland'); ?></span>
                            <span class="wl-nav-mark">
                                <i class="ion-ios-arrow-thin-right"></i>
                            </span>
                            <?php if ($next_thumb): ?>
                            <div class="wl-nav-thumb">
                                <img src="<?php echo esc_url($next_thumb); ?>"
                                    alt="<?php echo esc_attr($next_post->post_title); ?>" width="76" height="50"
                                    loading="lazy">
                            </div>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Author Box -->
                <div class="wl-author-box">
                    <div class="wl-author-box-inner">

                        <!-- Avatar -->
                        <div class="wl-author-box-img">
                            <a href="<?php echo esc_url($author_url); ?>"
                                title="<?php echo esc_attr(get_the_title()); ?>">
                                <img src="<?php echo esc_url($author_avatar); ?>"
                                    alt="<?php echo esc_attr($author_name); ?>" class="wl-author-box-avatar" width="120"
                                    height="120" loading="lazy">
                            </a>
                        </div>

                        <!-- Content -->
                        <div class="wl-author-box-content">

                            <!-- Social icons + Follow -->
                            <div class="wl-author-social">
                                <?php $author_facebook = get_the_author_meta('facebook', $author_id); ?>
                                <?php $author_twitter = get_the_author_meta('twitter', $author_id); ?>
                                <?php $author_instagram = get_the_author_meta('instagram', $author_id); ?>

                                <?php if ($author_facebook): ?>
                                <a href="<?php echo esc_url($author_facebook); ?>" target="_blank"
                                    rel="noopener noreferrer" itemprop="url" aria-label="Facebook">
                                    <i class="ion-social-facebook"></i>
                                </a>
                                <?php else: ?>
                                <a href="#" aria-label="Facebook">
                                    <i class="ion-social-facebook"></i>
                                </a>
                                <?php endif; ?>

                                <?php if ($author_twitter): ?>
                                <a href="<?php echo esc_url($author_twitter); ?>" target="_blank"
                                    rel="noopener noreferrer" itemprop="url" aria-label="Twitter">
                                    <i class="ion-social-twitter"></i>
                                </a>
                                <?php else: ?>
                                <a href="#" aria-label="Twitter">
                                    <i class="ion-social-twitter"></i>
                                </a>
                                <?php endif; ?>

                                <?php if ($author_instagram): ?>
                                <a href="<?php echo esc_url($author_instagram); ?>" target="_blank"
                                    rel="noopener noreferrer" itemprop="url" aria-label="Instagram">
                                    <i class="ion-social-instagram"></i>
                                </a>
                                <?php else: ?>
                                <a href="#" aria-label="Instagram">
                                    <i class="ion-social-instagram"></i>
                                </a>
                                <?php endif; ?>

                                <span class="wl-author-follow-label">
                                    <?php esc_html_e('Follow', 'wanderland'); ?>
                                </span>
                            </div>

                            <!-- Name -->
                            <h5 class="wl-author-box-name vcard author">
                                <a href="<?php echo esc_url($author_url); ?>" itemprop="url"
                                    title="<?php echo esc_attr(get_the_title()); ?>">
                                    <span class="fn"><?php echo esc_html($author_name); ?></span>
                                </a>
                            </h5>

                            <!-- Bio -->
                            <?php if ($author_bio): ?>
                            <p class="wl-author-box-bio" itemprop="description">
                                <?php echo esc_html($author_bio); ?>
                            </p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <?php if (comments_open() || get_comments_number()): ?>
                <div class="wl-comments-wrap">
                    <?php comments_template(); ?>
                </div>
                <?php endif; ?>

            </article>

            <!-- Sidebar -->
            <aside class="wl-single-sidebar" role="complementary">

                <?php if (is_active_sidebar('single-sidebar')): ?>
                <?php dynamic_sidebar('single-sidebar'); ?>
                <?php endif; ?>

                <!-- Recent Posts — luôn hiển thị -->
                <div class="wl-sidebar-widget wl-sidebar-recent">
                    <h5 class="wl-widget-title"><?php esc_html_e('Recent Posts', 'wanderland'); ?></h5>

                    <?php
                        $recent_query = new WP_Query(array(
                            'posts_per_page' => 5,
                            'post__not_in' => array($post_id),
                            'post_status' => 'publish',
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ));
                        if ($recent_query->have_posts()):
                            ?>
                    <ul class="wl-recent-list">
                        <?php while ($recent_query->have_posts()):
                                    $recent_query->the_post(); ?>
                        <?php
                                    $rec_img = get_the_post_thumbnail_url(get_the_ID(), 'wl-thumb');
                                    if (!$rec_img)
                                        $rec_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                    ?>
                        <li class="wl-recent-item">
                            <a class="wl-recent-link" href="<?php the_permalink(); ?>">
                                <!-- Thumbnail -->
                                <div class="wl-recent-thumb">
                                    <?php if ($rec_img): ?>
                                    <img src="<?php echo esc_url($rec_img); ?>" alt="<?php the_title_attribute(); ?>"
                                        loading="lazy" decoding="async">
                                    <?php else: ?>
                                    <div class="wl-recent-thumb-placeholder"></div>
                                    <?php endif; ?>
                                </div>
                                <!-- Text -->
                                <div class="wl-recent-text">
                                    <span class="wl-recent-title"><?php the_title(); ?></span>
                                    <time class="wl-recent-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                        <?php echo get_the_date('M j, Y'); ?>
                                    </time>
                                </div>
                            </a>
                        </li>
                        <?php endwhile;
                                wp_reset_postdata(); ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Categories -->
                <div class="wl-sidebar-widget">
                    <h5 class="wl-widget-title"><?php esc_html_e('Categories', 'wanderland'); ?></h5>
                    <ul class="wl-cat-list">
                        <?php wp_list_categories(array(
                                'show_count' => false,
                                'title_li' => '',
                                'orderby' => 'name',
                            )); ?>
                    </ul>
                </div>

                <!-- Tags -->
                <div class="wl-sidebar-widget">
                    <h5 class="wl-widget-title"><?php esc_html_e('Tags', 'wanderland'); ?></h5>
                    <div class="wl-tag-cloud">
                        <?php wp_tag_cloud(array(
                                'smallest' => 11,
                                'largest' => 11,
                                'unit' => 'px',
                                'format' => 'flat',
                            )); ?>
                    </div>
                </div>

            </aside>

        </div>
    </div>

    <!-- ============================================================
         RELATED POSTS — 3 bài cùng category
    ============================================================ -->
    <?php
        if ($cat) {
            $related_query = new WP_Query(array(
                'category__in' => array($cat->term_id),
                'post__not_in' => array($post_id),
                'posts_per_page' => 3,
                'orderby' => 'rand',
                'post_status' => 'publish',
            ));
        } else {
            $related_query = new WP_Query(array(
                'post__not_in' => array($post_id),
                'posts_per_page' => 3,
                'orderby' => 'date',
                'post_status' => 'publish',
            ));
        }

        if ($related_query->have_posts()):
            ?>
    <section class="wl-related-section">
        <div class="wl-single-container">

            <!-- Header -->
            <div class="wl-related-header">
                <h3 class="wl-related-title">
                    <?php esc_html_e('Related', 'wanderland'); ?>
                    <span class="wl-related-title-accent"><?php esc_html_e('Posts', 'wanderland'); ?></span>
                </h3>
                <div class="wl-related-line"></div>
            </div>

            <!-- Grid -->
            <div class="wl-related-grid">
                <?php while ($related_query->have_posts()):
                            $related_query->the_post(); ?>
                <?php
                            $rel_img = get_the_post_thumbnail_url(get_the_ID(), 'wl-card');
                            if (!$rel_img)
                                $rel_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            $rel_cats = get_the_category();
                            $rel_cat = !empty($rel_cats) ? $rel_cats[0] : null;
                            $rel_author = get_the_author();
                            $rel_exc = get_the_excerpt();
                            if (!$rel_exc)
                                $rel_exc = wp_trim_words(get_the_content(), 18, '...');
                            ?>
                <article class="wl-related-card">

                    <!-- Image -->
                    <a class="wl-related-img-link" href="<?php the_permalink(); ?>">
                        <?php if ($rel_img): ?>
                        <img src="<?php echo esc_url($rel_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"
                            decoding="async" class="wl-related-img">
                        <?php else: ?>
                        <div class="wl-related-img-placeholder"></div>
                        <?php endif; ?>

                        <!-- Category badge -->
                        <?php if ($rel_cat): ?>
                        <span class="wl-related-cat"><?php echo esc_html($rel_cat->name); ?></span>
                        <?php endif; ?>
                    </a>

                    <!-- Content -->
                    <div class="wl-related-content">

                        <!-- Meta: author + date -->
                        <div class="wl-related-meta">
                            <span class="wl-related-author">
                                <svg viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                    <path d="M7 7a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor"
                                        stroke-width="1.2" />
                                    <path d="M1.5 13c0-2 2.5-3.5 5.5-3.5S12.5 11 12.5 13" stroke="currentColor"
                                        stroke-width="1.2" stroke-linecap="round" />
                                </svg>
                                <?php echo esc_html($rel_author); ?>
                            </span>
                            <span class="wl-related-meta-dot" aria-hidden="true"></span>
                            <time class="wl-related-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                <?php echo get_the_date('M j, Y'); ?>
                            </time>
                        </div>

                        <!-- Title -->
                        <h4 class="wl-related-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>

                        <!-- Excerpt -->
                        <p class="wl-related-excerpt">
                            <?php echo esc_html(wp_trim_words($rel_exc, 18, '...')); ?>
                        </p>

                        <!-- Read more -->
                        <a class="wl-related-readmore" href="<?php the_permalink(); ?>">
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

        </div>
    </section>
    <?php endif; ?>

</main>

<?php endwhile; ?>
<?php get_footer(); ?>