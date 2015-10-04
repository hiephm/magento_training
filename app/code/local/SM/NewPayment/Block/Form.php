<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 7:41 PM
 */
class SM_NewPayment_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sm_newpayment/form.phtml');
    }

}