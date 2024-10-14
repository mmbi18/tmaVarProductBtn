<?php
/*
Plugin Name: tma Variable Product Button Selector
Description: Converts variable product dropdowns to buttons with image backgrounds if set.
Version: 1.0
Author: mohammad bagheri
*/

// Prevent WooCommerce from changing the product image on variation select
function prevent_image_change_on_variation_select() {
    ?>
    <style>
    .single-product .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image img {
        transition: none !important;
    }
    </style>
    <script>
    jQuery(($) => {
    $.fn.wc_variations_image_update = () => {
      //Do nothing
    }
});

    </script>
    <?php
}
add_action('wp_head', 'prevent_image_change_on_variation_select');
// Enqueue custom scripts and styles
function vpbs_enqueue_scripts() {
    wp_enqueue_style('vpbs-style', plugin_dir_url(__FILE__) . 'style.css?1101');
    wp_enqueue_script('vpbs-script', plugin_dir_url(__FILE__) . 'script.js?11024', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'vpbs_enqueue_scripts');

// Add buttons for variable products
function vpbs_add_variation_buttons() {
    global $product;
    if (!is_product() || empty($product) || !is_a($product, 'WC_Product_Variable')) {
        return;
    }

    $variations = $product->get_available_variations();
    ?>
    <script>
    jQuery(document).ready(function($) {
        var variations = <?php echo json_encode($variations); ?>;
        $('.variations_form').each(function() {
            $(this).find('.variations select').each(function() {
                var select = $(this);
                var options = '';
                variations.forEach(function(variation) {
                    var value = variation.attributes[select.attr('name')];
                    var image = variation.image.thumb_src;
                    if (image) {
                        options += '<button type="button" class="vpbs-option" data-value="'+value+'" style="background-image: url('+image+');"></button>';
                    } else {
                        options += '<button type="button" class="vpbs-option" data-value="'+value+'">'+value+'</button>';
                    }
                });
                if (!select.next('.vpbs-options').length) {
                    select.hide().after('<div class="vpbs-options">'+options+'</div>');
                }
            });
        });

        // Prevent main product image change
        var originalImage = $('.woocommerce-product-gallery__wrapper .wp-post-image').attr('src');
        $('.vpbs-option').on('click', function() {
            var button = $(this);
            var select = button.closest('.variations').find('select');
            var value = button.data('value');
            select.val(value).trigger('change');
            button.siblings().removeClass('selected');
            button.addClass('selected');
            
            // Restore original product image
            setTimeout(function() {
                $('.woocommerce-product-gallery__wrapper .wp-post-image').attr('src', originalImage);
            }, 100);
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'vpbs_add_variation_buttons');
?>
