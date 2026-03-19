<?php
// ── SEO cho trang archive partners ───────────────────────────────────────
add_action('init', function () {

    $seo_page_slug = 'doi-tac';

    $get_seo_page_id = function () use ($seo_page_slug) {
        static $id = null;
        if ($id !== null)
            return $id;
        $page = get_page_by_path($seo_page_slug);
        $id = $page ? (int) $page->ID : 0;
        return $id;
    };

    add_filter('rank_math/frontend/canonical', function ($canonical) {
        if (is_front_page())
            return 'https://vieproxy.com/';
        if ((int) get_query_var('partners_archive') !== 1)
            return $canonical;
        $paged = max(1, (int) get_query_var('paged'));
        $cat = get_query_var('vp_partner_cat');
        if (!empty($cat)) {
            return $paged > 1
                ? home_url(user_trailingslashit($cat . '/page/' . $paged, 'paged'))
                : home_url('/' . $cat . '/');
        }
        return $paged > 1
            ? home_url(user_trailingslashit('doi-tac/page/' . $paged, 'paged'))
            : home_url('/doi-tac/');
    }, 20);

    add_filter('rank_math/frontend/title', function ($title) use ($get_seo_page_id) {
        if ((int) get_query_var('partners_archive') !== 1)
            return $title;
        $id = $get_seo_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, 'rank_math_title', true);
        return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
    }, 5);

    add_filter('rank_math/frontend/description', function ($desc) use ($get_seo_page_id) {
        if ((int) get_query_var('partners_archive') !== 1)
            return $desc;
        $id = $get_seo_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 5);

    add_filter('wpseo_title', function ($title) use ($get_seo_page_id) {
        if ((int) get_query_var('partners_archive') !== 1)
            return $title;
        $id = $get_seo_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, '_yoast_wpseo_title', true);
        return !empty($custom) ? $custom : $title;
    }, 5);

    add_filter('wpseo_metadesc', function ($desc) use ($get_seo_page_id) {
        if ((int) get_query_var('partners_archive') !== 1)
            return $desc;
        $id = $get_seo_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, '_yoast_wpseo_metadesc', true);
        return !empty($custom) ? $custom : $desc;
    }, 5);
});


// ── SEO cho trang single partner ─────────────────────────────────────────
add_action('wp', function () {
    if (empty(get_query_var('partner_slug')))
        return;

    $get_partner_post = function () {
        $slug = get_query_var('partner_slug');
        if (empty($slug))
            return null;
        $q = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'name' => sanitize_title($slug),
            'category_name' => 'doi-tac',
            'posts_per_page' => 1,
            'vp_allow_partner' => true,
            'no_found_rows' => true,
        ]);
        return $q->have_posts() ? $q->posts[0] : null;
    };

    $p = $get_partner_post();
    if (!$p)
        return;
    $cached_post = $p;

    add_filter('rank_math/frontend/canonical', function ($canonical) use ($cached_post) {
        return home_url('/doi-tac/' . $cached_post->post_name . '/');
    }, 20);

    add_filter('rank_math/frontend/title', function ($title) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_title', true);
        if (!empty($custom))
            return apply_filters('rank_math/paper/title', $custom);
        return get_the_title($cached_post->ID) . ' – ' . get_bloginfo('name');
    }, 6);

    add_filter('rank_math/frontend/description', function ($desc) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_description', true);
        if (!empty($custom))
            return $custom;
        $yoast = get_post_meta($cached_post->ID, '_yoast_wpseo_metadesc', true);
        if (!empty($yoast))
            return $yoast;
        $excerpt = get_the_excerpt($cached_post->ID);
        return !empty($excerpt) ? wp_strip_all_tags($excerpt) : $desc;
    }, 6);

    add_filter('rank_math/opengraph/facebook/title', function ($title) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_title', true);
        return !empty($custom) ? $custom : get_the_title($cached_post->ID) . ' – ' . get_bloginfo('name');
    }, 6);

    add_filter('rank_math/opengraph/facebook/description', function ($desc) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 6);

    add_filter('rank_math/opengraph/twitter/title', function ($title) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_title', true);
        return !empty($custom) ? $custom : get_the_title($cached_post->ID) . ' – ' . get_bloginfo('name');
    }, 6);

    add_filter('rank_math/opengraph/twitter/description', function ($desc) use ($cached_post) {
        $custom = get_post_meta($cached_post->ID, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 6);
});

