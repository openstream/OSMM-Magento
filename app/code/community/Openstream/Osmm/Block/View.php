<?php

class Openstream_Osmm_Block_View extends Mage_Core_Block_Template
{
	public  $_currentProjectId;
    private $_queryTable,
            $_projectToQueryTable;

    public function __construct()
    {
        $this->setTemplate('openstream/osmm/index.phtml');
		
		if($this->getRequest()->getParam('project_id')){
			$this->_currentProjectId = $this->getRequest()->getParam('project_id');		
		}elseif(!$this->_currentProjectId){
			$projects = array_keys($this->getProjects());
            $this->_currentProjectId = $projects[0];
		}

        /** @var $core_resource Mage_Core_Model_Resource */
        $core_resource = Mage::getSingleton('core/resource');
        $this->_projectToQueryTable = $core_resource->getTableName('osmm/projecttoquery');
        $this->_queryTable = $core_resource->getTableName('osmm/query');
    }
	
	public function getProjects()
    {
		$ret = array();
        /** @var $projects Openstream_Osmm_Model_Resource_Project_Collection */
		$projects = Mage::getModel('osmm/project')->getCollection();
        if(!Mage::getStoreConfig('reports/osmm/stats_for_disabled_campaigns')){
            $projects->addFieldToFilter('project_status', array('eq' => 1));
        }
		foreach($projects as $project){
            /** @var $project Openstream_Osmm_Model_Project */
			$ret[$project->getData('project_id')] = $project->getData('project_name').($project->getData('project_status') ? '' : ' ('.$this->__('disabled').')');
		}	
		return $ret;
	}
	
	public function getCurrentProjectId()
    {
		return $this->_currentProjectId;
	}
	
	public function getTopInfluencers($influencers_to_show = 15)
    {
        /** @var $collection Openstream_Osmm_Model_Resource_Searchinfluencers_Collection */
        $collection = Mage::getModel('osmm/searchinfluencers')->getCollection();
		$collection->getSelect()->join(array('p2q' => $this->_projectToQueryTable), 'main_table.query_id = p2q.query_id', array())
								->limit($influencers_to_show)
								->order('main_table.cnt DESC');;
		$collection->addFieldToFilter('p2q.project_id', array('eq' => $this->getCurrentProjectId()));
		return $collection;
	}
	
	public function getProjectName()
    {
		$_project = Mage::getModel('osmm/project')->load($this->getCurrentProjectId());
		return $_project->getData('project_name');
	}
	
	public function getResults()
    {
        /** @var $project Openstream_Osmm_Model_Project */
        $project = Mage::getModel('osmm/project')->load($this->getCurrentProjectId());
		$query_ids = $project->getKeywords();
        $ret = array(
            'results'  => array(),
            'min_date' => 0,
            'max_date' => 0
        );

        /** @var $collection Openstream_Osmm_Model_Resource_Search_Collection */
        $collection = Mage::getModel('osmm/search')->getCollection();
        $collection->getSelect()->join(array('q' => $this->_queryTable), 'main_table.query_id = q.query_id', array('q.query_q', 'q.query_lang'));
        $collection->addFieldToFilter('main_table.query_id', array('in' => $query_ids));
		foreach($collection as $_search){
            /** @var $_search Openstream_Osmm_Model_Search */
            $query_name = $_search->getData('query_q').($_search->getData('query_lang') ? ' ('.$_search->getData('query_lang').')' : '');
			$darr = getdate($_search->getData('search_published'));
			$date = mktime(0, 0, 0, $darr['mon'], $darr['mday'], $darr['year']);
			$ret['results'][$query_name][$date] = isset($ret['results'][$query_name][$date]) ? $ret['results'][$query_name][$date] + 1 : 1;
            $ret['min_date'] = $ret['min_date'] && $ret['min_date'] < $date ? $ret['min_date'] : $date;
			$ret['max_date'] = $ret['max_date'] && $ret['max_date'] > $date ? $ret['max_date'] : $date;
		}

        /** @var $collection Openstream_Osmm_Model_Resource_Searchindex_Collection */
        $collection = Mage::getModel('osmm/searchindex')->getCollection();
        $collection->getSelect()->join(array('q' => $this->_queryTable), 'main_table.query_id = q.query_id', array('q.query_q', 'q.query_lang'));
        $collection->addFieldToFilter('main_table.query_id', array('in' => $query_ids));
		foreach($collection as $_search){
            /** @var $_search Openstream_Osmm_Model_Searchindex */
            $query_name = $_search->getData('query_q').($_search->getData('query_lang') ? ' ('.$_search->getData('query_lang').')' : '');
            if(isset($ret['results'][$query_name][$_search->getData('index_date')])){
				$ret['results'][$query_name][$_search->getData('index_date')] += $_search->getData('index_count');
			}else{
				$ret['results'][$query_name][$_search->getData('index_date')] = $_search->getData('index_count');
			}
            $ret['min_date'] = $ret['min_date'] && $ret['min_date'] < $_search->getData('index_date') ? $ret['min_date'] : $_search->getData('index_date');
            $ret['max_date'] = $ret['max_date'] && $ret['max_date'] > $_search->getData('index_date') ? $ret['max_date'] : $_search->getData('index_date');
		}	

		return $ret;
	}
}