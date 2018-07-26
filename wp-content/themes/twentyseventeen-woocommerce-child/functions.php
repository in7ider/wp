<?php


function twentyseventeenwoocommerce_enqueue_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'twentyseventeenwoocommerce_enqueue_styles' ); 


function load_admin_style() {
  wp_register_style( 'admin_css', get_stylesheet_directory_uri() . '/admin-style.css', false, '1.0.0' );
 }
 add_action( 'admin_enqueue_scripts', 'load_admin_style' );

//movie CPT 

function create_posttype() {
 
    register_post_type( 'movies',
    // CPT Options
        array(
            'labels' => array(
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
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'movies'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

//Create metabox fields

function movie_info_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <input name="subtitle" style="width:100%" id="subtitle" placeholder="Enter subtitle here" type="text" value="<?php echo get_post_meta($object->ID, "subtitle", true); ?>">
            <input name="price" id="price" placeholder="Enter price here" type="number" value="<?php echo get_post_meta($object->ID, "price", true); ?>">
        </div>
    <?php  
}

//Add metabox

function add_movie_meta_box()
{
    add_meta_box("movie-info", "Movie info", "movie_info_markup", "movies", "normal", "high", null);
}

add_action("add_meta_boxes", "add_movie_meta_box");

//Save metabox

function save_movie_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "movies";
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

add_action("save_post", "save_movie_meta_box", 10, 3);

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

//Registration redirect
add_action('woocommerce_registration_redirect', 'custom_registration_redirect', 2);
function custom_registration_redirect($redirect) {
    $redirect = site_url() . '/favourites/';
    return $redirect;
}

//Registration skype field

function woocommerce_skype_register_fields() { ?>
       <p class="form-row form-row-wide">
       <label for="skype"><?php _e( 'Skype', 'woocommerce' ); ?></label>
       <input type="text" class="input-text" name="skype" id="skype" value="<?php if ( ! empty( $_POST['skype'] ) ) esc_attr_e( $_POST['skype'] ); ?>" />
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

//Connect CPT with WC

class WCCPT_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {

    /**
     * Method to read a product from the database.
     * @param WC_Product
     */

    public function read( &$product ) {

        $product->set_defaults();

        if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) || ! in_array( $post_object->post_type, array( 'movies', 'product' ) ) ) { 
            throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
        }

        $id = $product->get_id();

        $product->set_props( array(
            'name'              => $post_object->post_title,
            'slug'              => $post_object->post_name,
            'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
            'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
            'status'            => $post_object->post_status,
            'description'       => $post_object->post_content,
            'short_description' => $post_object->post_excerpt,
            'parent_id'         => $post_object->post_parent,
            'menu_order'        => $post_object->menu_order,
            'reviews_allowed'   => 'open' === $post_object->comment_status,
        ) );

        $this->read_attributes( $product );
        $this->read_downloads( $product );
        $this->read_visibility( $product );
        $this->read_product_data( $product );
        $this->read_extra_data( $product );
        $product->set_object_read( true );
    }

    /**
     * Get the product type based on product ID.
     *
     * @since 3.0.0
     * @param int $product_id
     * @return bool|string
     */
    public function get_product_type( $product_id ) {
        $post_type = get_post_type( $product_id );
        if ( 'product_variation' === $post_type ) {
            return 'variation';
        } elseif ( in_array( $post_type, array( 'movies', 'product' ) ) ) {
            $terms = get_the_terms( $product_id, 'product_type' );
            return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
        } else {
            return false;
        }
    }
}

add_filter( 'woocommerce_data_stores', 'woocommerce_data_stores' );

function woocommerce_data_stores ( $stores ) {      
    $stores['product'] = 'WCCPT_Product_Data_Store_CPT';
    return $stores;
}

add_filter('the_content','rei_add_to_cart_button', 20,1);
function rei_add_to_cart_button($content){
	global $post;
	if ($post->post_type !== 'movies') {
		return $content; 
	} else {
?>
	<form action="" method="post">
		<input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
		<input name="quantity" type="number" value="1" min="1" style="width: 10%; height: 35px; float: left;" />
		<input name="submit" type="submit" value="Add to cart" />
	</form>
<?php	
	return $content . ob_get_clean();
	}
}

//connect price



add_filter('woocommerce_product_get_price', 'get_ws_price', 10, 2);

function get_ws_price($price, $product){

    $id = $product->get_id();
    $post_type = get_post_type( $id );
	if ('movies' !== $post_type ){
        return; 
    } else {
	    $price = get_post_meta($id, "price", true);
        return $price;
    }

}