// ── SEO cho trang archive proxy ───────────────────────────────────────────
add_action('init', function () {

    $proxy_page_slug = 'mua-proxy';

    $get_proxy_page_id = function () use ($proxy_page_slug) {
        static $id = null;
        if ($id !== null)
            return $id;
        $page = get_page_by_path($proxy_page_slug);
        $id = $page ? (int) $page->ID : 0;
        return $id;
    };

    $is_proxy_route = function () {
        return is_page_template('archive-proxies.php') || is_page('mua-proxy');
    };

    // ── Canonical ─────────────────────────────────────────────
    add_filter('rank_math/frontend/canonical', function ($canonical) use ($is_proxy_route, $proxy_page_slug) {
        if (!$is_proxy_route())
            return $canonical;
        $page = get_page_by_path($proxy_page_slug);
        $base = $page ? get_permalink($page->ID) : home_url('/mua-proxy/');
        $cat = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
        return !empty($cat) ? add_query_arg('category', $cat, $base) : $base;
    }, 20);

    // ── Title ─────────────────────────────────────────────────
    add_filter('rank_math/frontend/title', function ($title) use ($is_proxy_route, $get_proxy_page_id) {
        if (!$is_proxy_route())
            return $title;
        $id = $get_proxy_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, 'rank_math_title', true);
        return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
    }, 20);

    // ── Description ───────────────────────────────────────────
    add_filter('rank_math/frontend/description', function ($desc) use ($is_proxy_route, $get_proxy_page_id) {
        if (!$is_proxy_route())
            return $desc;
        $id = $get_proxy_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 20);

    // ── OG Title ──────────────────────────────────────────────
    add_filter('rank_math/opengraph/facebook/title', function ($title) use ($is_proxy_route, $get_proxy_page_id) {
        if (!$is_proxy_route())
            return $title;
        $id = $get_proxy_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, 'rank_math_title', true);
        return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
    }, 20);

    // ── OG Description ────────────────────────────────────────
    add_filter('rank_math/opengraph/facebook/description', function ($desc) use ($is_proxy_route, $get_proxy_page_id) {
        if (!$is_proxy_route())
            return $desc;
        $id = $get_proxy_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 20);
});


// // ── SEO cho trang archive proxy ───────────────────────────────────────────
// add_action('init', function () {

//     $proxy_page_slug = 'mua-proxy';

//     $get_proxy_page_id = function () use ($proxy_page_slug) {
//         static $id = null;
//         if ($id !== null) return $id;
//         $page = get_page_by_path($proxy_page_slug);
//         $id   = $page ? (int) $page->ID : 0;
//         return $id;
//     };

//     // Detect: đang ở trang mua-proxy (có hoặc không có ?category=)
//     $is_proxy_route = function () {
//         return is_page_template('archive-proxies.php') || is_page('mua-proxy');
//     };

//     // ── Canonical ─────────────────────────────────────────────
//     add_filter('rank_math/frontend/canonical', function ($canonical) use ($is_proxy_route, $proxy_page_slug) {
//         if (!$is_proxy_route()) return $canonical;

//         $page = get_page_by_path($proxy_page_slug);
//         $base = $page ? get_permalink($page->ID) : home_url('/mua-proxy/');

//         $cat = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
//         return !empty($cat) ? add_query_arg('category', $cat, $base) : $base;
//     }, 20);

//     // ── Title ─────────────────────────────────────────────────
//     add_filter('rank_math/frontend/title', function ($title) use ($is_proxy_route, $get_proxy_page_id) {
//         if (!$is_proxy_route()) return $title;

