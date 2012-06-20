<?php

class Openstream_Osmm_Model_Resource_Search extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('osmm/search', 'search_id');
    }
}
