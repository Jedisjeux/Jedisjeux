/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function($) {
  $(document).ready(function () {
    $('.product-select.ui.fluid.multiple.search.selection.dropdown').productAutoComplete();
    $('.sylius-update-product-variants').moveProductVariant($('.sylius-product-variant-position'));
  });
})(jQuery);
