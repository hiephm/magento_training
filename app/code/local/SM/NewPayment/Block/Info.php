<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 7:42 PM
 */
class SM_NewPayment_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sm_newpayment/info.phtml');
    }

}