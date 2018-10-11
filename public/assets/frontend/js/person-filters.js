(function($) {

    "use strict";

    var $form = $('form', '#person-filters');
    var $select = $('#criteria_zone_mainTaxon');
    var taxon = $('#person-filters').data('taxon');

    $(document).ready(function () {
        selectTaxon(taxon);
        handleFormSubmit();
    });

    function selectTaxon(taxon) {
        $('option', $select).each(function() {
            if (taxon === $(this).val()) {
                $(this).attr('selected', 'selected');
            }
        });
    }

    function handleFormSubmit() {
        if (window.location.href.match('/jeu-de-societe/')) {
            return;
        }

        $form.submit(function(event) {
            event.preventDefault();

            var queryParams = {};
            var slug = $select.val();

            queryParams['criteria[role]'] = $('#criteria_role').val();

            if (slug) {
                queryParams.slug = slug;
                window.location = Routing.generate('app_frontend_person_index_by_taxon', queryParams );

                return;
            }

            window.location = Routing.generate('app_frontend_person_index', queryParams );
        });
    }
})(jQuery);
