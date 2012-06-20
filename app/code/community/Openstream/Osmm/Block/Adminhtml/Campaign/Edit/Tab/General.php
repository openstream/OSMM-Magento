<?php

class Openstream_Osmm_Block_Adminhtml_Campaign_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('general');
        $fieldset = $form->addFieldset('general_form', array('legend' => Mage::helper('osmm')->__('General')));

        if (Mage::registry('current_osmm_project')->getId()) {
            $fieldset->addField('project_id', 'label', array(
                'label' => Mage::helper('osmm')->__('Campaign ID:'),
            ));
        }

        $fieldset->addField('project_name', 'text', array(
            'label'     => Mage::helper('osmm')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'project_name',
        ));

        $fieldset->addField('project_status', 'select', array(
            'label'     => Mage::helper('osmm')->__('Status'),
            'name'      => 'project_status',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('osmm')->__('Enabled'),
                ),

                array(
                    'value'     => 0,
                    'label'     => Mage::helper('osmm')->__('Disabled'),
                ),
            ),
        ));

        $form->addValues($this->_getFormData());

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

        if (!$data && Mage::registry('current_osmm_project')->getData()) {
            $data = Mage::registry('current_osmm_project')->getData();
        }

        return (array) $data;
    }
}