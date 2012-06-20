<?php

class Openstream_Osmm_Adminhtml_OsmmController extends Mage_Adminhtml_Controller_Action
{
    public function ajaxBlockAction(){
        $output = $this->getLayout()->createBlock('Openstream_Osmm_Block_View')->toHtml();
        $this->getResponse()->setBody($output);
        return;
	}

	public function wireXmlAction()
    {
        $output   = '';
        $output = $this->getLayout()->createBlock('Openstream_Osmm_Block_WireXml')->toHtml();
        $this->getResponse()->setBody($output);
        return;
	}
}