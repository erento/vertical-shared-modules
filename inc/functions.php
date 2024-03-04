<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    // =========================================================================
    // REMOVE JUNK FROM HEAD
    // =========================================================================
    remove_action('wp_head', 'rsd_link'); // remove really simple discovery link
    remove_action('wp_head', 'wp_generator'); // remove wordpress version
    remove_action('wp_head', 'feed_links', 2); // remove rss feed links
    remove_action('wp_head', 'feed_links_extra', 3); // removes all extra rss feed links
    remove_action('wp_head', 'index_rel_link'); // remove link to index page
    remove_action('wp_head', 'wlwmanifest_link'); // remove wlwmanifest.xml
    remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('publish_future_post', 'check_and_publish_future_post',   10, 1);
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    function remove_default_image_sizes() {
        remove_image_size( '2048x2048' );
        remove_image_size( '1536x1536' );
    }
    add_filter('init', 'remove_default_image_sizes');

    // Add Filters
    add_filter('show_admin_bar', '__return_false'); // Remove Admin bar
    add_filter('jpeg_quality', function() {return '95';});
    add_filter('http_request_host_is_external', '__return_true'); // Allow localhost uploads (media_sideload_image)
    add_filter('wp_sitemaps_enabled', '__return_false'); // disable default WP sitemap

    // Add Menu Support
    add_theme_support('menus');

    // Add Post Featured Image Theme Support
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'small', 260, 260, false );
    add_image_size( 'small_medium', 360, 360, false );
    add_image_size( 'og_size', 1200, 630, true );
    add_image_size( 'huge', 1920, 1200, true );

    function get_shared_template_part( $slug, $name = null, $args = array() ) {
        $templates = array();
        $name = (string) $name;
        if ( '' !== $name )
            $templates[] = "{$slug}-{$name}.php";
    
        $templates[] = "{$slug}.php";
    
        $external_template = SHARED_MODULES . $slug . '.php';

        if ( file_exists( $external_template ) ) {
            if ( !empty( $args ) ) {
                extract( $args );
            }
            require $external_template;
            return;
        }
    
        foreach ( $templates as $template_name ) {
            if ( $template_name ) {
                if ( !empty( $args ) ) {
                    extract( $args );
                }
                locate_template( $template_name, true, false );
                return;
            }
        }
    }

    function createResponsivePicture(
        $imgId,
        $srcSet = [],
        $sizes,
        $classes = false,
        $alt = false,
        $loading = false,
        $fetchpriority = false
    ) {
        if (!$imgId || empty($imgId)) {
            return false;
        }

        $image_html = '<img ';
            if ($classes) $image_html .= 'class="' . $classes . '" ';
            if ($loading) $image_html .= 'loading="' . $loading . '" ';
            if ($fetchpriority) $image_html .= 'fetchpriority="' . $fetchpriority . '" ';
            $image_html .= 'src="' . wp_get_attachment_image_src($imgId, $srcSet[0])[0] . '"';
            
            if (!empty($srcSet)) {
                $srcSetCounter = 0;
                $registeredSizes = wp_get_registered_image_subsizes();
                $image_html .= 'srcset="';
                foreach ($srcSet as $key => $srcSize) {
                    if (array_key_exists($srcSize, $registeredSizes)) {
                        if ($srcSetCounter > 0) $image_html .= ', '; $srcSetCounter ++;
                        $image_html .= $media_src = wp_get_attachment_image_src($imgId, $srcSize)[0] . ' ' . $registeredSizes[$srcSize]['width'] . 'w';
                    }
                }
                $image_html .= '"';
            }
            
            $image_html .= 'sizes="' . $sizes . '"';
            if ($alt) $image_html .= 'alt="' . $alt . '"';
        $image_html .= '>';

        return $image_html;
    }

    function custom_excerpt_length( $length ) { return 31; }
    add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

    function custom_excerpt_more( $more ) { return '...'; }
    add_filter( 'excerpt_more', 'custom_excerpt_more' );

    // Register Menu Display Locations
    function register_custom_menus() {
      register_nav_menus(
        array(
            'primary-menu' => __( 'Primary menu' ),
            'footer-legal-menu' => __( 'Footer Legal Menu' ),
            'mobile-hamburger-menu' => __( 'Mobile Hamburger Menu' ),
        )
      );
    }
    add_action( 'init', 'register_custom_menus' );

    // Remove edit post link from frontend
    function remove_all_edit_post_links( $link ) {
        return '';
    }
    add_filter('edit_post_link', 'remove_all_edit_post_links');

    // Remove the <div> surrounding the dynamic navigation to cleanup markup
    function my_wp_nav_menu_args($args = '') {
        $args['container'] = false;
        return $args;
    }
    add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation

    function get_svgs() {
        static $svgs = NULL;
        if ( empty( $svgs ) ) {
            $svgs = require_once(SHARED_MODULES . 'assets/svgs.php');
        }
        return $svgs;
    }
    add_action( 'template_redirect', 'get_svgs' );


    /////////////////////////////////////////////////////////////
    ///////////         ENQUE Scripts & Styles        ///////////
    /////////////////////////////////////////////////////////////

    // Scripts and HEAD styles
    function my_enqueue_scripts_styles() {
        wp_deregister_script('jquery');

        // Enqueue jQuery
        wp_enqueue_script( 'jQuery_js', get_template_directory_uri() . '/js/jquery-3.4.1.min.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/jquery-3.4.1.min.js' )), true );

        // Enqueue main script - script.js
        if (ENV == 'PROD') {
            wp_enqueue_script( 'customScript_js', get_template_directory_uri() . '/js/scripts.bundle.min.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/scripts.bundle.min.js' )), true );
        } else {
            wp_enqueue_script( 'customScript_js', get_template_directory_uri() . '/js/scripts.module.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/scripts.module.js' )), true );
        }

        // Enqueue Flickity
        wp_enqueue_script( 'flickity_js', get_template_directory_uri() . '/js/flickity.pkgd.min.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/flickity.pkgd.min.js' )), true );

        // Magnific Popup Js for Product Modal Gallery
        wp_enqueue_script( 'magnific_popup_js', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/jquery.magnific-popup.min.js' )), true );

        // Enqueue Jquery UI
        wp_enqueue_script( 'jquery_ui_js', get_template_directory_uri() . '/js/jquery-ui.js', array(), date("ymd-Gis", filemtime( get_template_directory() . '/js/jquery-ui.js' )), true );

        //////////////////////////////
        //////////////////////////////

        // Enque main styles - styles.css
        wp_enqueue_style( 'styles_css', get_template_directory_uri() . '/css/styles.min.css', array(), date("ymd-Gis", filemtime( get_template_directory() . '/css/styles.min.css' )), 'all' );
    }
    add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts_styles' );
    
    function changeJavascriptToModule($tag, $handle, $src) {
        if ("customScript_js" === $handle) {
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        return $tag;
    }
    add_filter("script_loader_tag", "changeJavascriptToModule", 10, 3);

    // FOOTER styles
    function prefix_add_footer_styles() {
        // Magnific Popup Css for Product Modal Gallery
        wp_enqueue_style( 'magnific_popup_css', get_template_directory_uri() . '/css/magnific-popup.css', array(), date("ymd-Gis", filemtime( get_template_directory() . '/style.css' )), 'all' );

        if (SPINOFFID !== 'sportauto') {
            wp_enqueue_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?libraries=places&language=de&callback=onMapInit&key=' . getGMapsApiKey(), array(), '1.0', true );
        }
    }
    add_action( 'get_footer', 'prefix_add_footer_styles' );

    // Deregister wp-embed js script
    function my_deregister_scripts(){
        wp_deregister_script( 'wp-embed' );
    }
    add_action( 'wp_footer', 'my_deregister_scripts' );

    ////////////////////////////////////////////////////

    // Remove media/attachment pages
    function test_attachment_redirect($redirect_url) {
        if( is_attachment() ) {
            fire404();
        } else {
            return $redirect_url;
        }
    }
    add_filter('template_redirect', 'test_attachment_redirect');
    add_filter('redirect_canonical', 'test_attachment_redirect', 0);

    ////////////////////////////////////////////////////

    function fire404() {
        buildMetaData(_t('Page not found', true), _t('Page not found', true), false);
        buildRobots('noindex,nofollow');
        status_header( 404 ); nocache_headers(); include( get_query_template( '404' ) ); die();
    }

    function wpd_do_stuff_on_404(){
        if( is_404() ){
            fire404();
        }
    }
    add_action( 'template_redirect', 'wpd_do_stuff_on_404' );


    /////////////////////////////////////////////
    ///////////         EMAILS        ///////////
    /////////////////////////////////////////////

    // Inline CSS Styles
    function inline_html_styles( $content ) {
        ob_start();
        if ( supports_emogrifier() ) {
            $emogrifier_class = '\\Pelago\\Emogrifier';
            if ( ! class_exists( $emogrifier_class ) ) {
                include_once get_template_directory() . '/vendor/pelago/emogrifier/src/Emogrifier.php';
            }
            try {
                $emogrifier = new $emogrifier_class( $content );
                $content    = $emogrifier->emogrify();
            } catch ( Exception $e ) {
                $logger = wc_get_logger();
                $logger->error( $e->getMessage(), array( 'source' => 'emogrifier' ) );
            }
        }
        ob_end_clean();
        return $content;
    }

    function supports_emogrifier() {
        return class_exists( 'DOMDocument' ) && version_compare( PHP_VERSION, '5.5', '>=' );
    }


    //////////////////////////////////////////////
    //// Add site fields to settings/general tabs
    //////////////////////////////////////////////
    function my_general_section() {
        add_settings_section(
            'my_settings_section', // Section ID
            "Site's display data", // Section Title
            'my_section_options_callback', // Callback
            'general' // What Page?  This makes the section show up on the General Settings Page
        );

        add_settings_field(
            'custom_site_email', "Site's Email", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_email')
        );

        add_settings_field(
            'custom_site_phone', "Site's Phone number", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_phone')
        );

        add_settings_field(
            'custom_site_company_name', "Company Name", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_company_name')
        );

        add_settings_field(
            'custom_site_company_address', "Company Address", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_company_address')
        );

        add_settings_field(
            'custom_site_company_zip', "Company Postal Code", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_company_zip')
        );

        add_settings_field(
            'custom_site_company_city', "Company City", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_company_city')
        );

        add_settings_field(
            'custom_site_company_country', "Company Country", 'my_textbox_callback', 'general', 'my_settings_section', array('custom_site_company_country')
        );

        register_setting('general','custom_site_email', 'esc_attr');
        register_setting('general','custom_site_phone', 'esc_attr');
        register_setting('general','custom_site_company_name', 'esc_attr');
        register_setting('general','custom_site_company_address', 'esc_attr');
        register_setting('general','custom_site_company_zip', 'esc_attr');
        register_setting('general','custom_site_company_city', 'esc_attr');
        register_setting('general','custom_site_company_country', 'esc_attr');
    }
    add_action('admin_init', 'my_general_section');

    function my_section_options_callback() { // Section Callback
        echo '<p>Email and phone number will be used across whole website (header, footer, contact form...)</p>';
    }

    function my_textbox_callback($args) {  // Textbox Callback
        $option = get_option($args[0]);
        echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
    }

    // beautify var_dump function. Used only for debugging.
    function pre_dump($dump) {
        echo "<pre>";
        print_r($dump);
        echo "</pre>";
    }

    function shuffle_assoc($list) {
        if (!is_array($list)) return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();

        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }

        return $random;
    }

    function isValidDate($date, $format) {
        if (DateTime::createFromFormat($format, $date)) return true;
        else return false;
    }

    function removeCF7ScriptsExceptOnContactPage() {
        if (basename(get_page_template()) != 'contact-us.php') {
            wp_dequeue_script('contact-form-7');
            wp_dequeue_script('google-recaptcha');
            wp_dequeue_script('wpcf7-recaptcha');
            wp_dequeue_style('wpcf7-recaptcha');
            wp_dequeue_style('contact-form-7');
        }
    }
    add_action('wp_enqueue_scripts', 'removeCF7ScriptsExceptOnContactPage', 99);