//         $cat_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
//         if (!empty($cat_slug)) {
//             $term = get_term_by('slug', $cat_slug, 'product_cat');
//             if ($term && !is_wp_error($term)) {
//                 $cat_title = get_term_meta($term->term_id, 'rank_math_title', true);
//                 if (!empty($cat_title)) return $cat_title;
//                 return $term->name . ' – ' . get_bloginfo('name');
//             }
//         }

//         $id = $get_proxy_page_id();
//         if (!$id) return $title;
//         $custom = get_post_meta($id, 'rank_math_title', true);
//         return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
//     }, 20);

//     // ── Description ───────────────────────────────────────────
//     add_filter('rank_math/frontend/description', function ($desc) use ($is_proxy_route, $get_proxy_page_id) {
//         if (!$is_proxy_route()) return $desc;

//         $cat_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
//         if (!empty($cat_slug)) {
//             $term = get_term_by('slug', $cat_slug, 'product_cat');
//             if ($term && !is_wp_error($term)) {
//                 $cat_desc = get_term_meta($term->term_id, 'rank_math_description', true);
//                 if (!empty($cat_desc)) return $cat_desc;
//                 if (!empty($term->description)) return wp_strip_all_tags($term->description);
//             }
//         }

//         $id = $get_proxy_page_id();
//         if (!$id) return $desc;
//         $custom = get_post_meta($id, 'rank_math_description', true);
//         return !empty($custom) ? $custom : $desc;
//     }, 20);

//     // ── OG Title ──────────────────────────────────────────────
//     add_filter('rank_math/opengraph/facebook/title', function ($title) use ($is_proxy_route, $get_proxy_page_id) {
//         if (!$is_proxy_route()) return $title;
//         $cat_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
//         if (!empty($cat_slug)) {
//             $term = get_term_by('slug', $cat_slug, 'product_cat');
//             if ($term && !is_wp_error($term)) {
//                 $cat_title = get_term_meta($term->term_id, 'rank_math_title', true);
//                 return !empty($cat_title) ? $cat_title : $term->name . ' – ' . get_bloginfo('name');
//             }
//         }
//         $id = $get_proxy_page_id();
//         if (!$id) return $title;
//         $custom = get_post_meta($id, 'rank_math_title', true);
//         return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
//     }, 20);

