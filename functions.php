<?php
/**
 * WANDERLAND — functions.php
 * Theme setup, menus, assets, custom walkers
 */

if (!defined('ABSPATH'))
    exit;


// ── Theme setup ──────────────────────────────────────────────
function wl_theme_setup()
{
    load_theme_textdomain('wanderland', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo', array(
        'height' => 160,
        'width' => 286,
        'flex-height' => true,
        'flex-width' => true,
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('post-formats', array('video', 'audio', 'gallery', 'quote', 'link'));

    add_image_size('wl-hero', 1920, 900, true);
    add_image_size('wl-card', 800, 600, true);
    add_image_size('wl-card-sm', 600, 450, true);
    add_image_size('wl-thumb', 400, 300, true);
    add_image_size('wl-wide', 1280, 720, true);

    register_nav_menus(array(
        'header-left' => __('Header — Left (Home / Pages / Destinations)', 'wanderland'),
        'header-right' => __('Header — Right (Blog / Shop / Landing)', 'wanderland'),
        'mobile' => __('Mobile Menu', 'wanderland'),
        'footer' => __('Footer Menu', 'wanderland'),
    ));
}
add_action('after_setup_theme', 'wl_theme_setup');


// ── Content width ────────────────────────────────────────────
function wl_content_width()
{
    $GLOBALS['content_width'] = apply_filters('wl_content_width', 1280);
}
add_action('after_setup_theme', 'wl_content_width', 0);


// ── Enqueue tất cả assets ────────────────────────────────────
function wl_enqueue_assets()
{

    wp_enqueue_style(
        'wl-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Outfit:wght@300;400;500;600;700&family=Dancing+Script:wght@600&display=swap',
        array(),
        null
    );
    $ver = '1.1.7';

    $uri = get_template_directory_uri();


    // wp_enqueue_style(
    //     'ionicons',
    //     'https://unpkg.com/ionicons@4.6.3/dist/css/ionicons.min.css',
    //     array(),
    //     '4.6.3'
    // );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
        array(),
        '5.15.4'
    );

    wp_enqueue_style(
        'ionicons',
        'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
        array(),
        '2.0.1'
    );

    wp_enqueue_style(
        'wl-global',
        $uri . '/css/style.css',
        array('ionicons', 'font-awesome'),
        $ver
    );

    wp_enqueue_style(
        'wl-header',
        $uri . '/css/header.css',
        array('wl-global'),
        $ver
    );

    wp_enqueue_style(
        'wl-footer',
        $uri . '/css/footer.css',
        array('wl-global'),
        $ver
    );

    wp_enqueue_script(
        'wl-header',
        $uri . '/js/header.js',
        array(),
        $ver,
        true
    );

    wp_enqueue_script(
        'wl-footer',
        $uri . '/js/footer.js',
        array(),
        $ver,
        true
    );

    // ── PAGE-SPECIFIC ───────────────────────────────────────

    if (is_front_page()) {
        wp_enqueue_style('wl-home', $uri . '/css/home.css', array('wl-global'), $ver);
        wp_enqueue_script('wl-home', $uri . '/js/home.js', array(), $ver, true);
    }

    if (is_single()) {
        wp_enqueue_style('wl-single', $uri . '/css/single.css', array('wl-global'), $ver);
        wp_enqueue_script('wl-single', $uri . '/js/single.js', array(), $ver, true);
    }

    if (is_archive() || (is_home() && !is_front_page())) {
        wp_enqueue_style('wl-archive', $uri . '/css/archive.css', array('wl-global'), $ver);
        wp_enqueue_script('wl-archive', $uri . '/js/archive.js', array(), $ver, true);
    }

    if (is_page() && !is_front_page()) {
        wp_enqueue_style('wl-page', $uri . '/css/page.css', array('wl-global'), $ver);
    }

    if (is_search()) {
        wp_enqueue_style('wl-archive', $uri . '/css/archive.css', array('wl-global'), $ver);
        wp_enqueue_style('wl-search', $uri . '/css/search.css', array('wl-archive'), $ver);
        wp_enqueue_script('wl-search', $uri . '/js/search.js', array(), $ver, true);
    }

    if (is_404()) {
        wp_enqueue_style('wl-404', $uri . '/css/404.css', array('wl-global'), $ver);
        wp_enqueue_script('wl-404', $uri . '/js/404.js', array(), $ver, true);
    }

    if (is_page_template('page-cms.php')) {
        wp_enqueue_style('wl-page-cms', $uri . '/css/page-cms.css', array('wl-global'), $ver);
        wp_enqueue_script('wl-page-cms', $uri . '/js/page-cms.js', array(), $ver, true);
    }

    if (is_page_template('page-contact.php')) {
        wp_enqueue_style('wl-page-cms', $uri . '/css/page-cms.css', array('wl-global'), $ver);
        wp_enqueue_style('wl-page-contact', $uri . '/css/page-contact.css', array('wl-page-cms'), $ver);
        wp_enqueue_script('wl-page-contact', $uri . '/js/page-contact.js', array(), $ver, true);
    }
}
add_action('wp_enqueue_scripts', 'wl_enqueue_assets');

