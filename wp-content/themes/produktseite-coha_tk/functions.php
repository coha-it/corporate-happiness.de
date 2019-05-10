<?php
/**
 * _tk functions and definitions
 *
 * @package _tk
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 1400; /* pixels */

if ( ! function_exists( '_tk_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function _tk_setup() {
	global $cap, $content_width;

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	/**
	 * Add default posts and comments RSS feed links to head
	*/
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	*/
	add_theme_support( 'post-thumbnails' );
	
	// Register the three useful image sizes for use in Add Media modal
	if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'small-thumbnail', 150, 150, true ); //(cropped)
	}
	add_filter('image_size_names_choose', 'my_image_sizes');
	function my_image_sizes($sizes) {
	$addsizes = array(
	"small-thumbnail" => __( "Small Thumbnail")
	);
	$newsizes = array_merge($sizes, $addsizes);
	return $newsizes;
	}
	

	/**
	 * Enable support for Post Formats
	*/
	/*
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	*/
	add_theme_support( 'custom-background', apply_filters( '_tk_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on _tk, use a find and replace
	 * to change '_tk' to the name of your theme in all the template files
	*/
	load_theme_textdomain( '_tk', get_template_directory() . '/languages' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/
	register_nav_menus( array(
		'primary'  => __( 'Header bottom menu', '_tk' ),
	) );
	
	
	register_nav_menus( array(
		'footnary'  => __( 'Footer menu', '_tk' ),
	) );


}
endif; // _tk_setup
add_action( 'after_setup_theme', '_tk_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function _tk_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_tk' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Sidebar Footer Quer Col 4', '_tk' ),
		'id'            => 'sidebar-footer',
		'class' 		=> 'sidebar-quer',
		'before_widget' => '<aside id="%1$s" class="col-md-3 widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	

}

add_action( 'widgets_init', '_tk_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function _tk_scripts() {

	// Import the necessary TK Bootstrap WP CSS additions
	wp_enqueue_style( '_tk-bootstrap-wp', get_template_directory_uri() . '/includes/css/bootstrap-wp.css' );

	// load bootstrap css
	wp_enqueue_style( '_tk-bootstrap', get_template_directory_uri() . '/includes/resources/bootstrap/css/bootstrap.min.css' );

	// load Font Awesome css
	wp_enqueue_style( '_tk-font-awesome', get_template_directory_uri() . '/includes/css/font-awesome.min.css', false, '4.1.0' );

	// load _tk styles
	wp_enqueue_style( '_tk-style', get_stylesheet_uri() );

	// load bootstrap js
	wp_enqueue_script('_tk-bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.min.js', array('jquery') );

	// load bootstrap wp js
	wp_enqueue_script( '_tk-bootstrapwp', get_template_directory_uri() . '/includes/js/bootstrap-wp.js', array('jquery') );

	wp_enqueue_script( '_tk-skip-link-focus-fix', get_template_directory_uri() . '/includes/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( '_tk-keyboard-image-navigation', get_template_directory_uri() . '/includes/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

}
add_action( 'wp_enqueue_scripts', '_tk_scripts', 999 );

/**
 * Implement the Custom Header feature.
 */
 /*
require get_template_directory() . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */

require get_template_directory() . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/extras.php';

/**
 * Customizer additions.
 */
 /*
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/includes/bootstrap-wp-navwalker.php';

/**
 * Adds WooCommerce support
 */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
/**
 ********************************************
//// THEME SETTING FINISHED ////////////////////
  *******************************************
 */
 
// https://christianvarga.com/how-to-get-submenu-items-from-a-wordpress-menu-based-on-parent-or-sibling/
// add hook

add_filter( 'wp_nav_menu_objects', 'my_wp_nav_menu_objects_sub_menu', 10, 2 );
// filter_hook function to react on sub_menu flag
function my_wp_nav_menu_objects_sub_menu( $sorted_menu_items, $args ) {
  if ( isset( $args->sub_menu ) ) {
    $root_id = 0;
    
    // find the current menu item
    foreach ( $sorted_menu_items as $menu_item ) {
      if ( $menu_item->current ) {
        // set the root id based on whether the current menu item has a parent or not
        $root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
        break;
      }
    }
    
    // find the top level parent
    if ( ! isset( $args->direct_parent ) ) {
      $prev_root_id = $root_id;
      while ( $prev_root_id != 0 ) {
        foreach ( $sorted_menu_items as $menu_item ) {
          if ( $menu_item->ID == $prev_root_id ) {
            $prev_root_id = $menu_item->menu_item_parent;
            // don't set the root_id to 0 if we've reached the top of the menu
            if ( $prev_root_id != 0 ) $root_id = $menu_item->menu_item_parent;
            break;
          } 
        }
      }
    }
    $menu_item_parents = array();
    foreach ( $sorted_menu_items as $key => $item ) {
      // init menu_item_parents
      if ( $item->ID == $root_id ) $menu_item_parents[] = $item->ID;
      if ( in_array( $item->menu_item_parent, $menu_item_parents ) ) {
        // part of sub-tree: keep!
        $menu_item_parents[] = $item->ID;
      } else if ( ! ( isset( $args->show_parent ) && in_array( $item->ID, $menu_item_parents ) ) ) {
        // not part of sub-tree: away with it!
        unset( $sorted_menu_items[$key] );
      }
    }
    
    return $sorted_menu_items;
  } else {
    return $sorted_menu_items;
  }
}

 
/*
* Remove unnecessary meta tags from WordPress header
*/
	  remove_action( 'wp_head', 'wp_generator' ) ;
      remove_action( 'wp_head', 'wlwmanifest_link' ) ;
      remove_action( 'wp_head', 'rsd_link' ) ;

 /**
 ******************************************************************************************
 * Disable WordPress Login Hints
  ******************************************************************************************
 */

function no_wordpress_errors(){
  return 'Versuchen Sie es noch einmal';
}
add_filter( 'login_errors', 'no_wordpress_errors' );

/**
 ******************************************************************************************
* OWN SHORTCODES
* http://wordpress.stackexchange.com/questions/103276/how-to-add-custom-css-buttons-to-wordpress-as-a-shortcode
* https://www.sitepoint.com/wordpress-nested-shortcodes/
*****************************************************************************************
*/
	  
function myOnepagerBG($atts, $content = ''){    

    extract(shortcode_atts(array(
		'class' => ''
    ), $atts)); 

    $html = '<div class="' . $class . '" >' . do_shortcode($content) . '</div>';
    return $html;    
}

add_shortcode('myonepagerbg', 'myOnepagerBG');

 /**
******************************************************************************************
* Custom Breadcrump
******************************************************************************************
 */
 
 function custom_breadcrumb() {
  if(!is_home()) {
    echo '<ol class="breadcrumb">';
    echo '<li><a href="'.get_option('home').'">Startseite</a></li>';
    if (is_single()) {
      echo '<li>';
      the_category(', ');
      echo '</li>';
      if (is_single()) {
        echo '<li>';
        the_title();
        echo '</li>';
      }
    } elseif (is_category()) {
      echo '<li>';
      single_cat_title();
      echo '</li>';
    } elseif (is_page() && (!is_front_page())) {
      echo '<li>';
      the_title();
      echo '</li>';
    } elseif (is_tag()) {
      echo '<li>Tag: ';
      single_tag_title();
      echo '</li>';
    } elseif (is_day()) {
      echo'<li>Archiv für ';
      the_time('F jS, Y');
      echo'</li>';
    } elseif (is_month()) {
      echo'<li>Archiv für ';
      the_time('F, Y');
      echo'</li>';
    } elseif (is_year()) {
      echo'<li>Archiv für ';
      the_time('Y');
      echo'</li>';
    } elseif (is_author()) {
      echo'<li>Author Beiträge';
      echo'</li>';
    } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
      echo '<li>Blog Archiv';
      echo'</li>';
    } elseif (is_search()) {
      echo'<li>Ergebnisse der Suche';
      echo'</li>';
    }
    echo '</ol>';
  }
}

/**
 ******************************************************************************************
 * Back to top http://cotswoldphoto.co.uk/bootstrap-float-to-top-button/
  ******************************************************************************************
 */
/*
function bs_back_to_top() {
  wp_register_script( 'bs-back-to-top', get_stylesheet_directory_uri() . '/includes/js/bs-back-to-top.js', array('jquery'), '1.0.0', true );
  wp_enqueue_script( 'bs-back-to-top' );
}
add_action( 'wp_enqueue_scripts', 'bs_back_to_top', 99 );
 */
/**
 ******************************************************************************************
 * Activate own style-login.css for LOGIN FORM
 * https://codex.wordpress.org/Customizing_the_Login_Form
  ******************************************************************************************
 */

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/style-login.css' );
}

add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );



