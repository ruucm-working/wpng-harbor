<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>
<div class="images">
    <?php
        $attachment_ids = $product->get_gallery_attachment_ids();
        
        if($attachment_ids){
            $attributes = om_get_attributes_string(array(
                'image_size'      => apply_filters('single_product_large_thumbnail_size', 'shop_single'),
                'on_click'        => get_option('woocommerce_enable_lightbox') == 'yes' ? 'lightbox' : 'nothing',
                'show_pagination' => 'true',
                'show_arrows'     => 'true',
                'height'          => 'auto',
                'images'          => implode(',', apply_filters('om_wc_single_product_ids', $attachment_ids, $product)),
                'enable_autoplay' => 'false',
                'enable_rewind'   => 'false'
            ));
    
            echo do_shortcode("[om_carousel$attributes]");
        } else {
            echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $post->ID);
        }
        
        //do_action( 'woocommerce_product_thumbnails' );
    ?>
</div>
