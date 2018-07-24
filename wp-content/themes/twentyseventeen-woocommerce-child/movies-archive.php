<?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'movies',
        'paged' => $paged,
    );
    $wp_query = new WP_Query($args);

    if (isset($_GET['all']))
    {
        ?>

        <?php do_action('woocommerce_archive_description'); ?>

        <?php if (have_posts()) : ?>

            <?php
            // I don't want the sorting anymore
            //do_action('woocommerce_before_shop_loop');
            ?>

            <ul class = "products-list">
                <?php while (have_posts()) : the_post(); ?>

                    <?php woocommerce_get_template_part('content', 'product'); ?>

                <?php endwhile; // end of the loop.   ?>
            </ul>

            <?php
            /*  woocommerce pagination  */
            do_action('woocommerce_after_shop_loop');
            ?>

        <?php elseif (!woocommerce_product_subcategories(array('before' => woocommerce_product_loop_start(false), 'after' => woocommerce_product_loop_end(false)))) : ?>

            <?php woocommerce_get_template('loop/no-products-found.php'); ?>

        <?php endif; ?>
        <?php
    }
    else
    {
        // Code to display the product categories with thumbnails.
    }
?>