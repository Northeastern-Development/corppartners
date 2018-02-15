<?php
/**
 * Author: Todd Motto | @toddmotto
 * URL: neudev.com | @neudev
 * Custom functions, support, custom post types and more.
 */

require_once "modules/is-debug.php";

/*------------------------------------*\
    External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
    Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
    'default-color' => 'FFF',
    'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
    'default-image'          => get_template_directory_uri() . '/img/headers/default.jpg',
    'header-text'            => false,
    'default-text-color'     => '000',
    'width'                  => 1000,
    'height'                 => 198,
    'random-default'         => false,
    'wp-head-callback'       => $wphead_cb,
    'admin-head-callback'    => $adminhead_cb,
    'admin-preview-callback' => $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Enable neudev support
    add_theme_support('neudev', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

    // Localisation Support
    load_theme_textdomain('neudev', get_template_directory() . '/languages');
}

/*------------------------------------*\
    Functions
\*------------------------------------*/

// neudev navigation
function neudev_nav()
{
    wp_nav_menu(
    array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => new neu_walker_nav_menu()
        )
    );
}

// neudev navigation
function neudev_footer_nav()
{
    wp_nav_menu(
    array(
        'theme_location'  => 'footer-menu',
        'menu'            => '',
        'container'       => 'false',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
}

// Extend Walker Menu
class neu_walker_nav_menu extends Walker_Nav_Menu {

// add classes to ul sub-menus
function start_lvl(&$output, $depth = 0, $args = Array ()) {
      $indent = str_repeat("\t", $depth);
      //$output .= "\n$indent<ul class=\"sub-menu\">\n";

      // Change sub-menu to dropdown menu
      $output .= "\n$indent<ul class=\"neu__sub-menu hidden\">\n";
  }

  function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    // Most of this code is copied from original Walker_Nav_Menu
    global $wp_query, $wpdb;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $class_names = $value = '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;

    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = ' class="' . esc_attr( $class_names ) . '"';

    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';



    $output .= $indent . '<li' . $id . $value . $class_names .'>';

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';



	//Check if menu item is in main menu and add the active class to current page li a item
    if ( $depth == 0 && in_array('current-menu-item', $classes)) {
        // These lines adds your custom class and attribute
        $attributes .= ' class="neu__active"';

    }

    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

    // Add the caret if menu level is 0
    //if ( $depth == 0 && $has_children > 0  ) {
        //$item_output .= ' <b class="caret"></b>';
   // }

    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }

}



function disable_embeds_init() {

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}

add_action('init', 'disable_embeds_init', 9999);

// include custom jQuery
function neudev_include_custom_jquery() {

	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri() . '/_scripts/js/jquery.js', array(), true, '3.2.1', false);

}
add_action('wp_enqueue_scripts', 'neudev_include_custom_jquery');







// Load neudev Scripts
function nudev_footer_scripts() {

  wp_register_script('nudevscripts', get_template_directory_uri() . '/_scripts/js/scripts-min.js', array('jquery'), '1.0.0'); // Custom scripts
  wp_enqueue_script('nudevscripts'); // Enqueue it!

}
add_action('wp_footer', 'nudev_footer_scripts');




// Load neudev styles
function neudev_styles(){

  wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Lato:300,400,700,900', false );

  wp_register_style('fontawesome', get_template_directory_uri() . '/_css/plugins/font-awesome.min.css', array(), '4.7', 'all');
  wp_enqueue_style('fontawesome'); // Enqueue it!

  wp_register_style('nudevmainstyle', get_template_directory_uri() . '/_css/style.css', array(), '1.0', 'all');
  wp_enqueue_style('nudevmainstyle'); // Enqueue it!
}

// Register neudev Navigation
function register_neudev_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'neudev'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'neudev'), // Sidebar Navigation
        'footer-menu' => __('Footer Menu', 'neudev') // Footer Navigation
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove the width and height attributes from inserted images
function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}


// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'neudev'),
        'description' => __('Description for this widget-area...', 'neudev'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'neudev'),
        'description' => __('Description for this widget-area...', 'neudev'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;

    if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
        remove_action('wp_head', array(
            $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
            'recent_comments_style'
        ));
    }
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function neudev_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function neudev_index($length) // Create 20 Word Callback for Index page Excerpts, call using neudev_excerpt('neudev_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using neudev_excerpt('neudev_custom_post');
function neudev_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function neudev_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function neudev_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'neudev') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function neudev_style_remove($tag){
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

function _remove_script_version( $src ){
	return remove_query_arg( 'ver',  $src  );
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function neudevgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');

// Remove comments from top admin bar
function my_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'my_admin_bar_render' );

function wpfme_has_sidebar($classes) {
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}
add_filter('body_class','wpfme_has_sidebar');

// Custom Comments Callback
function nudevcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------*\
    Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'neudev_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'neudev_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'neudev_styles'); // Add Theme Stylesheet
