<?php

class Openstream_Osmm_Block_Adminhtml_Campaign extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'osmm';
        $this->_controller = 'adminhtml_campaign';
		$this->_headerText = Mage::helper('osmm')->__('Campaign Manager');
		$this->_addButtonLabel = Mage::helper('osmm')->__('New Campaign');

		parent::__construct();
     }
}
