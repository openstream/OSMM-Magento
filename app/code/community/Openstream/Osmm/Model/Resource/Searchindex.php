<?php

class Openstream_Osmm_Model_Resource_Searchindex extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_searchIndexTable;

    protected function _construct()
    {
        $this->_init('osmm/searchindex', 'searchindex_id');
        $this->_searchIndexTable = $this->getTable('osmm/searchindex');
    }

    public function updateIndex($data)
    {
        if(is_array($data)){
            $data['index_count'] = 1;
            $adapter = $this->_getWriteAdapter();
            $adapter->insertOnDuplicate($this->_searchIndexTable, $data, array('index_count' => new Zend_Db_Expr('`index_count` + 1')));
        }
    }
}
