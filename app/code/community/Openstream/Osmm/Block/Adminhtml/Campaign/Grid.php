<?php

class Openstream_Osmm_Block_Adminhtml_Campaign_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function _construct()
    {
        parent::_construct();

        $this->setId('campaign');
        $this->setDefaultSort('id');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
   
	protected function _prepareCollection(){
        $collection = Mage::getModel('osmm/project')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
	}
	
	protected function _prepareColumns(){
       $this->addColumn('project_name', array(
            'header'	=> Mage::helper('osmm')->__('Name'),
            'align'		=> 'left',
            'index'		=> 'project_name',
        ));
		$this->addColumn('project_status', array(
			'header'	=> Mage::helper('osmm')->__('Status'),
        	'align'		=> 'left',
          	'index'		=> 'project_status',
        	'type'		=> 'options',
    		'options'	=> array(
      							1 => 'Enabled',
        						0 => 'Disabled'
        					  ),
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
        $this->setMassactionIdField('project_id');
        $this->getMassactionBlock()->setFormFieldName('project_id');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('osmm')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('osmm')->__('Are you sure?')
        ));

        $this->getMassactionBlock()->addItem('project_status', array(
             'label'=> Mage::helper('osmm')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility'	=> array(
                         'name'		=> 'project_status',
                         'type'		=> 'select',
                         'class'	=> 'required-entry',
                         'label'	=> Mage::helper('osmm')->__('Status'),
                         'values'	=> Mage::getSingleton('osmm/status')->getOptionArray()
                     )
             )
        ));
        return $this;
    }
    
    public function getRowUrl($row){
         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
