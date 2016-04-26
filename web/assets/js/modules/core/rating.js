$(function() {

    "use strict";

    $(".rating").each(function() {
        $(this).rate({
            max_value: $(this).attr("data-max-value"),
            readonly: $(this).attr("data-readonly"),
            selected_symbol_type: 'fontawesome_star',
            update_input_field_name: $("#sylius_product_review_rating")
        });
    });

});