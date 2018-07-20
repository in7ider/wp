<?php


function twentyseventeenwoocommerce_enqueue_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'twentyseventeenwoocommerce_enqueue_styles' ); 


function load_admin_style() {
  wp_register_style( 'admin_css', get_stylesheet_directory_uri() . '/admin-style.css', false, '1.0.0' );
 }
 add_action( 'admin_enqueue_scripts', 'load_admin_style' );

//product CPT Rename

add_filter( 'woocommerce_register_post_type_product', 'custom_post_type_label_woo' );

function custom_post_type_label_woo( $args ){
    $labels = array(
        'name'               => __( 'Movies', 'your-custom-plugin' ),
        'singular_name'      => __( 'Movie', 'your-custom-plugin' ),
        'menu_name'          => _x( 'Movies', 'Admin menu name', 'your-custom-plugin' ),
        'add_new'            => __( 'Add Movie', 'your-custom-plugin' ),
        'add_new_item'       => __( 'Add New Tour', 'your-custom-plugin' ),
        'edit'               => __( 'Edit Movie', 'your-custom-plugin' ),
        'edit_item'          => __( 'Edit Movie', 'your-custom-plugin' ),
        'new_item'           => __( 'New Movie', 'your-custom-plugin' ),
        'view'               => __( 'View Movie', 'your-custom-plugin' ),
        'view_item'          => __( 'View Movie', 'your-custom-plugin' ),
        'search_items'       => __( 'Search Movies', 'your-custom-plugin' ),
        'not_found'          => __( 'No Movies found', 'your-custom-plugin' ),
        'not_found_in_trash' => __( 'No Movies found in trash', 'your-custom-plugin' ),
        'parent'             => __( 'Parent Movie', 'your-custom-plugin' )
    );

    $args['labels'] = $labels;
    return $args;
}


//Create metabox fields

function movie_info_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <input name="subtitle" style="width:100%" id="subtitle" placeholder="Enter subtitle here" type="text" value="<?php echo get_post_meta($object->ID, "subtitle", true); ?>">
        </div>
    <?php  
}

//Add metabox

function add_movie_meta_box()
{
    add_meta_box("movie-info", "Movie info", "movie_info_markup", "product", "normal", "high", null);
}

add_action("add_meta_boxes", "add_movie_meta_box");

//Save metabox

function save_product_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "product";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";

    if(isset($_POST["subtitle"]))
    {
        $meta_box_text_value = $_POST["subtitle"];
    }   
    update_post_meta($post_id, "subtitle", $meta_box_text_value);

    if(isset($_POST["price"]))
    {
        $meta_box_text_value = $_POST["price"];
    }   
    update_post_meta($post_id, "price", $meta_box_text_value);
    
}

add_action("save_post", "save_product_meta_box", 10, 3);


//Add checkout redirect
function checkout_redirect_checkout_add_cart( $url ) {
    $url = get_permalink( get_option( 'woocommerce_checkout_page_id' ) ); 
    return $url;
}
 
add_filter( 'woocommerce_add_to_cart_redirect', 'checkout_redirect_checkout_add_cart' );

//Login redirect

add_filter('woocommerce_login_redirect', 'custom_wc_login_redirect', 10, 3);
  function custom_wc_login_redirect( $redirect, $user ) {
  $redirect = site_url() . '/favourites/';
  return $redirect;
}

//Registration skype field

function woocommerce_skype_register_fields() { ?>
       <p class="form-row form-row-wide">
       <label for="skype"><?php _e( 'Skype', 'woocommerce' ); ?></label>
       <input type="text" class="input-text" name="skype" id="skype" value="<?php esc_attr_e( $_POST['skype'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'woocommerce_skype_register_fields' );

//Add to DB

 function woocommerce_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['skype'] ) ) {
        update_user_meta( $customer_id, 'skype', sanitize_text_field( $_POST['skype'] ) );
    }
 
}
add_action( 'woocommerce_created_customer', 'woocommerce_save_extra_register_fields' );