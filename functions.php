<?php
/**
 * onward functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package onward
 */

if ( ! defined( '_ONWARD_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_ONWARD_VERSION', '1.0.6' );
}

if (!function_exists('is_plugin_active')):
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');  
endif;

if ( ! function_exists( 'onward_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function onward_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on onward, use a find and replace
		 * to change 'onward' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'onward', get_template_directory() . '/languages' );

		// Add WooCommerce Support.
		add_theme_support('woocommerce');
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'onward' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'onward_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/** Customizer Setup */
		$primary_color = get_theme_mod( 'onward_main_color' );

		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => __( 'Primary Color', 'onward' ),
					'slug'  => 'onward_main_color',
					'color' => $primary_color,
				),
			)
		);



	}
endif;
add_action( 'after_setup_theme', 'onward_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function onward_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'onward_content_width', 640 );
}
add_action( 'after_setup_theme', 'onward_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function onward_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Right Sidebar', 'onward' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'onward' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Left Sidebar', 'onward' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add widgets here.', 'onward' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'onward_widgets_init' );


/**
 * Add SVG definitions to the footer.
 */
function onward_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/assets/images/svg-icons.svg';

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		require $svg_icons ;
	}
}
add_action( 'wp_footer', 'onward_include_svg_icons', 9999 );

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
function onward_svg( $args = array() ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'onward' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'onward' );
	}

	// Set defaults.
	$defaults = array(
		'icon'        => '',
		'title'       => '',
		'desc'        => '',
		'class'        => '',
		'fallback'    => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	/* See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/ */
	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id    	 = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . esc_attr( $unique_id ) . '"';
		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . esc_attr( $unique_id ) . ' desc-' . esc_attr( $unique_id ) . '"';
		}
	}
	$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . ' ' . esc_attr( $args['class'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';
	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . esc_attr( $unique_id ) . '">' . esc_html( $args['title'] ) . '</title>';
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . esc_attr( $unique_id ) . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}
	$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
	}
	$svg .= '</svg>';
	return $svg;
}

/**
 * Fallback function call for menu
 * @param  Mixed $args Menu arguments
 * @return String $output Return or echo the add menu link.       
 */
function onward_menu_fallback_cb( $args ){
    if ( ! current_user_can( 'edit_theme_options' ) ){
	    return;
   	}
    // see wp-includes/nav-menu-template.php for available arguments
    $link = $args['link_before']
        	. '<a href="' .esc_url( admin_url( 'nav-menus.php' ) ) . '">' . $args['before'] . esc_html__( 'Create A New Menu','onward' ) . $args['after'] . '</a>'
        	. $args['link_after'];

   	if ( FALSE !== stripos( $args['items_wrap'], '<ul' ) || FALSE !== stripos( $args['items_wrap'], '<ol' )
	){
		$link = "<li>$link</li>";
	}
	$output = sprintf( $args['items_wrap'], $args['menu_id'], $args['menu_class'], $link );
	if ( ! empty ( $args['container'] ) ){
		$output = sprintf( '<%1$s class="%2$s" id="%3$s">%4$s</%1$s>', $args['container'], $args['container_class'], $args['container_id'], $output );
	}
	if ( $args['echo'] ){
		echo $output;
	}
	return $output;
}


/**
 * Enqueue scripts and styles.
 */
