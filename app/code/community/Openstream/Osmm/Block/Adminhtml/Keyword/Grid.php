<?php

class Openstream_Osmm_Block_Adminhtml_Keyword_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function _construct()
    {
        parent::_construct();

        $this->setId('keyword');
        $this->setDefaultSort('id');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('osmm/query')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn('query_q', array(
            'header'	=> Mage::helper('osmm')->__('Keyword'),
            'align'		=> 'left',
            'index'		=> 'query_q',
        ));
        $this->addColumn('query_lang', array(
            'header'	=> Mage::helper('osmm')->__('Language Code'),
            'align'		=> 'left',
            'index'		=> 'query_lang'
        ));
        $this->addColumn('query_geocode', array(
            'header'	=> Mage::helper('osmm')->__('Geo Code'),
            'align'		=> 'left',
            'index'		=> 'query_geocode'
        ));
        $this->addColumn('action', array(
            'header'    =>  Mage::helper('osmm')->__('Action'),
            'type'      => 'action',
            'align'		=> 'center',
            'width'		=> '70px',
            'getter'    => 'getId',
            'actions'   => array(
                array('caption'   => Mage::helper('osmm')->__('Edit'),
                    'url'       => array('base'=> '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true
        ));

        return parent::_prepareColumns();
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('query_id');
        $this->getMassactionBlock()->setFormFieldName('query_id');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('osmm')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('osmm')->__('Are you sure?')
        ));

        return $this;
    }

    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}