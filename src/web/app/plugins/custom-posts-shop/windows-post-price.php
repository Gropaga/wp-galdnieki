<?php


add_action( 'add_meta_boxes', 'add_window_meta' );

/* Saving the data */
add_action( 'save_post', 'window_meta_save' );

/* Adding the main meta box container to the post editor screen */
function add_window_meta() {
    add_meta_box(
        'window-price',
        __('Price'),
        'window_price_init',
        'window'); // post type
}

/*Printing the box content */
function window_price_init() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'window_price_nonce' );
    ?>
    <div id="window_meta_item">
    <?php

    //Obtaining the linked windowprice meta values
    $windowPrice = get_post_meta($post->ID,'windowPrice',true);

    $count = 0;
    if (is_array($windowPrice) && count($windowPrice) > 0) {
        foreach( $windowPrice as $windowDetail ) {
            printf(
                '<p>' .
                __('Height') . ': <input type="text" name="windowPrice[%1$s][height]" value="%2$s" /> ' .
                __('Width') . ': <input type="text" name="windowPrice[%1$s][width]" value="%3$s" /> ' .
                __('Material') . ': <select name="windowPrice[%1$s][material]">
                    <option value="Ash" ' . ($windowDetail['material'] == 'Ash' ? 'selected' : '') . '>'.__('Ash').'</option>
                    <option value="Oak" ' . ($windowDetail['material'] == 'Oak' ? 'selected' : '') . '>'.__('Oak').'</option>
                    <option value="Birch" ' . ($windowDetail['material'] == 'Birch' ? 'selected' : '') . '>'.__('Birch').'</option>
                    <option value="Nut" ' . ($windowDetail['material'] == 'Nut' ? 'selected' : '') . '>'.__('Nut').'</option>
                </select> ' .
                __('Price') . ': <input type="text" name="windowPrice[%1$s][price]" value="%4$s" /> EUR ' .
                '<a class="remove-window-price">'.__('Remove').'</a>' .
                '</p>',
                $count, $windowDetail['height'], $windowDetail['width'], $windowDetail['price']
            );

            $count++;
        }
    }

    ?>
    <span id="window-package"></span>
    <a href="#" class="add_window"><?php _e('Add Window Price'); ?></a>
    <script>
        var $ =jQuery.noConflict();
        $(document).ready(function() {
            var count = <?php echo (is_array($windowPrice) ? sizeof($windowPrice) : 0) ?>;
            $(".add_window").click(function() {
                count = count + 1;

                $('#window-package').append(
                    '<p>' +
                    '<?= __('Height') ?>: <input type="text" name="windowPrice['+count+'][height]" /> ' +
                    '<?= __('Width') ?>: <input type="text" name="windowPrice['+count+'][width]" /> ' +
                    '<?= __('Material') ?>: <select type="text" name="windowPrice['+count+'][material]">' +
                    '<option value="Ash"><?= __('Ash') ?></option>' +
                    '<option value="Oak"><?= __('Oak') ?></option>' +
                    '<option value="Birch"><?= __('Birch') ?></option>' +
                    '<option value="Nut"><?= __('Nut') ?></option>' +
                    '</select> ' +
                    '<?= __('Price') ?>: <input type="text" name="windowPrice['+count+'][price]" /> EUR ' +
                    '<a class="remove-window-price"><?php echo "Remove"; ?></a>' +
                    '</p>');
                return false;
            });
            $(document.body).on('click','.remove-window-price',function() {
                $(this).parent().remove();
            });
        });
    </script>
    </div><?php
}

/* Save function for the entered data */
function window_meta_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verifying the nonce
    if ( !isset( $_POST['window_price_nonce'] ) ) {
        return;
    }

    if ( !wp_verify_nonce( $_POST['window_price_nonce'], plugin_basename( __FILE__ ) ) ) {
        return;
    }
    $windowPrice = [];
    if (isset($_POST['windowPrice'])) {
        $windowPrice = $_POST['windowPrice'];
    }

    // normalize array keys 0, 1, 2, 3, 4... instead of 1, 4, 5, 6...
    $normalizedWindowPrice = [];
    foreach ($windowPrice as $d) {
        $normalizedWindowPrice[] = $d;
    }

    // Updating the windowPrice meta data for all translations
    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            update_post_meta(pll_get_post($post_id, $lang),'windowPrice',$normalizedWindowPrice);
        }
    } else {
        update_post_meta($post_id,'windowPrice',$normalizedWindowPrice);
    }
}

?>