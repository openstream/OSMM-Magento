<?php

class Openstream_Osmm_Model_Projecttoquery extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
		parent::_construct();
        $this->_init('osmm/project_to_query');
    }
}