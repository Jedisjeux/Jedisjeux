(function ($) {
    'use strict';

    $.fn.extend({
        personAutoComplete: function () {
            $(this).each(function () {
                var $element = $(this);
                var createUrl = $element.data('create-url');

                $element.dropdown('setting', 'allowAdditions', true);
                $element.dropdown('setting', 'hideAdditions', false);

                $element.dropdown('setting', 'onAdd', function (addedValue, addedText, $addedChoice) {
                    if ($addedChoice.hasClass('addition')) {
                        var lastName = getLastNameByConcatenatedNames(addedText);
                        var firstName = getFirstNameByConcatenatedNames(addedText);

                        postPerson($element, addedText, createUrl, lastName, firstName);
                    }
                });
            });
        }
    });

    /**
     * @param {jQuery} $element
     * @param {string} addedText
     * @param {string} url
     * @param {string} lastName
     * @param {string} firstName
     */
    function postPerson($element, addedText, url, lastName, firstName) {

        var $autoCompleteInput = $element.find('input.autocomplete');
        var values = $autoCompleteInput.val().split(',');

        $.ajax({
            type: "POST",
            url: url,
            data: {
                lastName: lastName,
                firstName: firstName
            },
            dataType: "json",
            accept: "application/json",
            success: function (data) {
                values.push(data.id);
                $autoCompleteInput.val(values.join(','));
            }
        });
    }

    /**
     * @param {string} names
     *
     * @return {string}
     */
    function getLastNameByConcatenatedNames(names) {
        var nameParts = names.split(' ');

        if (nameParts.length === 1) {
            return nameParts[0];
        }

        nameParts.reverse();
        nameParts.pop();

        return nameParts.join(' ');
    }

    /**
     * @param {string} names
     *
     * @return {string|null}
     */
    function getFirstNameByConcatenatedNames(names) {
        var lastName = getLastNameByConcatenatedNames(names);
        var firstName = names.replace(lastName, '').trim();

        return firstName !== "" ? firstName : null;
    }

})(jQuery);
