<?php


add_action( 'add_meta_boxes', 'add_door_meta' );

/* Saving the data */
add_action( 'save_post', 'door_meta_save' );

/* Adding the main meta box container to the post editor screen */
function add_door_meta() {
    add_meta_box(
        'door-price',
        __('Price'),
        'door_price_init',
        'door'); // post type
}

/*Printing the box content */
function door_price_init() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'door_price_nonce' );
    ?>
    <div id="door_meta_item">
    <?php

    //Obtaining the linked doorprice meta values
    $doorPrice = get_post_meta($post->ID,'doorPrice',true);

    $count = 0;
    if (is_array($doorPrice) && count($doorPrice) > 0) {

        var_dump($doorPrice);

        foreach( $doorPrice as $doorDetail ) {
            printf(
                '<p>' .
                __('Height') . ': <input type="text" name="doorPrice[%1$s][height]" value="%2$s" /> ' .
                __('Width') . ': <input type="text" name="doorPrice[%1$s][width]" value="%3$s" /> ' .
                __('Material') . ': <select name="doorPrice[%1$s][material]">
                    <option value="Ash" ' . ($doorDetail['material'] == 'Ash' ? 'selected' : '') . '>'.__('Ash').'</option>
                    <option value="Oak" ' . ($doorDetail['material'] == 'Oak' ? 'selected' : '') . '>'.__('Oak').'</option>
                    <option value="Birch" ' . ($doorDetail['material'] == 'Birch' ? 'selected' : '') . '>'.__('Birch').'</option>
                    <option value="Nut" ' . ($doorDetail['material'] == 'Nut' ? 'selected' : '') . '>'.__('Nut').'</option>
                </select> ' .
                __('Price') . ': <input type="text" name="doorPrice[%1$s][price]" value="%4$s" /> EUR ' .
                '<a class="remove-door-price">'.__('Remove').'</a>' .
                '</p>',
                $count, $doorDetail['height'], $doorDetail['width'], $doorDetail['price']
            );

            $count++;
        }
    }

    ?>
    <span id="door-package"></span>
    <a href="#" class="add_door"><?php _e('Add Door Price'); ?></a>
    <script>
        var $ =jQuery.noConflict();
        $(document).ready(function() {
            var count = <?php echo (is_array($doorPrice) ? sizeof($doorPrice) : 0) ?>;
            $(".add_door").click(function() {
                count = count + 1;

                $('#door-package').append(
                    '<p>' +
                    '<?= __('Height') ?>: <input type="text" name="doorPrice['+count+'][height]" /> ' +
                    '<?= __('Width') ?>: <input type="text" name="doorPrice['+count+'][width]" /> ' +
                    '<?= __('Material') ?>: <select type="text" name="doorPrice['+count+'][material]">' +
                    '<option value="Ash"><?= __('Ash') ?></option>' +
                    '<option value="Oak"><?= __('Oak') ?></option>' +
                    '<option value="Birch"><?= __('Birch') ?></option>' +
                    '<option value="Nut"><?= __('Nut') ?></option>' +
                    '</select> ' +
                    '<?= __('Price') ?>: <input type="text" name="doorPrice['+count+'][price]" /> EUR ' +
                    '<a class="remove-door-price"><?php echo "Remove"; ?></a>' +
                    '</p>');
                return false;
            });
            $(document.body).on('click','.remove-door-price',function() {
                $(this).parent().remove();
            });
        });
    </script>
    </div><?php
}

/* Save function for the entered data */
function door_meta_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verifying the nonce
    if ( !isset( $_POST['door_price_nonce'] ) ) {
        return;
    }

    if ( !wp_verify_nonce( $_POST['door_price_nonce'], plugin_basename( __FILE__ ) ) ) {
        return;
    }
    $doorPrice = [];
    if (isset($_POST['doorPrice'])) {
        $doorPrice = $_POST['doorPrice'];
    }

    // normalize array keys 0, 1, 2, 3, 4... instead of 1, 4, 5, 6...
    $normalizedDoorPrice = [];
    foreach ($doorPrice as $d) {
        $normalizedDoorPrice[] = $d;
    }

    // Updating the doorPrice meta data for all translations
    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            update_post_meta(pll_get_post($post_id, $lang),'doorPrice',$normalizedDoorPrice);
        }
    } else {
        update_post_meta($post_id,'doorPrice',$normalizedDoorPrice);
    }
}

?>