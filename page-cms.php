<?php
/**
 * Template Name: CMS Page (About / Policy / Terms)
 * Template Post Type: page
 *
 * WANDERLAND — page-cms.php
 * Reusable CMS template:
 *   1. Hero banner (header floats above)
 *   2. Image slider (3-up, brush-stroke arrows)
 *   3. Page content from WP editor
 */

get_header();

$post_id = get_the_ID();

// ── Hero image — custom meta OR fallback to cms-page.jpg ────
$hero_img_id = get_post_meta($post_id, '_wl_cms_hero_image', true);
$hero_bg = $hero_img_id
    ? wp_get_attachment_image_url(intval($hero_img_id), 'full')
    : get_template_directory_uri() . '/images/cms-page.jpg';

// ── Slider images ────────────────────────────────────────────
$slider_ids_raw = get_post_meta($post_id, '_wl_cms_slider_images', true);
$slider_ids = $slider_ids_raw
    ? array_filter(array_map('intval', explode(',', $slider_ids_raw)))
    : array();

// ── Page tagline ─────────────────────────────────────────────
$tagline = get_post_meta($post_id, '_wl_cms_tagline', true);
?>

<main id="wl-cms-main" class="wl-cms-main">

    <!-- 1. HERO BANNER -->
    <section class="wl-cms-hero" style="background-image: url('<?php echo esc_url($hero_bg); ?>');">
        <div class="wl-cms-hero-overlay"></div>
        <div class="wl-cms-hero-inner">
            <?php if ($tagline): ?>
            <span class="wl-cms-hero-tagline"><?php echo esc_html($tagline); ?></span>
            <?php endif; ?>
            <h1 class="wl-cms-hero-title"><?php the_title(); ?></h1>
        </div>
        <div class="wl-cms-hero-brush" aria-hidden="true">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/h1-rev-bottom.png" alt=""
                loading="eager" decoding="async">
        </div>
    </section>

    <!-- 2. IMAGE SLIDER -->
    <?php if (!empty($slider_ids)): ?>
    <section class="wl-cms-slider-section">
        <div class="wl-cms-slider-wrap">
            <div class="wl-cms-slider" id="wl-cms-slider">
                <div class="wl-cms-slider-track" id="wl-cms-slider-track">
                    <?php foreach ($slider_ids as $img_id): ?>
                    <?php $img_url = wp_get_attachment_image_url($img_id, 'wl-card') ?: wp_get_attachment_image_url($img_id, 'full'); ?>
                    <?php if ($img_url): ?>
                    <div class="wl-cms-slide">
                        <div class="wl-cms-slide-inner">
                            <img src="<?php echo esc_url($img_url); ?>"
                                alt="<?php echo esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', true)); ?>"
                                loading="lazy" decoding="async">
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Prev -->
            <button class="wl-cms-arrow wl-cms-arrow--prev" id="wl-cms-prev"
                aria-label="<?php esc_attr_e('Previous', 'wanderland'); ?>">
                <span class="wl-arrow-inner">
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <line x1="15" y1="10" x2="5" y2="10" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                        <path d="M9 5.5L4.5 10L9 14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" fill="none" />
                    </svg>
                </span>
            </button>
            <!-- Next -->
            <button class="wl-cms-arrow wl-cms-arrow--next" id="wl-cms-next"
                aria-label="<?php esc_attr_e('Next', 'wanderland'); ?>">
                <span class="wl-arrow-inner">
                    <svg viewBox="0 0 20 20" fill="none" aria-hidden="true">
                        <line x1="5" y1="10" x2="15" y2="10" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" />
                        <path d="M11 5.5L15.5 10L11 14.5" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" fill="none" />
                    </svg>
                </span>
            </button>
        </div>
    </section>
    <?php endif; ?>

    <!-- 3. PAGE CONTENT -->
    <div class="wl-cms-content-wrap">
        <div class="wl-cms-container">
            <div class="wl-cms-content">
                <?php while (have_posts()):
                    the_post();
                    the_content(); endwhile; ?>
            </div>
        </div>
    </div>

</main>
<?php get_footer(); ?>