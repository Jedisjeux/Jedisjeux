(function($) {

    "use strict";

    var $form = $('form', '#year-award-filters');
    var $select = $('#criteria_award');
    var award = $('#year-award-filters').data('award');

    $(document).ready(function () {
        selectAward(award);
        handleFormSubmit();
    });

    function selectAward(award) {
        $('option', $select).each(function() {
            if (award === $(this).val()) {
                $(this).attr('selected', 'selected');
            }
        });
    }

    function handleFormSubmit() {
        $form.submit(function(event) {
            event.preventDefault();

            var slug = $select.val();
            window.location = Routing.generate('app_frontend_year_award_index_by_award', { slug: slug } );
        });
    }
})(jQuery);
