<?php

class Openstream_Osmm_Block_Adminhtml_Keyword_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'osmm';
        $this->_controller = 'adminhtml_keyword';
        $this->_mode = 'edit';
    }

    protected function  _prepareLayout()
    {
        $this->_updateButton('save', 'label', Mage::helper('osmm')->__('Save Keyword'));
        $this->_updateButton('delete', 'label', Mage::helper('osmm')->__('Delete Keyword'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        /* @var $model Openstream_Osmm_Model_Query */
        if(($model = Mage::registry('current_osmm_keyword')) && $model->getId()) {
            return Mage::helper('osmm')->__("Edit Keyword '%s'", $this->escapeHtml($model->getData('query_q')));
        } else {
            return Mage::helper('osmm')->__('New Keyword');
        }
    }
}