<?php

class Openstream_Osmm_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 0;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('osmm')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('osmm')->__('Disabled')
        );
    }
}