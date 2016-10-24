<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductQuantityPriceUpdateProductPriceByQuantityModuleFrontController extends ModuleFrontController
{

    public function init()
    {
        if (!$this->ajax) {
            exit;
        }
        parent::init();
    }

    public function initContent()
    {
        $product_id = (int) Tools::getValue('product_id');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $quantity = (int) Tools::getValue('quantity');

        $this->validateClientInput($product_id, $id_product_attribute, $quantity);
        $this->getProductPrice($product_id, $id_product_attribute, $quantity);
    }

    private function validateClientInput($product_id, $id_product_attribute, $quantity)
    {
        if ($product_id <= 0 || $id_product_attribute < 0 || $quantity < 0) {
            header('Content-Type: application/json');
            $this->ajaxDie(Tools::jsonEncode(array('error' => 'user input validation failed')));
        }
    }

    private function getProductPrice($product_id, $id_product_attribute, $quantity)
    {
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
