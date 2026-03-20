<?php
/**
 * WANDERLAND — inc/cms-page-meta.php
 * Meta boxes for CMS Page template:
 *   - Tagline (above hero title)
 *   - Hero background image (override default)
 *   - Slider images (up to 8, drag-to-reorder)
 */

if (!defined('ABSPATH'))
    exit;

function wl_cms_meta_boxes()
{
    add_meta_box(
        'wl_cms_settings',
        __('CMS Page Settings — Hero & Slider', 'wanderland'),
        'wl_cms_meta_box_render',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wl_cms_meta_boxes');

function wl_cms_meta_box_render($post)
{
    wp_nonce_field('wl_cms_meta_save', 'wl_cms_meta_nonce');

    $hero_img_id = get_post_meta($post->ID, '_wl_cms_hero_image', true);
    $tagline = get_post_meta($post->ID, '_wl_cms_tagline', true);
    $slider_ids_raw = get_post_meta($post->ID, '_wl_cms_slider_images', true);
    $slider_ids = $slider_ids_raw
        ? array_filter(array_map('intval', explode(',', $slider_ids_raw)))
        : array();

    $hero_src = $hero_img_id
        ? wp_get_attachment_image_url(intval($hero_img_id), 'thumbnail')
        : '';
    ?>
<style>
.wl-meta-section {
    margin-bottom: 22px;
    padding-bottom: 22px;
    border-bottom: 1px solid #eee
}

.wl-meta-section:last-child {
    border-bottom: none;
    margin-bottom: 0
}

.wl-meta-section>label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    font-size: 13px
}

.wl-meta-hint {
    font-size: 11px;
    color: #888;
    margin: 4px 0 0
}

.wl-media-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px
}

.wl-media-row img {
    width: 80px;
    height: 54px;
    object-fit: cover;
    border: 1px solid #ddd;
    border-radius: 2px
}

.wl-slider-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
    min-height: 90px;
    align-items: flex-start
}

.wl-slider-item {
    position: relative;
    width: 82px;
    height: 82px;
    border: 1px solid #ddd;
    border-radius: 2px;
    cursor: grab;
    background: #f5f5f5
}

.wl-slider-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 2px;
    pointer-events: none
}

.wl-slider-item.drag-over {
    border: 2px dashed #b89d6e
}

.wl-slider-remove {
    position: absolute;
    top: 3px;
    right: 3px;
    width: 18px;
    height: 18px;
    background: rgba(180, 0, 0, .85);
    color: #fff;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    font-size: 12px;
    line-height: 18px;
    text-align: center;
    padding: 0;
    display: none
}

.wl-slider-item:hover .wl-slider-remove {
    display: block
}

.wl-slider-add {
    width: 82px;
    height: 82px;
    border: 2px dashed #ccc;
    background: #fafafa;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #ccc;
    border-radius: 2px;
    transition: all .18s
}

.wl-slider-add:hover {
    border-color: #b89d6e;
    color: #b89d6e
}
</style>

<!-- Tagline -->
<div class="wl-meta-section">
    <label for="wl_cms_tagline"><?php _e('Hero Tagline', 'wanderland'); ?></label>
    <input type="text" id="wl_cms_tagline" name="wl_cms_tagline" value="<?php echo esc_attr($tagline); ?>"
        class="widefat" placeholder="<?php esc_attr_e('e.g. Our Story — shown above title in hero', 'wanderland'); ?>">
    <p class="wl-meta-hint"><?php _e('Optional small text above the page title.', 'wanderland'); ?></p>
</div>

<!-- Hero image -->
<div class="wl-meta-section">
    <label><?php _e('Hero Background Image', 'wanderland'); ?></label>
    <p class="wl-meta-hint">
        <?php _e('Overrides default /images/cms-page.jpg. Recommended: 1920×900px.', 'wanderland'); ?></p>
    <input type="hidden" id="wl_cms_hero_image" name="wl_cms_hero_image" value="<?php echo esc_attr($hero_img_id); ?>">
    <div class="wl-media-row">
        <img id="wl_hero_preview" src="<?php echo esc_url($hero_src); ?>"
            style="<?php echo $hero_src ? '' : 'display:none'; ?>">
        <button type="button" class="button" id="wl_hero_choose">
            <?php _e('Choose Image', 'wanderland'); ?>
        </button>
        <button type="button" class="button" id="wl_hero_remove"
            style="<?php echo $hero_img_id ? '' : 'display:none'; ?>">
            <?php _e('Remove', 'wanderland'); ?>
        </button>
    </div>
</div>

