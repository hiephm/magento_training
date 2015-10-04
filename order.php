<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 6:36 PM
 */
require_once 'app/Mage.php';

Mage::app();

function placeOrder($customer, $products, $paymentMethod, $shippingMethod)
{
    $storeId = Mage::app()->getStore('default')->getId();
    $quote = Mage::getModel('sales/quote')->setStoreId($storeId);

    $quote->setCustomerEmail($customer['email'])
        ->setCustomerIsGuest($customer['is_guess']);

    foreach ($products as $product) {
        addProduct($product, $quote);
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

function addProduct($product, Mage_Sales_Model_Quote $quote)
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

$shipping = 'flatrate_flatrate';

$order = placeOrder($customer, $products, $payment, $shipping);
echo $order->getIncrementId() . "\n";