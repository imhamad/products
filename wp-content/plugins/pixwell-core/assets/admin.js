/** PIXWELL_CORE_ADMIN */
var PIXWELL_CORE_ADMIN = (function (Module, $) {
    "use strict";

    /** init */
    Module.init = function () {
        var self = this;
        self.widgetImageUpload();
        self.widgetClearImage();
        self.widgetColorPicker();
    };

    /** clear image */
    Module.widgetClearImage = function () {
        $(document).on('click', '.w-clear-img', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var targetID = $(this).data('id');
            $('#' + targetID + 'image').val('').trigger('change');
            $('#' + targetID + 'preview').html('');
        });
    };

    /** widget image upload */
    Module.widgetImageUpload = function () {
        $(document).on('click', '.w-upload-img', function (event) {
            if (typeof wp === 'undefined' || !wp.media) {
                return;
            }
            event.preventDefault();
            event.stopPropagation();

            var targetID = $(this).data('id');
            if (frame) {
                frame.open();
                return;
            } else {
                var frame = wp.media({
                    title: 'Select Media',
                    multiple: false
                });
            }

            /** pre-selection */
            frame.on('open', function () {
                var currentVal = $('#' + targetID + 'image').val();
                var selection = frame.state().get('selection');
                if (currentVal != null && currentVal.length > 0) {
                    var attachment = wp.media.attachment(currentVal);
                    selection.add(attachment ? [attachment] : []);
                }
            });

            frame.on('select', function () {
                var preview = $('#' + targetID + 'preview');
                var attachment = frame.state().get('selection').first();
                preview.html('');

                var selectionID = attachment.attributes.id;
                var previewImg = typeof attachment.attributes.thumb !== 'undefined' ? attachment.attributes.thumb.src : attachment.attributes.url;
                var previewHTML = '<img src="' + previewImg + '"/>';
                preview.append(previewHTML);

                $('#' + targetID + 'image').val(selectionID).trigger('change');
            });

            frame.open();
        });
    };

    /** color picker */
    Module.widgetColorPicker = function () {
        $(document).on('panelsopen widget-added widget-updated', function () {
            $(document).on('click', 'input.w-color-field', function () {
                $(this).wpColorPicker();
            })
        });
    };

    return Module;

}(PIXWELL_CORE_ADMIN || {}, jQuery));

jQuery(document).ready(function () {
    PIXWELL_CORE_ADMIN.init();
});
