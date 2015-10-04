<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 8:03 PM
 */
class SM_NewShip_Model_Carrier_Heavyitem
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'sm_newship';
    protected $_isFixed = true;


    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result|bool|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $shippingPrice = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {

                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                $shippingPrice += $item->getProduct()->getWeight() > $this->getConfigData('weight_threshold') ?
                    $this->getConfigData('price_heavy') : $this->getConfigData('price');
            }
        }

        $result = Mage::getModel('shipping/rate_result');

        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier('sm_newship');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('heavy');
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array('heavy'=>$this->getConfigData('name'));
    }
}
