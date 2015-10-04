<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 7:38 PM
 */
class SM_NewPayment_Model_Method extends Mage_Payment_Model_Method_Abstract
{
    protected $_code  = 'sm_newpayment';
    protected $_formBlockType = 'sm_newpayment/form';
    protected $_infoBlockType = 'sm_newpayment/info';

}