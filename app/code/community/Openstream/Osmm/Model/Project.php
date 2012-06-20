<?php

class Openstream_Osmm_Model_Project extends Mage_Core_Model_Abstract
{
    protected function _construct(){
		parent::_construct();
        $this->_init('osmm/project');
    }

    public function getKeywords()
    {
        if (!$this->getId()) {
            return array();
        }

        $array = $this->getData('keywords');
        if (is_null($array)) {
            $array = $this->getResource()->getKeywords($this);
            $this->setData('keywords', $array);
        }
        return $array;
    }
}