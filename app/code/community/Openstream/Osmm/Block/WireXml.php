<?php

class Openstream_Osmm_Block_WireXml extends Mage_Core_Block_Template
{
    private $_projectToQueryTable;

    public function __construct()
    {
        $this->setTemplate('openstream/osmm/wirexml.phtml');

        /** @var $core_resource Mage_Core_Model_Resource */
        $core_resource = Mage::getSingleton('core/resource');
        $this->_projectToQueryTable = $core_resource->getTableName('osmm/projecttoquery');
    }
	
	public function getWireXml(){
        $first = max(0, intval($this->getRequest()->getParam('first')) - 1);
		$from = strtotime(date('Y-m-d', $this->getRequest()->getParam('from')));
		$to = strtotime(date('Y-m-d', $this->getRequest()->getParam('to'))) + 3600*24;
        $project_id = $this->getRequest()->getParam('project');


		/*
		 *  Counting the total number of results
		 */
        /** @var $collection Openstream_Osmm_Model_Resource_Search_Collection */
		$collection = Mage::getModel('osmm/search')->getCollection();
		$collection->getSelect()->join(array('p2q' => $this->_projectToQueryTable), 'main_table.query_id = p2q.query_id', array());
		$collection->addFieldToFilter('p2q.project_id', array('eq' => $project_id))
				   ->addFieldToFilter('main_table.search_published', array('gt' => $from))
				   ->addFieldToFilter('main_table.search_published', array('lt' => $to));
		$ret = $collection->getSize();

        $collection = Mage::getModel('osmm/search')->getCollection();
		$collection->getSelect()->join(array('p2q' => $this->_projectToQueryTable), 'main_table.query_id = p2q.query_id', array())
								->order('search_published DESC')
								->limit(10, $first);
		$collection->addFieldToFilter('p2q.project_id', array('eq' => $project_id))
				   ->addFieldToFilter('main_table.search_published', array('gt' => $from))
				   ->addFieldToFilter('main_table.search_published', array('lt' => $to));

		foreach($collection as $_result){
            /** @var $_result Openstream_Osmm_Model_Search */
			if($_result->getData('search_source') == 'facebook'){
				$image = $this->getSkinUrl('openstream/osmm/images/fb.jpg');
				$url = 'http://www.facebook.com/profile.php?id='.preg_replace('/_.*$/', '', $_result->getData('search_outer_id'));
			}else{
                $url = 'https://twitter.com/#!/'.$_result->getData('search_author_name');
                $image = $_result->getData('search_author_image');
     		}

			$ret .= '<hr>
			 <div class="results-container'.($_result->getData('search_source') == 'facebook' ? ' facebook' : '').'">
			  <div class="left">
			   <a href="'.$url.'" target="_blank" title="'.stripslashes($_result->getData('search_author_name')).'" onclick="blur();">
			    <img src="'.$image.'" alt="'.stripslashes($_result->getData('search_author_name')).'" />
			   </a>
			  </div>
			  <div class="left msg-text">
			   <strong>'.stripslashes($_result->getData('search_author_name')).'</strong>
			   <div class="date">'.date('F jS, Y H:i', $_result->getData('search_published')).'</div>'.substr(strip_tags(stripslashes($_result->getData('search_content'))), 0, 200).'</div>
			  <div class="clear">
			 </div>';
		}	

		return $ret;
	}
}