<?php

class Openstream_Osmm_Block_Adminhtml_Campaign_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('campaign_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('osmm')->__('Item Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general_section', array(
            'label'     => Mage::helper('osmm')->__('General'),
            'title'     => Mage::helper('osmm')->__('General'),
            'content'   => $this->getLayout()->createBlock('osmm/adminhtml_campaign_edit_tab_general')->toHtml(),
        ));
        $this->addTab('keywords_section', array(
            'label'     => Mage::helper('osmm')->__('Keywords'),
            'title'     => Mage::helper('osmm')->__('Keywords'),
            'content'   => $this->getLayout()->createBlock('osmm/adminhtml_campaign_edit_tab_keywords')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}