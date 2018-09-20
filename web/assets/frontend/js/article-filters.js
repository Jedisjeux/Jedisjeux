(function($) {

    "use strict";

    var $form = $('form', '#article-filters');
    var $select = $('#criteria_mainTaxon_mainTaxon');

    $(document).ready(function () {
        $form.submit(function(event) {
            event.preventDefault();

            var slug = $select.val();
            var newUri = URI(window.location.href).directory('/articles/' + slug);
            console.log('newUri', newUri);
        });
    });
})(jQuery);
