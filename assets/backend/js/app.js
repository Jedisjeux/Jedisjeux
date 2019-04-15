import 'semantic-ui-calendar/dist/calendar';
import 'semantic-ui-css/components/accordion';
import $ from 'jquery';

import 'sylius/ui/app';
import 'sylius/ui/sylius-auto-complete';
import 'sylius/ui/sylius-product-attributes';
import 'sylius/ui/sylius-product-auto-complete';
import 'sylius/ui/sylius-prototype-handler';

import './app-date-time-picker';
import './app-move-blocks';
import './app-person-auto-complete';
import './sylius-compound-form-errors';
import './sylius-move-product-variant';
import './sylius-product-slug';
import './sylius-taxon-slug';

(function ($) {
    $(document).ready(function () {
        $('.sylius-autocomplete').autoComplete();
        $('.sylius-tabular-form').addTabErrors();
        $('.ui.accordion').addAccordionErrors();
        $('.product-select.ui.fluid.multiple.search.selection.dropdown').productAutoComplete();
        $('.sylius-update-product-variants').moveProductVariant($('.sylius-product-variant-position'));
        $(document).productSlugGenerator();
        $(document).taxonSlugGenerator();

        $(document).moveBlocks();

        $(document).on('dom-node-inserted', function(addedElement) {
            $('.ui.checkbox').checkbox();
        });

        $('.person-autocomplete').personAutoComplete();
        $('.app-date-picker').datePicker();
        $('.app-date-time-picker').dateTimePicker();
    });
})(jQuery);

window.$ = $;
window.jQuery = $;
