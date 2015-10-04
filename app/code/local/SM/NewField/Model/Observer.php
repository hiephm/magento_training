<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 6:15 PM
 */
class SM_NewField_Model_Observer
{
    public function salesQuoteSaveBefore(Varien_Event_Observer $observer)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = $observer->getEvent()->getQuote();
        if (!$quote) {
            return;
        }

        $comments = Mage::app()->getRequest()->getParam('sm_comments', false);
        if (!$comments) {
            return;
        }

        $quote->setComments(Mage::helper('core')->escapeHtml($comments));
    }
}