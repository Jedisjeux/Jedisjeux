$(function() {

    "use strict";

    var $ratingForm = $('form[name=sylius_product_review_rating]');
    var successMessage = "Votre note a bien été enregistrée.";

    //initializeAjaxForm(successMessage);

    $(".rating").each(function() {
        $(this).rate({
            max_value: $(this).attr("data-max-value"),
            readonly: $(this).attr("data-readonly"),
            selected_symbol_type: 'fontawesome_star',
            update_input_field_name: $('#' + $(this).attr("data-update-input-field-name")),
            step_size: $(this).attr("data-step-size"),
            symbols: {
                fontawesome_star: {
                    base: '<i class="fa fa-star grey"></i>',
                    hover: '<i class="fa fa-star yellow"></i>',
                    selected: '<i class="fa fa-star yellow"></i>',
                }
            }
        });
    });

    $('.rating.rate').on("change", function(event, data) {

        var rateValue = $(this).attr('data-rate-value');
        var productId = $(this).attr('data-product-id');
        var routeName;
        var type;
        var newRateValue = data.to;

        if (rateValue > 0) {
            routeName = 'sylius_api_product_review_rating_update';
            type = 'PUT';
        } else {
            routeName = 'sylius_api_product_review_rating_create';
            type = 'POST';
        }

        $.ajax({
            url: Routing.generate(routeName, { 'productId': productId }),
            type: type,
            data: {'rating': newRateValue},
            success: function(data, textStatus, xhr) {
                appendFlash(successMessage);
            },
            error: function(xhr, textStatus, errorThrown) {
                if(xhr.status===401) {
                    //handle error
                    window.location.replace('/login');
                } else {
                    renderErrors(xhr);
                }
            }
        });
    });

    function initializeAjaxForm(successMessage, messageHolderSelector) {
        $ratingForm.submit(function(e) {
            e.preventDefault();
            var form = $(this);

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: parseFormToJson(form),
                dataType: "json",
                accept: "application/json",
                success: function(data, textStatus, xhr) {
                    completeRequest(form);
                    appendFlash(successMessage, messageHolderSelector);
                },
                error: function(xhr, textStatus, errorThrown) {
                    renderErrors(xhr, form);
                }
            });
        });
    }

    function renderErrors(xhr, form) {
        clearErrors(form);

        $.each(xhr.responseJSON.errors.errors, function(key, value) {
            $('div.panel-body').addClass('has-error').prepend('<span class="help-block form-error">' + value + '</span>');
        });
    }

    function clearErrors(form) {
        form.find('.form-error').remove();
        form.find('.has-error').removeClass('has-error');
    }

    function parseFormToJson(form) {
        var formJson = {};
        $.each(form.serializeArray(), function(index, field) {
            var name = field.name.replace('sylius_product_review[', '').replace(']', '');
            formJson[name] = field.value || '';
        });

        return formJson;
    }

    function appendFlash(successMessage, messageHolderSelector) {
        messageHolderSelector = messageHolderSelector ? messageHolderSelector : '#flashes';

        $(messageHolderSelector).html('<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a>' + successMessage + '</div>');
    }

    function completeRequest(form) {
        form[0].reset();
        clearErrors(form);
        $("html, body").animate({ scrollTop: 0 }, "slow");
    }

});