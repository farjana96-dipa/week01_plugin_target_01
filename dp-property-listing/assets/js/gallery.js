jQuery(document).ready(function ($) {

    let galleryFrame;

    $('#dp_gallery_button').on('click', function (e) {
        e.preventDefault();

        if (galleryFrame) {
            galleryFrame.open();
            return;
        }

        galleryFrame = wp.media({
            title: 'Select Property Images',
            button: {
                text: 'Add Images'
            },
            multiple: true
        });

        galleryFrame.on('select', function () {
            let selection = galleryFrame.state().get('selection');

            selection.each(function (attachment) {
                attachment = attachment.toJSON();

                $('#dp_gallery_preview').append(
                    '<li style="display:inline-block;margin:5px;">' +
                    '<img src="' + attachment.sizes.thumbnail.url + '" />' +
                    '<input type="hidden" name="dp_gallery[]" value="' + attachment.id + '">' +
                    '</li>'
                );
            });
        });

        galleryFrame.open();
    });

});