<!-- Slider images -->
<div class="wl-meta-section">
    <label><?php _e('Slider Images', 'wanderland'); ?></label>
    <p class="wl-meta-hint">
        <?php _e('Up to 8 images. Drag to reorder. Portrait ratio recommended (e.g. 500×600px).', 'wanderland'); ?>
    </p>
    <input type="hidden" id="wl_cms_slider_images" name="wl_cms_slider_images"
        value="<?php echo esc_attr($slider_ids_raw); ?>">
    <div class="wl-slider-grid" id="wl_slider_grid">
        <?php foreach ($slider_ids as $sid):
                $thumb = wp_get_attachment_image_url($sid, 'thumbnail');
                if (!$thumb)
                    continue;
                ?>
        <div class="wl-slider-item" data-id="<?php echo intval($sid); ?>" draggable="true">
            <img src="<?php echo esc_url($thumb); ?>" alt="">
            <button type="button" class="wl-slider-remove">&times;</button>
        </div>
        <?php endforeach; ?>
        <button type="button" class="wl-slider-add" id="wl_slider_add">+</button>
    </div>
</div>

<script>
jQuery(function($) {

    // ── Hero image ─────────────────────────────────
    var heroFrame;
    $('#wl_hero_choose').click(function() {
        heroFrame = heroFrame || wp.media({
            title: 'Hero Image',
            multiple: false,
            button: {
                text: 'Use Image'
            }
        });
        heroFrame.on('select', function() {
            var a = heroFrame.state().get('selection').first().toJSON();
            $('#wl_cms_hero_image').val(a.id);
            var src = (a.sizes || {}).thumbnail ? a.sizes.thumbnail.url : a.url;
            $('#wl_hero_preview').attr('src', src).show();
            $('#wl_hero_remove').show();
        });
        heroFrame.open();
    });
    $('#wl_hero_remove').click(function() {
        $('#wl_cms_hero_image').val('');
        $('#wl_hero_preview').hide();
        $(this).hide();
    });

    // ── Slider images ───────────────────────────────
    function updateIds() {
        var ids = [];
        $('#wl_slider_grid .wl-slider-item').each(function() {
            ids.push($(this).data('id'));
        });
        $('#wl_cms_slider_images').val(ids.join(','));
    }

    $('#wl_slider_add').click(function() {
        if ($('#wl_slider_grid .wl-slider-item').length >= 8) {
            alert('Max 8 images.');
            return;
        }
        var frame = wp.media({
            title: 'Slider Images',
            multiple: true,
            button: {
                text: 'Add'
            }
        });
        frame.on('select', function() {
            frame.state().get('selection').each(function(a) {
                if ($('#wl_slider_grid .wl-slider-item').length >= 8) return;
                var d = a.toJSON();
                var src = (d.sizes || {}).thumbnail ? d.sizes.thumbnail.url : d.url;
                var $el = $('<div class="wl-slider-item" data-id="' + d.id +
                    '" draggable="true">' +
                    '<img src="' + src + '" alt="">' +
                    '<button type="button" class="wl-slider-remove">&times;</button></div>'
                    );
                $('#wl_slider_add').before($el);
            });
            updateIds();
        });
        frame.open();
    });

    // Remove
    $('#wl_slider_grid').on('click', '.wl-slider-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.wl-slider-item').remove();
        updateIds();
    });

    // Drag-to-reorder
    var dragged = null;
    $('#wl_slider_grid')
        .on('dragstart', '.wl-slider-item', function() {
            dragged = this;
            $(this).css('opacity', .4);
        })
        .on('dragend', '.wl-slider-item', function() {
            $(this).css('opacity', 1);
            dragged = null;
            updateIds();
        })
        .on('dragover', '.wl-slider-item', function(e) {
            e.preventDefault();
            if (!dragged || dragged === this) return;
            var mid = this.getBoundingClientRect().left + this.getBoundingClientRect().width / 2;
            $(dragged).detach();
            e.clientX < mid ? $(this).before(dragged) : $(this).after(dragged);
        });
});
</script>
<?php
}

function wl_cms_meta_save($post_id)
{
    if (!isset($_POST['wl_cms_meta_nonce']))
        return;
    if (!wp_verify_nonce($_POST['wl_cms_meta_nonce'], 'wl_cms_meta_save'))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;

    // Tagline
    update_post_meta(
        $post_id,
        '_wl_cms_tagline',
        isset($_POST['wl_cms_tagline']) ? sanitize_text_field($_POST['wl_cms_tagline']) : ''
    );

    // Hero image
    $hero = intval($_POST['wl_cms_hero_image'] ?? 0);
    $hero ? update_post_meta($post_id, '_wl_cms_hero_image', $hero)
        : delete_post_meta($post_id, '_wl_cms_hero_image');

    // Slider images
    $raw = sanitize_text_field($_POST['wl_cms_slider_images'] ?? '');
    $ids = array_slice(array_filter(array_map('intval', explode(',', $raw))), 0, 8);
    $ids ? update_post_meta($post_id, '_wl_cms_slider_images', implode(',', $ids))
        : delete_post_meta($post_id, '_wl_cms_slider_images');
}
add_action('save_post', 'wl_cms_meta_save');