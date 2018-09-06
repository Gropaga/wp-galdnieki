jQuery( document ).ready( function( $ ) {
    $(document.body).on('click','.edit-gallery',function (event) {
        event.preventDefault();

        // Create a new media frame
        var frame = wp.media({
            title: 'Select stair images',
            button: {
                text: 'Update stairs gallery',
            },
            multiple: 'add'
        });

        frame.on('open',function() {
            var galleryState = $('#stairs-gallery').val();

            if (galleryState) {
                var selection = frame.state().get('selection');
                JSON.parse(galleryState).map(function(item) {
                    selection.add(wp.media.attachment(item));
                });
            }
        });

        // When an image is selected in the media frame...
        frame.on( 'select', function() {
            var attachment = frame.state().get('selection').toJSON().map(function(item) {
                return item.id;
            });

            $('#stairs-gallery').val(JSON.stringify(attachment));
        });

        // Finally, open the modal on click
        frame.open();
    });
});