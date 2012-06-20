<?php

class Openstream_Osmm_Model_Resource_Searchinfluencers extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_searchInfluencersTable;

    protected function _construct()
    {
        $this->_init('osmm/searchinfluencers', 'searchinfluencers_id');
        $this->_searchInfluencersTable = $this->getTable('osmm/searchinfluencers');
    }

    public function saveSearchInfluencers($data){
        if(is_array($data)){
            $data['cnt'] = 1;
            $adapter = $this->_getWriteAdapter();
            $adapter->insertOnDuplicate($this->_searchInfluencersTable, $data, array('cnt' => new Zend_Db_Expr('`cnt` + 1')));
        }
    }
}
