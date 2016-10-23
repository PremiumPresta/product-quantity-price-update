<?php

if (!defined('_PS_VERSION_')) exit;

class ProductQuantityPriceUpdate extends Module {

  public function __construct() {
    $this->name = 'productquantitypriceupdate';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'PremiumPresta';
    $this->need_instance = 0;

    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Product Quantity Price Update');
    $this->description = $this->l('On a product page, it instantly displays the price for a product when the quantity changes.');

    $this->confirmUninstall = $this->l('');
  }

  public function install() {
    return parent::install() &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('displayProductPriceBlock');
  }

  public function uninstall() {
    return parent::uninstall();
  }

//    public function hookHeader() {
  public function hookActionFrontControllerSetMedia() {
    $pageName = $this->context->controller->php_self;
    if ($pageName == "product") {
      $this->context->controller->addJS($this->_path . '/views/js/front.js');
      $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }
  }

  public function hookdisplayProductPriceBlock($params) {
    if ($params['type'] == 'after_price') return $this->display(__FILE__, 'displayProductPriceBlock.tpl');
  }

}
