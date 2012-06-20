<?php

class Openstream_Osmm_Model_Query extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
		parent::_construct();
        $this->_init('osmm/query');
    }

    /**
     * Delete all stored data for current keyword
     *
     * @return Openstream_Osmm_Model_Query
     */
    public function clearStoredData()
    {
        $this->getResource()->clearStoredData($this->getId());

        return $this;
    }

    /**
     * Delete all project_to_query table records which involved current keyword
     *
     * @return Openstream_Osmm_Model_Query
     */
    public function unbindFromAllProjects()
    {
        $this->getResource()->unbindFromAllProjects($this->getId());

        return $this;
    }
}