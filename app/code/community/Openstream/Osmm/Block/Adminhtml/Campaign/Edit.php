<?php

class Openstream_Osmm_Block_Adminhtml_Campaign_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'osmm';
        $this->_controller = 'adminhtml_campaign';
        $this->_mode = 'edit';
    }

    protected function  _prepareLayout()
    {
        $this->_updateButton('save', 'label', Mage::helper('osmm')->__('Save Campaign'));
        $this->_updateButton('delete', 'label', Mage::helper('osmm')->__('Delete Campaign'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit();',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            Event.observe(window, 'load', function() {
                var projectKeywords = ".$this->getKeywordsJson().";
                $('edit_form').insert(new Element('input', { 'name':'project_keywords', 'type':'hidden', 'id':'in_project_keywords', 'value':projectKeywords }));

                keywordsGridJsObject.checkboxCheckCallback = function(grid, element, checked){
                    if (checked && parseInt(element.value) && projectKeywords.indexOf(element.value) == -1) {
                        projectKeywords[projectKeywords.size()] = element.value;
                    } else if(!checked && parseInt(element.value) && projectKeywords.indexOf(element.value) > -1) {
                        projectKeywords = projectKeywords.without(element.value);
                    }
                    $('in_project_keywords').value = projectKeywords;
                };

                keywordsGridJsObject.rowClickCallback = function(grid, event){
                    var trElement = Event.findElement(event, 'tr');
                    var isInput   = Event.element(event).tagName == 'INPUT';
                    if(trElement){
                        var checkbox = Element.getElementsBySelector(trElement, 'input');
                        if(checkbox[0]){
                            var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                            keywordsGridJsObject.setCheckboxChecked(checkbox[0], checked);
                        }
                    }
                }

            });
        ";
        return parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        /* @var $model Openstream_Osmm_Model_Project */
        if(($model = Mage::registry('current_osmm_project')) && $model->getId()) {
            return Mage::helper('osmm')->__("Edit Campaign '%s'", $this->escapeHtml($model->getData('project_name')));
        } else {
            return Mage::helper('osmm')->__('New Campaign');
        }
    }

    public function getKeywordsJson()
    {
        $keywords = Mage::helper('osmm')->getProject()->getKeywords();
        if (!empty($keywords)) {
            return Mage::helper('core')->jsonEncode($keywords);
        }
        return '[]';
    }
}