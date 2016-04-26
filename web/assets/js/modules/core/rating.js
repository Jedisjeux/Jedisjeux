$(function() {

    "use strict";

    $(".rating").rate({
        selected_symbol_type: 'fontawesome_star',
        update_input_field_name: $("#sylius_product_review_rating")
    });

});