<?php

class Openstream_Osmm_Model_Searchinfluencers extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
		parent::_construct();
        $this->_init('osmm/searchinfluencers');
    }

    public function getUrl() {
        if ($this->getData('search_source') == 'facebook') {
            return 'http://www.facebook.com/'.$this->getData('search_author_name');
        } else {
            return 'https://twitter.com/'.$this->getData('search_author_name');
        }
    }
}