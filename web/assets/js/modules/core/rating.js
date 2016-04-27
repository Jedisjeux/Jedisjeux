$(function() {

    "use strict";

    $(".rating").each(function() {
        $(this).rate({
            max_value: $(this).attr("data-max-value"),
            readonly: $(this).attr("data-readonly"),
            selected_symbol_type: 'fontawesome_star',
            update_input_field_name: $('#' + $(this).attr("data-update-input-field-name"))
        });
    });

    $(".rating", "form[name=sylius_product_review_rating]").on("change", function(ev, data) {
        console.log(data.from, data.to);
        $(this).closest('form').submit();


        //$(this).closest('form').submit();
    }).on("updateSuccess", function(ev, data){
        console.log("This is a custom success event");
    });
});