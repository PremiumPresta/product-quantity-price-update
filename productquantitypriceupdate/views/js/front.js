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