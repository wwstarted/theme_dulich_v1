<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * WANDERLAND — page-contact.php
 * Contact page: hero + info cards + form
 */

get_header();

$post_id = get_the_ID();

// ── Meta fields ──────────────────────────────────────────────
$hero_img_id = get_post_meta($post_id, '_wl_cms_hero_image', true);
$hero_bg = $hero_img_id
    ? wp_get_attachment_image_url(intval($hero_img_id), 'full')
    : get_template_directory_uri() . '/images/cms-page.jpg';
$tagline = get_post_meta($post_id, '_wl_cms_tagline', true);

$address = get_post_meta($post_id, '_wl_contact_address', true) ?: '';
$phone = get_post_meta($post_id, '_wl_contact_phone', true) ?: get_theme_mod('wl_phone', '');
$email = get_post_meta($post_id, '_wl_contact_email', true) ?: get_theme_mod('wl_email', '');
$hours = get_post_meta($post_id, '_wl_contact_hours', true) ?: '';
$map_url = get_post_meta($post_id, '_wl_contact_map_embed', true) ?: '';

// ── Form submission ──────────────────────────────────────────
$form_sent = false;
$form_error = '';

if (isset($_POST['wl_contact_submit']) && wp_verify_nonce($_POST['_wl_nonce'] ?? '', 'wl_contact_form')) {
    $cf_name = sanitize_text_field($_POST['cf_name'] ?? '');
    $cf_email = sanitize_email($_POST['cf_email'] ?? '');
    $cf_subject = sanitize_text_field($_POST['cf_subject'] ?? '');
    $cf_message = sanitize_textarea_field($_POST['cf_message'] ?? '');

    if (empty($cf_name) || empty($cf_email) || empty($cf_message)) {
        $form_error = __('Please fill in all required fields.', 'wanderland');
    } elseif (!is_email($cf_email)) {
        $form_error = __('Please enter a valid email address.', 'wanderland');
    } else {
        $to = $email ?: get_option('admin_email');
        $subject = $cf_subject ?: sprintf(__('Contact from %s', 'wanderland'), $cf_name);
        $body = sprintf("Name: %s\nEmail: %s\n\n%s", $cf_name, $cf_email, $cf_message);
        $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $cf_email);

        $sent = wp_mail($to, $subject, $body, $headers);
        $form_sent = $sent;
        if (!$sent)
            $form_error = __('Message could not be sent. Please try again.', 'wanderland');
    }
}
?>

