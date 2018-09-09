<?php

add_action( 'add_meta_boxes', 'add_color_meta_windows' );

/* Saving the data */
add_action( 'save_post', 'color_meta_save_windows' );

register_meta( 'window', 'windowColor', [
    'single' => true,
    'show_in_rest' => true,
]);

/* Adding the main meta box container to the post editor screen */
function add_color_meta_windows() {
    add_meta_box(
        'window-color',
        __('Color'),
        'window_color_init',
        'window'); // post type
}

/*Printing the box content */
function window_color_init() {
    global $post;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'window_color_nonce' );
    ?>
    <div id="window_meta_item">
    <?php

    //Obtaining the linked windowcolor meta values
    $windowColor = get_post_meta($post->ID,'windowColor',true);
    $count = 0;
    if (is_array($windowColor) && count($windowColor) > 0) {
        foreach( $windowColor as $windowDetail ) {
            printf(
                '<p>' .
                __('Name') . ': <input type="text" name="windowColor[%1$s][name]" value="%2$s" /> ' .
                __('Color') . ': <input type="text" class="window-color-hex" name="windowColor[%1$s][hex]" value="%3$s" /> ' .
                __('Gallery') . ': <input type="hidden" class="window-color-gallery" name="windowColor[%1$s][gallery]" value="%4$s" /> <button class="edit-gallery"> ' . __('Edit Gallery') . '</button>' .
                '<a class="remove-window-color">'.__('Remove').'</a>' .
                '</p>',
                $count, $windowDetail['name'], $windowDetail['hex'], $windowDetail['gallery']
            );

            $count++;
        }
    }

    ?>
    <span id="color-package"></span>
    <a href="#" class="add_color"><?= __('Add Window Color'); ?></a>
    <script>
        var $ =jQuery.noConflict();

        $(document).ready(function() {
            $('.window-color-hex').wpColorPicker();

            var count = <?php echo (is_array($windowColor) ? sizeof($windowColor) : 0) ?>;
            $(".add_color").click(function() {
                count = count + 1;

                $('#color-package').append(
                    '<p>' +
                    '<?= __('Name') ?>: <input type="text" name="windowColor['+count+'][name]" /> ' +
                    '<?= __('Color') ?>: <input type="text" class="window-color-hex" name="windowColor['+count+'][hex]" /> ' +
                    '<?= __('Gallery') ?>: <input type="hidden" class="window-color-gallery" name="windowColor['+count+'][gallery]" /> <button class="edit-gallery"><?= __('Edit Gallery') ?></button>' +
                    '<a class="remove-window-color"><?= __('Remove') ?></a>' +
                    '</p>');
                $( "input[name='windowColor["+count+"][hex]']" ).wpColorPicker();
                return false;
            });
            $(document.body).on('click','.remove-window-color',function() {
                $(this).parent().remove();
            });
            $(document.body).on('click','.edit-gallery',function (event) {
                event.preventDefault();

                var frame, editButton = this;
                var galleryState = $(editButton).parent().find('.window-color-gallery').val();

                // Create a new media frame
                frame = wp.media({
                    title: '<?= __('Select window images') ?>',
                    button: {
                        text: '<?= __('Update color gallery') ?>'
                    },
                    multiple: 'add' // Set to true to allow multiple files to be selected
                });

                frame.on('open',function() {
                    if (galleryState) {
                        var selection = frame.state().get('selection');
                        JSON.parse(galleryState).map(function(item) {
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

                    $(editButton).parent().find('.window-color-gallery').val(JSON.stringify(attachment));
                });

                // Finally, open the modal on click
                frame.open();
            });
        });
    </script>
    </div><?php
}

/* Save function for the entered data */
function color_meta_save_windows( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Verifying the nonce
    if ( !isset( $_POST['window_color_nonce'] ) ) {
        return;
    }

    if ( !wp_verify_nonce( $_POST['window_color_nonce'], plugin_basename( __FILE__ ) ) ) {
        return;
    }

    $windowColor = [];
    if (isset($_POST['windowColor'])) {
        $windowColor = $_POST['windowColor'];
    }

    // normalize array keys 0, 1, 2, 3, 4... instead of 1, 4, 5, 6...
    $normalizedWindowColor = [];
    foreach ($windowColor as $d) {
        $normalizedWindowColor[] = $d;
    }

    // Updating the windowColor meta data
    if (function_exists('pll_languages_list')) {
        foreach (pll_languages_list() as $lang) {
            update_post_meta(pll_get_post($post_id, $lang),'windowColor',$normalizedWindowColor);
        }
    } else {
        update_post_meta($post_id,'windowColor',$normalizedWindowColor);
    }


}