<?php

class Openstream_Osmm_Block_Adminhtml_Keyword_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        	'enctype' => 'multipart/form-data'
        ));

        $form->setHtmlIdPrefix('general');
        $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('osmm')->__('General')));

        if (Mage::registry('current_osmm_keyword')->getId()) {
            $fieldset->addField('notice', 'label', array(
                'value' => 'Please note that if you make any sort of modifications you will save all previously stored data for this keyword'
            ));
            $fieldset->addField('query_id', 'label', array(
                'label' => Mage::helper('osmm')->__('Keyword ID:'),
            ));
        }

        $fieldset->addField('query_q', 'text', array(
            'label'     => Mage::helper('osmm')->__('Keyword:'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'query_q',
        ));
        $fieldset->addField('query_lang', 'text', array(
            'label'     => Mage::helper('osmm')->__('Language Code (ISO 639-1):'),
            'name'      => 'query_lang',
            'comment'   => 'tst'
        ));
        $fieldset->addField('query_geocode', 'text', array(
            'label'     => Mage::helper('osmm')->__('Geo Code:'),
            'name'      => 'query_geocode'
        ));

        $form->addValues($this->_getFormData());

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     *
     * @return array
     */
    protected function _getFormData()
    {
        $data = Mage::getSingleton('adminhtml/session')->getFormData();

        if (!$data && Mage::registry('current_osmm_keyword')->getData()) {
            $data = Mage::registry('current_osmm_keyword')->getData();
        }

        return (array) $data;
    }
}