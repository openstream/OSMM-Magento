<?php

class Openstream_Osmm_Model_Resource_Search_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('osmm/search');
    }
}