/**
 ******************************************************************************************
 * ADDITIONAL LOGIN FORM HOOKS: https://css-tricks.com/snippets/wordpress/customize-login-page/
 ******************************************************************************************
 */

 
function change_wp_login_title() {
	return get_option('blogname');
}
add_filter('login_headertitle', 'change_wp_login_title');
 
function new_wp_login_url() {
	return home_url();
}
add_filter('login_headerurl', 'new_wp_login_url');

/**
 ******************************************************************************************
 * Remove menu items from WordPress admin bar
 * http://www.catswhocode.com/blog/wordpress-dashboard-hacks-for-developers-and-freelancers
 ******************************************************************************************
 */

function wps_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
    $wp_admin_bar->remove_menu('view-site');
}
add_action( 'wp_before_admin_bar_render', 'wps_admin_bar' );

/**
 ******************************************************************************************
 *Videos in WordPress Responsive einbetten http://wphafen.com/wordpress-youtube-videos-responsive-einbetten/
 ******************************************************************************************
 */
add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);
function my_embed_oembed_html($html, $url, $attr, $post_id) {
	return '<div class="video-container">' . $html . '</div>';
}

/**
 ******************************************************************************************
 * ADDS LINK AFTER POST EXCERPT
 ***************
 ***************************************************************************
 **/
