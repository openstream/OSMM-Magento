<?php

class Openstream_Osmm_Model_Search extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
		parent::_construct();
        $this->_init('osmm/search');
    }
 }