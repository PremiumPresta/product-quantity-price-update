/*
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2016 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$(document).ready(function () {
    // Check for changes on quantity and select attributes (such as Size - S, M, L)
    $(document).on('change', '#quantity_wanted, .attribute_select, #idCombination', function (e) {
        e.preventDefault();
        var quantity = $('#quantity_wanted').val();

        // send request to server only if there is more quantity
        if (quantity > 1) getProductPriceWithQuantity();
        if (quantity == 1) $('.js-product-price-with-quantity').addClass('hidden');
    });

    // Check for changes when clicking on colors and radio buttons
    $(document).on('click', '#color_to_pick_list a, .attribute_radio', function (e) {
        e.preventDefault();
        findCombination();
        $('#idCombination').trigger('change');
    });
});

function getProductPriceWithQuantity() {
    var product_id = $('#buy_block').find('#product_page_product_id').val();
    var id_product_attribute = $('#buy_block').find('#idCombination').val();
    var quantity = $('#quantity_wanted').val();

    $.get(baseUri + '?rand=' + new Date().getTime(), {
        controller: 'productpricebyquantity',
        module: 'productquantitypriceupdate',
        fc: 'module',
        product_id: product_id,
        id_product_attribute: id_product_attribute,
        quantity: quantity,
        ajax: true,
        dataType: 'json'
    }).done(function (response) {
        if (response.error) {
            console.error(response.error);
            return;
        }

        var productQuantityPriceElement = $('.js-product-price-with-quantity');
        productQuantityPriceElement
                .removeClass('hidden')
                .html('<p class="price-with-quantity-wrapper">' + product_quantity_text_1 + ' <span class="pwq-quantity">' + response.quantity + '</span> ' + product_quantity_text_2 + ' <span class="pwq-price">' + formatCurrency(response.priceWithQuantity, currencyFormat, currencySign, currencyBlank) + '</span></p>');
    }).fail(function (response) {
        console.error(response);
    });
}