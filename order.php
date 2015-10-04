<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 6:36 PM
 */
require_once 'shell/abstract.php';

class SM_Order_Creator extends Mage_Shell_Abstract
{
    protected function placeOrder($customer, $products, $paymentMethod, $shippingMethod)
    {
        $storeId = Mage::app()->getStore('default')->getId();
        $quote = Mage::getModel('sales/quote')->setStoreId($storeId);

        $quote->setCustomerEmail($customer['email'])
            ->setCustomerIsGuest($customer['is_guess']);

        foreach ($products as $product) {
            $this->addProduct($product, $quote);
        }
        $quote->save();

        $billingAddress = $quote->getBillingAddress();
        $billingAddress->addData($customer['billing_address']);

        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->addData($customer['shipping_address']);

        $quote->collectTotals();

        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod($shippingMethod);

        $quote->getPayment()->importData($paymentMethod);

        $quote->setTotalsCollectedFlag(false)->collectTotals();

        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();

        $order = $service->getOrder();

        return $order;
    }

    protected function addProduct($product, Mage_Sales_Model_Quote $quote)
    {
        $productModel = Mage::getModel('catalog/product');
        if (!empty($product['sku'])) {
            $productModel = $productModel->loadByAttribute('sku', $product['sku']);
            $productModel->load($productModel->getId());
        } else {
            $productModel->load($product['id']);
        }

        $buyInfo = ['qty' => $product['qty']];
        $quote->addProduct($productModel, new Varien_Object($buyInfo));
    }

    public function run()
    {
        $shipping = $this->getArg('shipping') ? $this->getArg('shipping') : 'flatrate_flatrate';

        $customer = [
            "email" => "hieptest@smartosc.com",
            "is_guess" => 1,
            "billing_address" => [
                "city" => "test",
                "firstname" => "Hiep",
                "lastname" => "Ho",
                "company" => "SmartOSC",
                "country_id" => "VN",
                "telephone" => "0123456789",
                "street" => "test",
                "postcode" => 12345,
                "region" => "test"
            ],
            "shipping_address" => [
                "city" => "test",
                "firstname" => "Hiep",
                "lastname" => "Ho",
                "company" => "SmartOSC",
                "country_id" => "AU",
                "telephone" => "0123456789",
                "street" => "test",
                "postcode" => 3023,
                "region" => "VIC"
            ]
        ];

        $products = [
            ['sku' => 'hdb000', 'qty' => 2],
            ['sku' => 'acj005', 'qty' => 1],
        ];

        $payment = [
            "method" => "ccsave",
            "cc_type" => "VI",
            "cc_number" => 4444333322221111,
            "cc_exp_month" => 3,
            "cc_exp_year" => 2017,
            "cc_cid" => 123,
            "cc_owner" => "Hiep Ho",
        ];

        $order = $this->placeOrder($customer, $products, $payment, $shipping);
        echo $order->getIncrementId() . "\n";
    }

    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f order.php -- [options]

  -h            Short alias for help
  help          This help
  --shipping <shipping method> Set shipping method, default is flatrate_flatrate
USAGE;
    }

}

$orderCreator = new SM_Order_Creator();
$orderCreator->run();