function new_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more', 21 );

function the_excerpt_more_link( $excerpt ){
    $post = get_post();
    $excerpt .= '<a href="'. get_permalink($post->ID) . '">Weiterlesen</a>';
    return $excerpt;
}
add_filter( 'the_excerpt', 'the_excerpt_more_link', 21 );

/**
 ******************************************************************************************
 * Remove <p> tag around img & iframe http://wordpress.stackexchange.com/questions/136840/how-to-remove-p-tags-around-img-and-iframe-tags-in-the-acf-wysiwyg-field
 ******************************************************************************************
 */

function filter_ptags_on_images($content)
{
    $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
}
add_filter('the_content', 'filter_ptags_on_images');

 
/**
 ******************************************************************************************
 * execute PHP scripts in text widget of wordpress by default
  ******************************************************************************************
 */

function php_execute($html){
if(strpos($html,"<"."?php")!==false){ ob_start(); eval("?".">".$html);
$html=ob_get_contents();
ob_end_clean();
}
return $html;
}
add_filter('widget_text','php_execute',100);



/**
 ******************************************************************************************
 * Disable admin bar on the frontend of your website
 * for subscribers.
 ******************************************************************************************
 */
 
function themeblvd_disable_admin_bar() { 
	if( ! current_user_can('edit_posts') )
		add_filter('show_admin_bar', '__return_false');	
}
add_action( 'after_setup_theme', 'themeblvd_disable_admin_bar' );

/**
 ******************************************************************************************
 * ADD ONW STANDARD AVATAR
 * http://crunchify.com/how-to-change-default-avatar-in-wordpress/
 ******************************************************************************************
 */
/*
add_filter( 'avatar_defaults', 'ourowngravatar' );
 
function ourowngravatar ($avatar_defaults) {
$myavatar = get_bloginfo('template_directory') . '/img/.png';
$avatar_defaults[$myavatar] = "CoHa Avatar";
return $avatar_defaults;
}

/**
 ******************************************************************************************
EMAIL ENCODE SHORTCODE: http://bavotasan.com/2012/shortcode-to-encode-email-in-wordpress-posts/
how to use:
in php  <?php echo antispambot( 'john.doe@mysite.com' ); ?> 
in editor as shortcode [email][/email]
******************************************************************************************
*/

function email_encode_function( $atts, $content ){
	return '<a href="'.antispambot("mailto:".$content).'">'.antispambot($content).'</a>';
}
add_shortcode( 'email', 'email_encode_function' );



/*
  Add Custom CSS File (coha-custom.css)
*/
// Load the theme stylesheets
function theme_styles()  
{ 
  /* 
    Tisa Pro Font 
    Lato Font
  */
  wp_enqueue_style(
    'coha-fonts',
    'https://use.typekit.net/cvy3vwb.css'
  );
  

	// Load all of the styles that need to appear on all pages
	wp_enqueue_style(
    'coha-custom',
    get_template_directory_uri().'/includes/css/coha-custom.css'
  );

}
add_action('wp_enqueue_scripts', 'theme_styles', 5000);

/*
  Add Custom JS File (coha-custom.js)
*/
function my_custom_script_load(){
  wp_enqueue_script(
    'coha-custom',
    get_template_directory_uri() . '/includes/js/coha-custom.js'
  );
}
add_action( 'wp_enqueue_scripts', 'my_custom_script_load');


/*
  Overwrite jQuery Version 
*/
function replace_core_jquery_version() {
  wp_deregister_script( 'jquery' );

  wp_register_script(
    'jquery',
    get_template_directory_uri().'/includes/js/jquery/jquery-2.2.4.min.js',
    [],
    '3.3.1' );
}
add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );
