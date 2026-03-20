<?php
/**
 * WANDERLAND — inc/contact-page-meta.php
 * Meta boxes for Contact Page template:
 *   - Hero image + tagline (reuse cms fields)
 *   - Address, Phone, Email, Hours
 *   - Google Maps embed URL
 */

if (!defined('ABSPATH'))
    exit;

function wl_contact_meta_box()
{
    add_meta_box(
        'wl_contact_settings',
        __('Contact Page Settings', 'wanderland'),
        'wl_contact_meta_render',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wl_contact_meta_box');

function wl_contact_meta_render($post)
{
    // Only show on contact template
    $template = get_page_template_slug($post->ID);
    if ($template !== 'page-contact.php')
        return;

    wp_nonce_field('wl_contact_meta_save', 'wl_contact_nonce');

    $hero_img_id = get_post_meta($post->ID, '_wl_cms_hero_image', true);
    $tagline = get_post_meta($post->ID, '_wl_cms_tagline', true);
    $address = get_post_meta($post->ID, '_wl_contact_address', true);
    $phone = get_post_meta($post->ID, '_wl_contact_phone', true);
    $email = get_post_meta($post->ID, '_wl_contact_email', true);
    $hours = get_post_meta($post->ID, '_wl_contact_hours', true);
    $map_url = get_post_meta($post->ID, '_wl_contact_map_embed', true);

    $hero_src = $hero_img_id
        ? wp_get_attachment_image_url(intval($hero_img_id), 'thumbnail') : '';
    ?>
<style>
.wl-cm-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px
}

.wl-cm-row.full {
    grid-template-columns: 1fr
}

.wl-cm-field label {
    display: block;
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 5px;
    letter-spacing: .03em
}

.wl-cm-field input,
.wl-cm-field textarea {
    width: 100%;
    box-sizing: border-box
}

.wl-cm-hint {
    font-size: 11px;
    color: #888;
    margin-top: 3px
}

.wl-media-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 6px
}

.wl-media-row img {
    width: 72px;
    height: 48px;
    object-fit: cover;
    border: 1px solid #ddd;
    border-radius: 2px
}

hr.wl-divider {
    border: none;
    border-top: 1px solid #eee;
    margin: 20px 0
}
</style>

<!-- Hero settings (shared with CMS page) -->
<div class="wl-cm-row">
    <div class="wl-cm-field">
        <label><?php _e('Hero Tagline', 'wanderland'); ?></label>
        <input type="text" name="wl_cms_tagline" value="<?php echo esc_attr($tagline); ?>"
            placeholder="<?php esc_attr_e('e.g. Reach Out To Us', 'wanderland'); ?>">
    </div>
    <div class="wl-cm-field">
        <label><?php _e('Hero Background Image', 'wanderland'); ?></label>
        <input type="hidden" id="wl_cms_hero_image" name="wl_cms_hero_image"
            value="<?php echo esc_attr($hero_img_id); ?>">
        <div class="wl-media-row">
            <img id="wl_hero_prev" src="<?php echo esc_url($hero_src); ?>"
                style="<?php echo $hero_src ? '' : 'display:none'; ?>">
            <button type="button" class="button" id="wl_hero_pick"><?php _e('Choose Image', 'wanderland'); ?></button>
            <button type="button" class="button" id="wl_hero_clear"
                style="<?php echo $hero_img_id ? '' : 'display:none'; ?>"><?php _e('Remove', 'wanderland'); ?></button>
        </div>
    </div>
</div>

<hr class="wl-divider">
<p style="font-weight:700;font-size:13px;margin:0 0 12px"><?php _e('Contact Information', 'wanderland'); ?></p>

<!-- Address + Hours -->
<div class="wl-cm-row">
    <div class="wl-cm-field">
        <label><?php _e('Address', 'wanderland'); ?></label>
        <textarea name="wl_contact_address" rows="3"><?php echo esc_textarea($address); ?></textarea>
        <p class="wl-cm-hint"><?php _e('Displayed in address card. Use line breaks.', 'wanderland'); ?></p>
    </div>
    <div class="wl-cm-field">
        <label><?php _e('Working Hours', 'wanderland'); ?></label>
        <textarea name="wl_contact_hours" rows="3"><?php echo esc_textarea($hours); ?></textarea>
        <p class="wl-cm-hint"><?php esc_html_e('e.g. Mon–Fri: 9am – 6pm', 'wanderland'); ?></p>
    </div>
</div>

<!-- Phone + Email -->
<div class="wl-cm-row">
    <div class="wl-cm-field">
        <label><?php _e('Phone', 'wanderland'); ?></label>
        <input type="text" name="wl_contact_phone" value="<?php echo esc_attr($phone); ?>"
            placeholder="<?php echo esc_attr(get_theme_mod('wl_phone', '')); ?>">
        <p class="wl-cm-hint"><?php _e('Leave empty to use global phone from Customizer.', 'wanderland'); ?></p>
    </div>
    <div class="wl-cm-field">
        <label><?php _e('Email', 'wanderland'); ?></label>
        <input type="email" name="wl_contact_email" value="<?php echo esc_attr($email); ?>"
            placeholder="<?php echo esc_attr(get_theme_mod('wl_email', '')); ?>">
        <p class="wl-cm-hint">
            <?php _e('Contact form sends to this email. Leave empty for Customizer email.', 'wanderland'); ?></p>
    </div>
</div>

<!-- Google Maps embed -->
<div class="wl-cm-row full">
    <div class="wl-cm-field">
        <label><?php _e('Google Maps Embed URL', 'wanderland'); ?></label>
        <input type="url" name="wl_contact_map_embed" value="<?php echo esc_url($map_url); ?>"
            placeholder="https://maps.google.com/maps?...&output=embed">
        <p class="wl-cm-hint">
            <?php _e('Get from Google Maps → Share → Embed a map → copy src URL only. Leave empty to hide map.', 'wanderland'); ?>
        </p>
    </div>
</div>

<script>
jQuery(function($) {
    var frame;
    $('#wl_hero_pick').click(function() {
        frame = frame || wp.media({
            title: 'Hero Image',
            multiple: false,
            button: {
                text: 'Use Image'
            }
        });
        frame.on('select', function() {
            var a = frame.state().get('selection').first().toJSON();
            $('#wl_cms_hero_image').val(a.id);
            var src = (a.sizes || {}).thumbnail ? a.sizes.thumbnail.url : a.url;
            $('#wl_hero_prev').attr('src', src).show();
            $('#wl_hero_clear').show();
        });
        frame.open();
    });
    $('#wl_hero_clear').click(function() {
        $('#wl_cms_hero_image').val('');
        $('#wl_hero_prev').hide();
        $(this).hide();
    });
});
</script>
<?php
}

