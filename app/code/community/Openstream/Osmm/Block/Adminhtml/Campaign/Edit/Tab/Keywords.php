<?php

class Openstream_Osmm_Block_Adminhtml_Campaign_Edit_Tab_Keywords extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){
        parent::__construct();
        $this->setId('keywordsGrid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    public function _prepareCollection(){
        $collection = Mage::getModel('osmm/query')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns(){
        $this->addColumn('in_category', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_category',
            'values'    => $this->_getSelectedKeywords(),
            'align'     => 'center',
            'index'     => 'query_id'
        ));
        $this->addColumn('query_id', array(
            'header' => $this->__('ID'),
            'sortable' => true,
            'index' => 'query_id',
            'width' => '40'
        ));
        $this->addColumn('query_q', array(
            'header' => 'Keyword',
            'sortable' => true,
            'index' => 'query_q'
        ));
        $this->addColumn('query_lang', array(
            'header' => 'Language',
            'sortable' => true,
            'index' => 'query_lang'
        ));
        $this->addColumn('query_geocode', array(
            'header' => 'Geo Code',
            'sortable' => true,
            'index' => 'query_geocode'
        ));
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    protected function _getSelectedKeywords()
    {
        $keywords = $this->getRequest()->getPost('project_keywords');
        if (is_null($keywords)) {
            $keywords = Mage::helper('osmm')->getProject()->getKeywords();
        }
        return $keywords;
    }
}