//     // ── OG Description ────────────────────────────────────────
//     add_filter('rank_math/opengraph/facebook/description', function ($desc) use ($is_proxy_route, $get_proxy_page_id) {
//         if (!$is_proxy_route()) return $desc;
//         $cat_slug = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : '';
//         if (!empty($cat_slug)) {
//             $term = get_term_by('slug', $cat_slug, 'product_cat');
//             if ($term && !is_wp_error($term)) {
//                 $cat_desc = get_term_meta($term->term_id, 'rank_math_description', true);
//                 if (!empty($cat_desc)) return $cat_desc;
//                 if (!empty($term->description)) return wp_strip_all_tags($term->description);
//             }
//         }
//         $id = $get_proxy_page_id();
//         if (!$id) return $desc;
//         $custom = get_post_meta($id, 'rank_math_description', true);
//         return !empty($custom) ? $custom : $desc;
//     }, 20);
// });
// ── SEO cho trang blog archive ────────────────────────────────────────────
add_action('init', function () {

    $get_blog_page_id = function () {
        static $id = null;
        if ($id !== null)
            return $id;
        $page = get_page_by_path('blog');
        $id = $page ? (int) $page->ID : 0;
        return $id;
    };

    $is_blog_route = function () {
        return (bool) get_query_var('vp_blog_cat') || is_page_template('page-blog.php');
    };

    // ── Canonical ─────────────────────────────────────────────
    add_filter('rank_math/frontend/canonical', function ($canonical) use ($is_blog_route) {
        if (!$is_blog_route())
            return $canonical;

        $paged = max(1, (int) get_query_var('paged'));
        $cat = get_query_var('vp_blog_cat');

        if (!empty($cat)) {
            return $paged > 1
                ? home_url(user_trailingslashit($cat . '/page/' . $paged, 'paged'))
                : home_url('/' . $cat . '/');
        }

        $blog_page = get_page_by_path('blog');
        $base = $blog_page ? get_permalink($blog_page->ID) : home_url('/blog/');
        return $paged > 1
            ? home_url(user_trailingslashit('blog/page/' . $paged, 'paged'))
            : $base;
    }, 20);

    // ── Title: Rank Math ───────────────────────────────────────
    add_filter('rank_math/frontend/title', function ($title) use ($is_blog_route, $get_blog_page_id) {
        if (!$is_blog_route())
            return $title;

        $cat_slug = get_query_var('vp_blog_cat');
        if (!empty($cat_slug)) {
            $term = get_category_by_slug($cat_slug);
            if ($term && !is_wp_error($term)) {
                $cat_title = get_term_meta($term->term_id, 'rank_math_title', true);
                if (!empty($cat_title))
                    return $cat_title;
                return $term->name . ' – ' . get_bloginfo('name');
            }
        }

        $id = $get_blog_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, 'rank_math_title', true);
        return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
    }, 20);

    // ── Description: Rank Math ─────────────────────────────────
    add_filter('rank_math/frontend/description', function ($desc) use ($is_blog_route, $get_blog_page_id) {
        if (!$is_blog_route())
            return $desc;

        $cat_slug = get_query_var('vp_blog_cat');
        if (!empty($cat_slug)) {
            $term = get_category_by_slug($cat_slug);
            if ($term && !is_wp_error($term)) {
                $cat_desc = get_term_meta($term->term_id, 'rank_math_description', true);
                if (!empty($cat_desc))
                    return $cat_desc;
                if (!empty($term->description))
                    return wp_strip_all_tags($term->description);
            }
        }

        $id = $get_blog_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 20);

    // ── OG Title ──────────────────────────────────────────────
    add_filter('rank_math/opengraph/facebook/title', function ($title) use ($is_blog_route, $get_blog_page_id) {
        if (!$is_blog_route())
            return $title;
        $cat_slug = get_query_var('vp_blog_cat');
        if (!empty($cat_slug)) {
            $term = get_category_by_slug($cat_slug);
            if ($term && !is_wp_error($term)) {
                $cat_title = get_term_meta($term->term_id, 'rank_math_title', true);
                return !empty($cat_title) ? $cat_title : $term->name . ' – ' . get_bloginfo('name');
            }
        }
        $id = $get_blog_page_id();
        if (!$id)
            return $title;
        $custom = get_post_meta($id, 'rank_math_title', true);
        return !empty($custom) ? apply_filters('rank_math/paper/title', $custom) : $title;
    }, 20);

    // ── OG Description ────────────────────────────────────────
    add_filter('rank_math/opengraph/facebook/description', function ($desc) use ($is_blog_route, $get_blog_page_id) {
        if (!$is_blog_route())
            return $desc;
        $cat_slug = get_query_var('vp_blog_cat');
        if (!empty($cat_slug)) {
            $term = get_category_by_slug($cat_slug);
            if ($term && !is_wp_error($term)) {
                $cat_desc = get_term_meta($term->term_id, 'rank_math_description', true);
                if (!empty($cat_desc))
                    return $cat_desc;
                if (!empty($term->description))
                    return wp_strip_all_tags($term->description);
            }
        }
        $id = $get_blog_page_id();
        if (!$id)
            return $desc;
        $custom = get_post_meta($id, 'rank_math_description', true);
        return !empty($custom) ? $custom : $desc;
    }, 20);
});


// ── Fix redirect_canonical cho custom routes ──────────────────────────────
add_filter('redirect_canonical', function ($redirect_url) {
    if (get_query_var('vp_blog_cat'))
        return false;
    if (get_query_var('vp_proxy_cat'))
        return false;
    return $redirect_url;
}, 10, 2);


// ── SEO cho trang single blog post ───────────────────────────────────────
add_action('wp', function () {
    if (!is_singular('post'))
        return;
    $post = get_queried_object();
    if (!$post instanceof WP_Post)
        return;
    if (has_category('doi-tac', $post))
        return;

    add_filter('rank_math/frontend/canonical', function ($canonical) use ($post) {
        return get_permalink($post->ID);
    }, 20);
});