function wl_contact_meta_save($post_id)
{
    if (!isset($_POST['wl_contact_nonce']))
        return;
    if (!wp_verify_nonce($_POST['wl_contact_nonce'], 'wl_contact_meta_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;
    if (get_page_template_slug($post_id) !== 'page-contact.php')
        return;

    $fields = array(
        'wl_cms_tagline' => '_wl_cms_tagline',
        'wl_contact_address' => '_wl_contact_address',
        'wl_contact_phone' => '_wl_contact_phone',
        'wl_contact_hours' => '_wl_contact_hours',
    );

    foreach ($fields as $post_key => $meta_key) {
        $val = isset($_POST[$post_key]) ? sanitize_text_field($_POST[$post_key]) : '';
        $val ? update_post_meta($post_id, $meta_key, $val)
            : delete_post_meta($post_id, $meta_key);
    }

    // Email
    $em = isset($_POST['wl_contact_email']) ? sanitize_email($_POST['wl_contact_email']) : '';
    $em ? update_post_meta($post_id, '_wl_contact_email', $em)
        : delete_post_meta($post_id, '_wl_contact_email');

    // Hero image
    $hero = intval($_POST['wl_cms_hero_image'] ?? 0);
    $hero ? update_post_meta($post_id, '_wl_cms_hero_image', $hero)
        : delete_post_meta($post_id, '_wl_cms_hero_image');

    // Map embed URL
    $map = isset($_POST['wl_contact_map_embed']) ? esc_url_raw($_POST['wl_contact_map_embed']) : '';
    $map ? update_post_meta($post_id, '_wl_contact_map_embed', $map)
        : delete_post_meta($post_id, '_wl_contact_map_embed');
}
add_action('save_post', 'wl_contact_meta_save');