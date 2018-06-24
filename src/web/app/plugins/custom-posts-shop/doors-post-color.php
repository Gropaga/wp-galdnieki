<?php

add_action( 'add_meta_boxes', 'add_color_meta' );

/* Saving the data */
add_action( 'save_post', 'color_meta_save' );

/* Adding the main meta box container to the post editor screen */
function add_color_meta() {
    add_meta_box(
        'door-color',
        __('Color'),
        'door_color_init',
        'door'); // post type
}

/*Printing the box content */
function door_color_init() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'door_color_nonce' );
    ?>
    <div id="door_meta_item">
    <?php

    //Obtaining the linked doorcolor meta values
    $doorColor = get_post_meta($post->ID,'doorColor',true);
    $count = 0;
    if (is_array($doorColor) && count($doorColor) > 0) {
        foreach( $doorColor as $doorDetail ) {
            printf(
                '<p>' .
                __('Name') . ': <input type="text" name="doorColor[%1$s][name]" value="%2$s" /> ' .
                __('Color') . ': <input type="text" class="door-color-hex" name="doorColor[%1$s][hex]" value="%3$s" /> ' .
                __('Gallery') . ': <input type="hidden" class="door-color-gallery" name="doorColor[%1$s][gallery]" value="%4$s" /> <button class="edit-gallery"> ' . __('Edit Gallery') . '</button>' .
                '<a class="remove-door-color">'.__('Remove').'</a>' .
                '</p>',
                $count, $doorDetail['name'], $doorDetail['hex'], $doorDetail['gallery']
            );

            $count++;
        }
    }

    ?>
    <span id="color-package"></span>
    <a href="#" class="add_color"><?= __('Add Door Color'); ?></a>
    <script>
        var $ =jQuery.noConflict();

        $(document).ready(function() {
            $('.door-color-hex').wpColorPicker();

            var count = <?php echo (is_array($doorColor) ? sizeof($doorColor) : 0) ?>;
            $(".add_color").click(function() {
                count = count + 1;

                $('#color-package').append(
                    '<p>' +
                    '<?= __('Name') ?>: <input type="text" name="doorColor['+count+'][name]" /> ' +
                    '<?= __('Color') ?>: <input type="text" class="door-color-hex" name="doorColor['+count+'][hex]" /> ' +
                    '<?= __('Gallery') ?>: <input type="hidden" class="door-color-gallery" name="doorColor['+count+'][gallery]" /> <button class="edit-gallery"><?= __('Edit Gallery') ?></button>' +
                    '<a class="remove-door-color"><?= __('Remove') ?></a>' +
                    '</p>');
                $( "input[name='doorColor["+count+"][hex]']" ).wpColorPicker();
                return false;
            });
            $(document.body).on('click','.remove-door-color',function() {
                $(this).parent().remove();
            });
            $(document.body).on('click','.edit-gallery',function (event) {
                event.preventDefault();

                var frame, editButton = this;
                var galleryState = $(editButton).parent().find('.door-color-gallery').val();

                // Create a new media frame
                frame = wp.media({
                    title: '<?= __('Select door images') ?>',
                    button: {
                        text: '<?= __('Update color gallery') ?>'
                    },
                    multiple: 'add' // Set to true to allow multiple files to be selected
                });

                frame.on('open',function() {
                    if (galleryState) {
                        var selection = frame.state().get('selection');
                        JSON.parse(galleryState).map(function(item) {
                            console.log(item);
                            selection.add(wp.media.attachment(item));
                        });
                    }
                });

                // When an image is selected in the media frame...
                frame.on( 'select', function() {

                    // Get media attachment details from the frame state
                    var attachment = frame.state().get('selection').toJSON().map(function(item) {
                        return item.id;
                    });

                    $(editButton).parent().find('.door-color-gallery').val(JSON.stringify(attachment));
                });

                // Finally, open the modal on click
                frame.open();
            });
        });
    </script>
    </div><?php
}

/* Save function for the entered data */
function color_meta_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verifying the nonce
    if ( !isset( $_POST['door_color_nonce'] ) ) {
        return;
    }

    if ( !wp_verify_nonce( $_POST['door_color_nonce'], plugin_basename( __FILE__ ) ) ) {
        return;
    }

    $doorColor = [];
    if (isset($_POST['doorColor'])) {
        $doorColor = $_POST['doorColor'];
    }
    // Updating the doorColor meta data

    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            update_post_meta(pll_get_post($post_id, $lang),'doorColor',$doorColor);
        }
    } else {
        update_post_meta($post_id,'doorColor',$doorColor);
    }


}