require_once get_template_directory() . '/inc/home-options.php';


require_once get_template_directory() . '/inc/functions-newsletter.php';

require_once get_template_directory() . '/inc/destinations-cpt.php';

require_once get_template_directory() . '/inc/cms-page-meta.php';

require_once get_template_directory() . '/inc/contact-page-meta.php';


// ── Customizer settings ──────────────────────────────────────
function wl_customizer_settings($wp_customize)
{
    // Logo variants
    foreach (array('wl_logo_dark', 'wl_logo_light', 'wl_logo_mobile', 'wl_logo_footer') as $key) {
        $wp_customize->add_setting($key, array(
            'default' => '',
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ));
    }

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'wl_logo_dark', array(
        'label' => __('Logo — Dark (sticky)', 'wanderland'),
        'section' => 'title_tagline',
        'mime_type' => 'image',
    )));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'wl_logo_light', array(
        'label' => __('Logo — Light (transparent header)', 'wanderland'),
        'section' => 'title_tagline',
        'mime_type' => 'image',
    )));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'wl_logo_mobile', array(
        'label' => __('Logo — Mobile', 'wanderland'),
        'section' => 'title_tagline',
        'mime_type' => 'image',
    )));

    // Contact info (Top Bar)
    $wp_customize->add_section('wl_contact', array(
        'title' => __('Contact Info (Top Bar)', 'wanderland'),
        'priority' => 30,
    ));
    $wp_customize->add_setting('wl_phone', array(
        'default' => '+123 45677 789',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_setting('wl_email', array(
        'default' => 'wanderland@example.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('wl_phone', array(
        'label' => __('Phone', 'wanderland'),
        'section' => 'wl_contact',
        'type' => 'text',
    ));
    $wp_customize->add_control('wl_email', array(
        'label' => __('Email', 'wanderland'),
        'section' => 'wl_contact',
        'type' => 'email',
    ));

    // Social links
    $wp_customize->add_section('wl_social', array(
        'title' => __('Social Links', 'wanderland'),
        'priority' => 31,
    ));
    foreach (array('instagram', 'twitter', 'facebook', 'youtube') as $network) {
        $wp_customize->add_setting('wl_social_' . $network, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('wl_social_' . $network, array(
            'label' => ucfirst($network) . ' URL',
            'section' => 'wl_social',
            'type' => 'url',
        ));
    }

    // Footer settings
    $wp_customize->add_section('wl_footer', array(
        'title' => __('Footer Settings', 'wanderland'),
        'priority' => 32,
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'wl_logo_footer', array(
        'label' => __('Footer Logo', 'wanderland'),
        'section' => 'wl_footer',
        'mime_type' => 'image',
    )));
    $wp_customize->add_setting('wl_footer_bio', array(
        'default' => 'Based in Utah, USA, Wanderland is a blog by Markus <span class="mkdf-footer-highlight">Thompson.</span> His posts<br>explore outdoor experiences through photos and diaries with tips &amp; tricks.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('wl_footer_bio', array(
        'label' => __('Footer Bio Text (HTML allowed)', 'wanderland'),
        'section' => 'wl_footer',
        'type' => 'textarea',
    ));
    $wp_customize->add_setting('wl_footer_about', array(
        'default' => 'Lorem ipsum dolor sit amet, conse ctetur adipisicing elit, sed do eiusmod mas.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('wl_footer_about', array(
        'label' => __('About the Blog Text', 'wanderland'),
        'section' => 'wl_footer',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'wl_customizer_settings');


// ── Desktop Header Walker ────────────────────────────────────
class WL_Header_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        if ($args->walker->has_children)
            $classes[] = 'has_sub';
        // narrow class cho top-level
        if ($depth === 0)
            $classes[] = 'narrow';

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $output .= '<li class="' . esc_attr($class_names) . '">';

        $atts = array();
        $atts['href'] = !empty($item->url) ? $item->url : '#';
        $atts['class'] = in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes) ? 'current' : '';

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value))
                $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
        }

        if ($depth === 0) {
            // Top-level: brush stroke SVG wrapper
            $output .= '<a' . $attributes . '>';
            $output .= '<span class="mkdf-menu-item-holder">';

            // Brush stroke shape
            // $output .= '<span class="mkdf-active-hover">';
            // $output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.7 30" class="mkdf-active-hover-left"><polygon points="2.6,1 0.7,3.3 2,5.8 2.3,7.6 2.9,8.7 4.4,10.5 3.9,10.8 4.4,11.9 4.4,12.8 4.1,13.8 3.3,14.7 3.9,15.8 4.4,16.8 4,17.5 3.5,18.1 2.2,20.2 3.4,21.5 4.2,24.1 3.4,25.4 2.5,27.4 2.5,27.8 3.2,28.3 4.1,28.5 4.9,29 14.8,29 14.8,1"/></svg>';
            // $output .= '<span class="mkdf-active-hover-middle"></span>';
            // $output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.3 30" class="mkdf-active-hover-right"><polygon points="10,1 10.2,2.1 10.6,2.9 10.6,3.3 10.8,3.7 10.8,4.3 11,5 11,5.7 11,6.3 10.5,6.7 10.8,7.3 11,7.8 11.6,8.3 11.6,8.6 11.5,8.9 11.6,9.9 11.6,10.5 12.4,11.6 12.1,12 12.4,12.2 11.8,12.8 11.4,13.5 11.6,13.7 11.9,13.7 12,13.9 11.5,15.1 10.8,16 9.1,17.7 9.7,18.2 9.3,19 9.7,19.8 9.6,20.6 9.7,21.5 9.6,21.9 9.6,22.3 10.1,22.8 9.6,23.6 9.7,24 9.7,24.2 9.9,24.4 9.5,24.7 9.3,25.4 9.3,25.9 8.8,26.2 8.5,27.1 8.8,27.8 9.4,28.6 7.8,29 0.9,29 0.9,1"/></svg>';
            // $output .= '</span>';
            $output .= '<span class="mkdf-active-hover"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.7 30" class="mkdf-active-hover-left"><polygon fill="currentColor" points="2.6,1 0.7,3.3 2,5.8 2.3,7.6 2.9,8.7 4.4,10.5 3.9,10.8 4.4,11.9 4.4,12.8 4.1,13.8 3.3,14.7 3.9,15.8 4.4,16.8 4,17.5 3.5,18.1 2.2,20.2 3.4,21.5 4.2,24.1 3.4,25.4 2.5,27.4 2.5,27.8 3.2,28.3 4.1,28.5 4.9,29 14.8,29 14.8,1"/></svg><span class="mkdf-active-hover-middle"></span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13.3 30" class="mkdf-active-hover-right"><polygon fill="currentColor" points="10,1 10.2,2.1 10.6,2.9 10.6,3.3 10.8,3.7 10.8,4.3 11,5 11,5.7 11,6.3 10.5,6.7 10.8,7.3 11,7.8 11.6,8.3 11.6,8.6 11.5,8.9 11.6,9.9 11.6,10.5 12.4,11.6 12.1,12 12.4,12.2 11.8,12.8 11.4,13.5 11.6,13.7 11.9,13.7 12,13.9 11.5,15.1 10.8,16 9.1,17.7 9.7,18.2 9.3,19 9.7,19.8 9.6,20.6 9.7,21.5 9.6,21.9 9.6,22.3 10.1,22.8 9.6,23.6 9.7,24 9.7,24.2 9.9,24.4 9.5,24.7 9.3,25.4 9.3,25.9 8.8,26.2 8.5,27.1 8.8,27.8 9.4,28.6 7.8,29 0.9,29 0.9,1"/></svg></span>';

            // Text + arrow
            $output .= '<span class="item_outer">';
            $output .= '<span class="item_text">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
            if ($args->walker->has_children) {
                $output .= '<i class="mkdf-menu-arrow fa fa-angle-down"></i>';
            }
            $output .= '</span>';
            $output .= '</span>';
            $output .= '</a>';
        } else {
            // Dropdown items: structure đơn giản hơn
            $output .= '<a' . $attributes . '>';
            $output .= '<span class="item_outer">';
            $output .= '<span class="item_text">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
            $output .= '</span>';
            $output .= '</a>';
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        // Dropdown bên phải cần class "right" để căn phải
        $is_right = isset($args->theme_location) && $args->theme_location === 'header-right';
        $div_class = $is_right ? 'second right' : 'second';
        $ul_class = $is_right ? ' class="right"' : '';
        $output .= '<div class="' . $div_class . '"><div class="inner"><ul' . $ul_class . '>';
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '</ul></div></div>';
    }
}


