<?php
/**
 * Created by PhpStorm.
 * User: hiephm
 * Date: 10/4/2015
 * Time: 5:20 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE `{$this->getTable('sales/order')}`
        ADD COLUMN `comments` VARCHAR(200) NULL;
");

$installer->run("
    ALTER TABLE `{$this->getTable('sales/quote')}`
        ADD COLUMN `comments` VARCHAR(200) NULL;
");

$installer->endSetup();