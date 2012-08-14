<?php

class Openstream_Osmm_Block_Dashboard_Diagrams extends Mage_Adminhtml_Block_Dashboard_Diagrams
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->addTab('osmm', array(
            'label'     => $this->__('Social Media Monitoring'),
            'content'   => $this->getLayout()->createBlock('osmm/view')->toHtml(),
			'active'	=> true
        ));
    }
}