// ── Mobile Header Walker ─────────────────────────────────────
class WL_Mobile_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes);

        if ($has_children)
            $classes[] = 'has_sub';

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $id_attr = apply_filters('nav_menu_item_id', 'mobile-menu-item-' . $item->ID, $item, $args, $depth);
        $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

        $output .= '<li' . $id_attr . $class_names . '>';

        $href = !empty($item->url) ? $item->url : '#';
        $output .= '<a href="' . esc_url($href) . '"' . ($has_children ? ' class="mkdf-mobile-no-link"' : '') . '>';
        $output .= '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
        $output .= '</a>';

        if ($has_children) {
            $output .= '<span class="mobile_arrow">';
            $output .= '<i class="mkdf-sub-arrow ion-ios-arrow-right"></i>';
            $output .= '<i class="ion-ios-arrow-down"></i>';
            $output .= '</span>';
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '<ul class="sub_menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '</ul>';
    }
}

add_filter('show_admin_bar', '__return_false');


register_sidebar(array(
    'name' => __('Single Post Sidebar', 'wanderland'),
    'id' => 'single-sidebar',
    'before_widget' => '<div class="wl-sidebar-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h5 class="wl-widget-title">',
    'after_title' => '</h5>',
));

function wl_register_footer_widgets()
{
    $cols = array(
        array('id' => 'footer-col-1', 'name' => 'Footer — Col 1 (About)'),
        array('id' => 'footer-col-2', 'name' => 'Footer — Col 2 (Newsletter)'),
        array('id' => 'footer-col-3', 'name' => 'Footer — Col 3 (Recent News)'),
        array('id' => 'footer-col-4', 'name' => 'Footer — Col 4 (Categories)'),
    );

    foreach ($cols as $col) {
        register_sidebar(array(
            'name' => __($col['name'], 'wanderland'),
            'id' => $col['id'],
            'before_widget' => '<div class="mkdf-footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="mkdf-widget-title">',
            'after_title' => '</h6>',
        ));
    }
}
add_action('widgets_init', 'wl_register_footer_widgets');