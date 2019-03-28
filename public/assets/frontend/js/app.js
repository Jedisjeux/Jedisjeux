(function($) {
    $(document).ready(function () {
        $('[data-form-type="collection"]').CollectionForm();
        $('.app-notifications').notifications();

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image-preview').attr('src', e.target.result).show();

                    const cropper = new Cropper(image, {
                        crop(event) {
                            console.log(event.detail.x);
                            console.log(event.detail.y);
                            console.log(event.detail.width);
                            console.log(event.detail.height);
                            console.log(event.detail.rotate);
                            console.log(event.detail.scaleX);
                            console.log(event.detail.scaleY);
                        },
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#app_simple_product_box_image_file").change(function() {
            readURL(this);
        });

        const image = document.getElementById('image-preview');

    });
})(jQuery);
