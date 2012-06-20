<?php

class Openstream_Osmm_Model_Resource_Query extends Mage_Core_Model_Resource_Db_Abstract
{
    protected $_searchIndexTable,
              $_searchTable,
              $_searchInfluencersTable,
              $_projectQueryTable;

    protected function _construct()
    {
        $this->_init('osmm/query', 'query_id');
        $this->_searchIndexTable = $this->getTable('osmm/searchindex');
        $this->_searchTable = $this->getTable('osmm/search');
        $this->_searchInfluencersTable = $this->getTable('osmm/searchinfluencers');
        $this->_projectQueryTable = $this->getTable('osmm/projecttoquery');
    }

    public function clearStoredData($query_id){
        $adapter = $this->_getWriteAdapter();

        /*
        *  Clearing `search` table entries
        */
        $adapter->delete($this->_searchTable, array(
            'query_id=?' => $query_id
        ));

        /*
         *  Clearing `search_index` table entries
         */
        $adapter->delete($this->_searchIndexTable, array(
            'query_id=?' => $query_id
        ));

        /*
        *  Clearing `search` table entries
        */
        $adapter->delete($this->_searchInfluencersTable, array(
            'query_id=?' => $query_id
        ));
    }

    public function unbindFromAllProjects($query_id)
    {
        $adapter = $this->_getWriteAdapter();
        $adapter->delete($this->_projectQueryTable, array(
            'query_id=?' => $query_id
        ));

    }
}