<main id="wl-contact-main" class="wl-cms-main wl-contact-main">

    <!-- ── HERO ── -->
    <section class="wl-cms-hero wl-contact-hero" style="background-image: url('<?php echo esc_url($hero_bg); ?>');">
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

    <!-- ── CONTACT BODY ── -->
    <div class="wl-contact-body">
        <div class="wl-contact-container">

            <!-- LEFT: Info cards + optional map -->
            <aside class="wl-contact-info">

                <h2 class="wl-contact-info-title">
                    <?php esc_html_e('Get In Touch', 'wanderland'); ?>
                </h2>

                <?php if (has_excerpt()): ?>
                <p class="wl-contact-info-desc"><?php the_excerpt(); ?></p>
                <?php endif; ?>

                <!-- Info cards -->
                <div class="wl-contact-cards">

                    <?php if ($address): ?>
                    <div class="wl-contact-card">
                        <div class="wl-contact-card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </div>
                        <div class="wl-contact-card-text">
                            <span class="wl-contact-card-label">
                                <?php esc_html_e('Address', 'wanderland'); ?>
                            </span>
                            <span class="wl-contact-card-value">
                                <?php echo nl2br(esc_html($address)); ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($phone): ?>
                    <div class="wl-contact-card">
                        <div class="wl-contact-card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="wl-contact-card-text">
                            <span class="wl-contact-card-label">
                                <?php esc_html_e('Phone', 'wanderland'); ?>
                            </span>
                            <a class="wl-contact-card-value wl-contact-card-link"
                                href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($email): ?>
                    <div class="wl-contact-card">
                        <div class="wl-contact-card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <rect x="2" y="4" width="20" height="16" rx="2" stroke="currentColor"
                                    stroke-width="1.5" />
                                <path d="M2 7l10 7 10-7" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="wl-contact-card-text">
                            <span class="wl-contact-card-label">
                                <?php esc_html_e('Email', 'wanderland'); ?>
                            </span>
                            <a class="wl-contact-card-value wl-contact-card-link"
                                href="mailto:<?php echo esc_attr($email); ?>">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($hours): ?>
                    <div class="wl-contact-card">
                        <div class="wl-contact-card-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
                                <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="wl-contact-card-text">
                            <span class="wl-contact-card-label">
                                <?php esc_html_e('Working Hours', 'wanderland'); ?>
                            </span>
                            <span class="wl-contact-card-value">
                                <?php echo nl2br(esc_html($hours)); ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                </div><!-- .wl-contact-cards -->

                <!-- Social links -->
                <div class="wl-contact-social">
                    <?php if ($ig = get_theme_mod('wl_social_instagram')): ?>
                    <a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener noreferrer"
                        class="wl-contact-social-btn" aria-label="Instagram">
                        <i class="ion-social-instagram"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($tw = get_theme_mod('wl_social_twitter')): ?>
                    <a href="<?php echo esc_url($tw); ?>" target="_blank" rel="noopener noreferrer"
                        class="wl-contact-social-btn" aria-label="Twitter">
                        <i class="ion-social-twitter"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($fb = get_theme_mod('wl_social_facebook')): ?>
                    <a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener noreferrer"
                        class="wl-contact-social-btn" aria-label="Facebook">
                        <i class="ion-social-facebook"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($yt = get_theme_mod('wl_social_youtube')): ?>
                    <a href="<?php echo esc_url($yt); ?>" target="_blank" rel="noopener noreferrer"
                        class="wl-contact-social-btn" aria-label="YouTube">
                        <i class="ion-social-youtube"></i>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Optional map embed -->
                <?php if ($map_url): ?>
                <div class="wl-contact-map">
                    <iframe src="<?php echo esc_url($map_url); ?>" width="100%" height="260" style="border:0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="<?php esc_attr_e('Location map', 'wanderland'); ?>">
                    </iframe>
                </div>
                <?php endif; ?>

            </aside>

            <!-- RIGHT: Contact form -->
            <div class="wl-contact-form-wrap">

                <h2 class="wl-contact-form-title">
                    <?php esc_html_e('Send a Message', 'wanderland'); ?>
                </h2>

                <?php if ($form_sent): ?>
                <div class="wl-contact-success" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M8 12l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <p><?php esc_html_e('Your message has been sent successfully. We\'ll get back to you soon!', 'wanderland'); ?>
                    </p>
                </div>
                <?php else: ?>

                <?php if ($form_error): ?>
                <div class="wl-contact-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 8v5M12 16v.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <p><?php echo esc_html($form_error); ?></p>
                </div>
                <?php endif; ?>

                <form class="wl-contact-form" id="wl-contact-form" method="post" action="#wl-contact-form" novalidate>
                    <?php wp_nonce_field('wl_contact_form', '_wl_nonce'); ?>
                    <input type="hidden" name="wl_contact_submit" value="1">

                    <!-- Row 1: Name + Email -->
                    <div class="wl-cf-row wl-cf-row--2">
                        <div class="wl-cf-field">
                            <label class="wl-cf-label" for="cf_name">
                                <?php esc_html_e('Your Name', 'wanderland'); ?>
                                <span class="wl-cf-required" aria-hidden="true">*</span>
                            </label>
                            <input type="text" id="cf_name" name="cf_name" class="wl-cf-input"
                                placeholder="<?php esc_attr_e('John Doe', 'wanderland'); ?>"
                                value="<?php echo isset($_POST['cf_name']) ? esc_attr(sanitize_text_field($_POST['cf_name'])) : ''; ?>"
                                required autocomplete="name">
                        </div>
                        <div class="wl-cf-field">
                            <label class="wl-cf-label" for="cf_email">
                                <?php esc_html_e('Email Address', 'wanderland'); ?>
                                <span class="wl-cf-required" aria-hidden="true">*</span>
                            </label>
                            <input type="email" id="cf_email" name="cf_email" class="wl-cf-input"
                                placeholder="<?php esc_attr_e('john@example.com', 'wanderland'); ?>"
                                value="<?php echo isset($_POST['cf_email']) ? esc_attr(sanitize_email($_POST['cf_email'])) : ''; ?>"
                                required autocomplete="email">
                        </div>
                    </div>

                    <!-- Row 2: Subject -->
                    <div class="wl-cf-row">
                        <div class="wl-cf-field">
                            <label class="wl-cf-label" for="cf_subject">
                                <?php esc_html_e('Subject', 'wanderland'); ?>
                            </label>
                            <input type="text" id="cf_subject" name="cf_subject" class="wl-cf-input"
                                placeholder="<?php esc_attr_e('How can we help you?', 'wanderland'); ?>"
                                value="<?php echo isset($_POST['cf_subject']) ? esc_attr(sanitize_text_field($_POST['cf_subject'])) : ''; ?>">
                        </div>
                    </div>

                    <!-- Row 3: Message -->
                    <div class="wl-cf-row">
                        <div class="wl-cf-field">
                            <label class="wl-cf-label" for="cf_message">
                                <?php esc_html_e('Message', 'wanderland'); ?>
                                <span class="wl-cf-required" aria-hidden="true">*</span>
                            </label>
                            <textarea id="cf_message" name="cf_message" class="wl-cf-textarea"
                                placeholder="<?php esc_attr_e('Write your message here...', 'wanderland'); ?>" required
                                rows="6"><?php echo isset($_POST['cf_message']) ? esc_textarea(sanitize_textarea_field($_POST['cf_message'])) : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="wl-cf-row wl-cf-row--submit">
                        <button type="submit" class="wl-cf-submit" id="wl-cf-submit">
                            <span class="wl-cf-submit-text">
                                <?php esc_html_e('Send Message', 'wanderland'); ?>
                            </span>
                            <span class="wl-cf-submit-icon" aria-hidden="true">
                                <svg viewBox="0 0 20 20" fill="none">
                                    <path d="M3 10h14M10 4l6 6-6 6" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>
                        <p class="wl-cf-required-note">
                            <span class="wl-cf-required">*</span>
                            <?php esc_html_e('Required fields', 'wanderland'); ?>
                        </p>
                    </div>

                </form>

                <?php endif; ?>

            </div><!-- .wl-contact-form-wrap -->

        </div><!-- .wl-contact-container -->
    </div><!-- .wl-contact-body -->

</main>

<?php get_footer(); ?>