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

class ProductQuantityPriceUpdate extends Module
{

    public function __construct()
    {
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

    public function install()
    {
        return parent::install() &&
                $this->registerHook('actionFrontControllerSetMedia') &&
                $this->registerHook('displayProductPriceBlock');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionFrontControllerSetMedia()
    {
        $pageName = $this->context->controller->php_self;
        if ($pageName == "product") {
            $this->context->controller->addJS($this->_path . '/views/js/front.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        }
    }

    public function hookdisplayProductPriceBlock($params)
    {
        if ($params['type'] == 'after_price') {
            return $this->display(__FILE__, 'displayProductPriceBlock.tpl');
        }
    }
}