function onward_scripts() {

	wp_enqueue_style( 'onward-style', get_stylesheet_uri(), array(), _ONWARD_VERSION );
	wp_enqueue_style( 'onward-main-css', get_template_directory_uri() . '/css/onward-main.css',false,'1.1','all');
	// add_editor_style( 'main-css', get_template_directory_uri() . '/css/main.css',false,'1.1','all');
	
	$woocommerce_style	= get_theme_mod( 'onward_woocommerce_settings', onward_defaults( 'onward_woocommerce_settings' ) );
	$woo_style			= isset($woocommerce_style) ? $woocommerce_style : '';
	
	switch ($woo_style) {
		case 'modern':
			wp_enqueue_style( 'onward-woo-modern-css', get_template_directory_uri() . '/css/woocommerce/woo-modern.css',false,'1.1','all');
			break;
		case 'minimal':
			wp_enqueue_style( 'onward-woo-minimal-css', get_template_directory_uri() . '/css/woocommerce/woo-minimal.css',false,'1.1','all');
			break;
		default:
			wp_enqueue_style( 'onward-woo-basic-css', get_template_directory_uri() . '/css/woocommerce/woo-basic.css',false,'1.1','all');
			wp_enqueue_script( 'onward-woo-basic-js', get_template_directory_uri() . '/js/woocommerce/woo-basic.js', array('jquery'), '1.1', 'all' );
			break;
	}


	wp_style_add_data( 'onward-style', 'rtl', 'replace' );

	wp_enqueue_script( 'onward-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _ONWARD_VERSION, true );
	
	$onward_l10n = array(
		'quote'          => onward_svg( array( 'icon' => 'quote-right' ) ),
		'expand'         => esc_html__( 'Expand child menu', 'onward' ),
		'collapse'       => esc_html__( 'Collapse child menu', 'onward' ),
		'icon'           => onward_svg( array( 'icon' => 'down', 'fallback' => true ) ),
	);
	wp_localize_script( 'onward-navigation', 'onward_l10n', $onward_l10n );


	wp_enqueue_script( 'onward-custom-js', get_template_directory_uri() . '/js/onward-custom-js.js', array('jquery'), _ONWARD_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'onward_scripts' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

// if ( ! is_plugin_active( 'onward-core/onward-core.php' ) ) {

	/**
	 * Customizer defaults.
	 */
	require get_template_directory() . '/inc/defaults.php';

	/**
	 * Customizer additions.
	 */
	require get_template_directory() . '/inc/customizer.php';

	/**
	 * Custom Controller Customizer.
	 */
	require get_template_directory() . '/inc/custom-controls.php';

	/**
	 * Customizer Helper.
	 */
	require get_template_directory() . '/inc/custom-header.php';

	/**
	 * Main customizer css render.
	 */
	require get_template_directory() . '/inc/dynamic-styles/main-customizer-css.php';

// }

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * TGMPA plugin activation.
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';


 /**
 * Onward WooCommerce - cart template
 */
require_once get_template_directory() . '/woocommerce/onward-cart.php';


 /**
 * Onward WooCommerce - account template
 */
require_once get_template_directory() . '/woocommerce/onward-account.php';


/**
 * Register the required plugins for this theme.
 */
function onward_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     */
    $plugins = array();

    // Add the required plugins.
    $plugins[] = array(
        'name'      => 'Elementor Website Builder',
        'slug'      => 'elementor',
        'required'  => false,
      );
    $plugins[] = array(
        'name'      => 'Black Widgets For Elementor',
        'slug'      => 'black-widgets',
        'required'  => false,
      );
    $plugins[] = array(
        'name'      => 'Elementor – Header, Footer & Blocks Template',
        'slug'      => 'header-footer-elementor',
        'required'  => false,
      );

    // Add the Meta Box and Redux Framework plugins if Onward Core is active.
    if ( is_plugin_active( 'onward-core/onward-core.php' ) ) {
        // $plugins[] = array(
        //     'name'      => 'Meta Box',
        //     'slug'      => 'meta-box',
        //     'required'  => false,
        // );
        $plugins[] = array(
            'name'      => 'Redux Framework',
            'slug'      => 'redux-framework',
            'required'  => false,
        );
    }

    /*
     * Array of configuration settings. Amend each line as needed.
     */
    $config = array(
        'id'           => 'onward',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

    );

    // Merge the $plugins array with the array of plugins that we want to register.
    $plugins = array_merge( $plugins, $plugins );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'onward_register_required_plugins' );

//Onward Patterns
if ( function_exists( 'register_block_pattern_category' ) ) {
    register_block_pattern_category(
        'onward-cta',
        array( 'label' => esc_html__( 'Onward CTA', 'onward' ) )
    );

    register_block_pattern_category(
        'onward-hero',
        array( 'label' => esc_html__( 'Onward Hero', 'onward' ) )
    );

    // Corrected code
    register_block_pattern(
        'onward/my-custom-query-pattern',
        array(
            'categories'  => array( 'onward-cta' ),
            'title'  => esc_html__( 'My Custom Query Pattern', 'onward' ),
            'content' => wp_json_encode( [
                 'core/group' => [
                     'innerBlocks' => [
                         // Your blocks here
                    ],
                ],
            ] ),
        )
    );
}



// register_block_pattern(
// 	'theme-slug/onward',
// 	array(
//          	'categories'  => array( 'onward' ),
//         )
// );



