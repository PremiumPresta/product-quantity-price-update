<?php

if (!defined('_PS_VERSION_')) exit;

class ProductQuantityPriceUpdateProductPriceByQuantityModuleFrontController extends ModuleFrontController {

  public function init() {
    if (!$this->ajax) exit;
    parent::init();
  }

  public function initContent() {
    parent::initContent();

    $product_id = (int) Tools::getValue('product_id');
    $id_product_attribute = (int) Tools::getValue('id_product_attribute');
    $quantity = (int) Tools::getValue('quantity');

    $this->validateClientInput($product_id, $id_product_attribute, $quantity);
    $this->getProductPrice($product_id, $id_product_attribute, $quantity);
  }

  private function validateClientInput($product_id, $id_product_attribute, $quantity) {
    if ($product_id <= 0 || $id_product_attribute < 0 || $quantity < 0) {
      header('Content-Type: application/json');
      $this->ajaxDie(Tools::jsonEncode(array('error' => 'user input validation failed')));
    }
  }

  private function getProductPrice($product_id, $id_product_attribute, $quantity) {
    $product = new Product($product_id);

    $productUnitPrice = $product->getPrice($tax = true, $id_product_attribute, $decimals = 6, $divisor = null, $only_reduc = false, $usereduc = true, $quantity);

    $productPriceWithQuantity = $productUnitPrice * $quantity;

    $return = array(
        'quantity' => $quantity,
        'priceWithQuantity' => $productPriceWithQuantity
    );

    header('Content-Type: application/json');
    $this->ajaxDie(Tools::jsonEncode($return));
  }

}
