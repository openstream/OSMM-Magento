<?php

class Openstream_Osmm_Model_Search extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
		parent::_construct();
        $this->_init('osmm/search');
    }

    public function getDecoratedContent()
    {
        $text = substr(strip_tags(stripslashes($this->getData('search_content'))), 0, 200);
        $_occurrences = array();
        if (preg_match_all('|https?://[a-z0-9$/\-.+!\'(),]+|ism', $text, $_occurrences)) {
            foreach ($_occurrences[0] as $_occurrence) {
                $text = preg_replace('|'.$_occurrence.'|ism', '<a href="'.$_occurrence.'" target="_blank">'.$_occurrence.'</a>', $text);
            }
            $_occurrences = array();
        }
        if ($this->getData('search_source') == 'twitter' && preg_match_all('|@[a-z0-9_]{1,15}|ism', $text, $_occurrences)) {
            foreach ($_occurrences[0] as $_occurrence) {
                $text = preg_replace('|'.$_occurrence.'|ism', '<a href="https://twitter.com/'.substr($_occurrence, 1).'" target="_blank">'.$_occurrence.'</a>', $text);
            }
            $_occurrences = array();
        }
        if ($this->getData('search_source') == 'twitter' && preg_match_all('|#[a-z0-9]+|ism', $text, $_occurrences)) {
            foreach ($_occurrences[0] as $_occurrence) {
                $text = preg_replace('|'.$_occurrence.'|ism', '<a href="https://twitter.com/search/'.$_occurrence.'" target="_blank">'.$_occurrence.'</a>', $text);
            }
        }
        return $text;
    }

    public function getUrl()
    {
        if ($this->getData('search_source') == 'facebook') {
            return 'http://www.facebook.com/profile.php?id='.preg_replace('/_.*$/', '', $this->getData('search_outer_id'));
        } else {
            return 'https://twitter.com/'.$this->getData('search_author_name');
        }
    }
 }