add_action('init', 'register_neudev_menu'); // Add neudev Menu
//add_action('init', 'create_post_type_neudev'); // Add our neudev Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'neudev_pagination'); // Add our neudev Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );//Removing Emoji code from header
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );//Removing Emoji code from header
remove_action( 'wp_print_styles', 'print_emoji_styles' );//Removing Emoji code from header
remove_action( 'admin_print_styles', 'print_emoji_styles' );//Removing Emoji code from header
remove_action('welcome_panel', 'wp_welcome_panel'); // Removes Your Dashboard’s ‘Welcome Panel’

// Add Filters
add_filter('avatar_defaults', 'neudevgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
//add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'neudev_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'neudev_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 ); // Removes scripts version number from script tags
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 ); // Removes scripts version number from style tags
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('post_thumbnail_html', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images
add_filter('image_send_to_editor', 'remove_width_attribute', 10 ); // Remove width and height dynamic attributes to post images
add_filter('xmlrpc_enabled', '__return_false'); // Disables XML-RPC in WordPress


// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('neudev_shortcode_demo', 'neudev_shortcode_demo'); // You can place [neudev_shortcode_demo] in Pages, Posts now.
add_shortcode('neudev_shortcode_demo_2', 'neudev_shortcode_demo_2'); // Place [neudev_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [neudev_shortcode_demo] [neudev_shortcode_demo_2] Here's the page title! [/neudev_shortcode_demo_2] [/neudev_shortcode_demo]

/*------------------------------------*\
    Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called neudev-Blank
function create_post_type_neudev()
{
    register_taxonomy_for_object_type('category', 'neudev-blank'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'neudev-blank');
    register_post_type('neudev-blank', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('neudev Custom Post', 'neudev'), // Rename these to suit
            'singular_name' => __('neudev Custom Post', 'neudev'),
            'add_new' => __('Add New', 'neudev'),
            'add_new_item' => __('Add New neudev Custom Post', 'neudev'),
            'edit' => __('Edit', 'neudev'),
            'edit_item' => __('Edit neudev Custom Post', 'neudev'),
            'new_item' => __('New neudev Custom Post', 'neudev'),
            'view' => __('View neudev Custom Post', 'neudev'),
            'view_item' => __('View neudev Custom Post', 'neudev'),
            'search_items' => __('Search neudev Custom Post', 'neudev'),
            'not_found' => __('No neudev Custom Posts found', 'neudev'),
            'not_found_in_trash' => __('No neudev Custom Posts found in Trash', 'neudev')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom neudev post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

//Add colors to the page/post panels within the dashboardcan i test
add_action('admin_footer','posts_status_color');
function posts_status_color(){
?>
<style>
.status-draft .check-column {box-shadow: -12px 0 0 -3px rgba(224, 108, 117, 1.0) !important;}
.status-pending .check-column {box-shadow: -12px 0 0 -3px rgba(209, 154, 102, 1.0) !important;}
.status-publish .check-column {box-shadow: -12px 0 0 -3px rgba(152, 195, 121, 1.0) !important;}
.plugin-update-tr.active td, .plugins .active th.check-column {border-left: 4px solid rgba(152, 195, 121, 1.0) !important;}
.status-future .check-column {box-shadow: -12px 0 0 -3px #ffffff !important;}
.status-private .check-column {box-shadow: -12px 0 0 -3px #000000 !important;}
</style>
<?php
}

/*------------------------------------*\
    ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function neudev_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function neudev_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

//Hide Login Errors in WordPress
function no_wordpress_errors(){
  return 'Something is wrong!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


//Customizing the WordPress Login Page

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {

            background-image: url(<?php echo get_template_directory_uri(); ?>/img/logo.svg) !important;
            width:300px;
            background-size: 400px 84px;
          	height: 84px;
        }
        body.login #login_error, .login .message {
            border-left: 4px solid rgba(204, 0, 0, 1.0) !important;
        }
        body.login #backtoblog a, .login #nav a {
            color:rgba(51, 62, 71, 1.0) !important;
        }
        body.login #backtoblog a:hover, .login #nav a:hover {
            color:rgba(204, 0, 0, 1.0) !important;
        }
         .wp-core-ui .button-primary {
            background:rgba(204, 0, 0, 1.0) !important;
            border-color:rgba(189, 189, 189, 1.0) !important;
            text-shadow: none !important;
            box-shadow: 0 1px 0 #000 !important;
        }
        body.login label {
            color:rgba(51, 62, 71, 1.0) !important;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Government Relations';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );



//DASHBOARD STYLES
function custom_colors() {
   echo '<style type="text/css">
           #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu {
             background:rgba(201, 70, 62, 1.0);
           }
           #adminmenu .awaiting-mod, #adminmenu .update-plugins {
             background-color:rgba(201, 70, 62, 1.0);
           }
         </style>';
}
add_action('admin_head', 'custom_colors');
