<?php

class Openstream_Osmm_Block_Adminhtml_Keyword extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'osmm';
        $this->_controller = 'adminhtml_keyword';
		$this->_headerText = Mage::helper('osmm')->__('Keyword Manager');
		$this->_addButtonLabel = Mage::helper('osmm')->__('Add Keyword');

		parent::__construct();
     }
}
