<?php

class Openstream_Osmm_Model_Resource_Projecttoquery extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('osmm/projecttoquery', 'project_to_query_id');
    }
}
