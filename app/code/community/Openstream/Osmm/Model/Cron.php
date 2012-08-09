<?php

class Openstream_Osmm_Model_Cron
{
    public function run()
    {
        ini_set('user_agent', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1');

        /** @var $helper Openstream_Osmm_Helper_Data */
        $helper = Mage::helper('osmm');

        /** @var $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $active_keywords = array();

        /** @var $collection Openstream_Osmm_Model_Resource_Query_Collection */
        $collection = Mage::getModel('osmm/query')->getCollection();
        $collection->getSelect()->join(array('p2q' => $resource->getTableName('osmm/projecttoquery')), 'p2q.query_id = main_table.query_id', array())
                                ->join(array('p' => $resource->getTableName('osmm/project')), 'p.project_id = p2q.project_id', array())
                                ->distinct();
        $collection->addFieldToFilter('p.project_status', array('eq' => 1));
        foreach($collection as $keyword){
            /** @var $keyword Openstream_Osmm_Model_Query */
            $active_keywords[] = $keyword->getId();
            /** @var $searchInfluencersResource Openstream_Osmm_Model_Resource_Searchinfluencers */
            $searchInfluencersResource = Mage::getResourceModel('osmm/searchinfluencers');

            /*
             * Twitter
             */
            $last_tweet_id = 0;
            $base_url = 'http://search.twitter.com/search.json';
            $parameters = array(
                'q'             => $keyword->getData('query_q'),
                'geocode'       => $keyword->getData('query_geocode'),
                'rpp'           => 100,
                'result_type'   => 'recent',
                'since_id'      => $keyword->getData('query_last_twitter'),
                'lang'          => $keyword->getData('query_lang')
            );
            $response = $this->_get_file_contents($base_url, $parameters, true);
            while(is_object($response) && isset($response->results) && is_array($response->results) && list(,$entry) = each($response->results)){
                if(!$last_tweet_id){
                    $last_tweet_id = $entry->id_str;
                    $data = array('query_last_twitter' => $last_tweet_id);
                    $keyword->addData($data)->save();
                }
                /** @var $model Openstream_Osmm_Model_Search */
                $model = Mage::getModel('osmm/search');
                $data = array(
                    'query_id' => $keyword->getId(),
                    'search_outer_id' => $entry->id_str,
                    'search_source' => 'twitter',
                    'search_published' => strtotime($entry->created_at),
                    'search_content' => $helper->utf8tohtml($entry->text, true),
                    'search_author_name' => $entry->from_user,
                    'search_author_image' => $entry->profile_image_url
                );
                $model->addData($data)->save();
                $data = array(
                    'query_id' => $keyword->getId(),
                    'search_author_name' => $entry->from_user,
                    'search_source' => 'twitter'
                );
                $searchInfluencersResource->saveSearchInfluencers($data);
            }

            /*
             * Facebook
             */
            $last_facebook_post_time = 0;
            $base_url = 'https://graph.facebook.com/search';
            $parameters = array(
                'q'     => $keyword->getData('query_q'),
                'type'  => 'post',
                'limit' => 100,
                'since' => $keyword->getData('query_last_facebook')
            );
            $response = $this->_get_file_contents($base_url, $parameters, true);
            $alchemy_api_key = Mage::getStoreConfig('reports/osmm/alchemy_api_key');
            $alchemy_bale_url = 'http://access.alchemyapi.com/calls/text/TextGetLanguage';
            $lang = array();
            while(is_object($response) && isset($response->data) && is_array($response->data) && list(,$entry) = each($response->data)){
                $text = $entry->message ? $entry->message : $entry->story;
                if($keyword->getData('query_lang') && $alchemy_api_key){
                    $parameters = array(
                        'apikey'        => $alchemy_api_key,
                        'outputMode'    => 'json',
                        'text'          => $text
                    );
                    $lang = $this->_get_file_contents($alchemy_bale_url, $parameters, true, true);
                }
                if(!$keyword->getData('query_lang') || !$alchemy_api_key || ($keyword->getData('query_lang') && $keyword->getData('query_lang') == $lang['iso-639-1'])){
                    $created_time = strtotime($entry->created_time);
                    if(!$last_facebook_post_time){
                        $last_facebook_post_time = date('n/j/Y H:i:s', $created_time);
                        $data = array('query_last_facebook' => $last_facebook_post_time);
                        $keyword->addData($data)->save();
                    }
                    /** @var $model Openstream_Osmm_Model_Search */
                    $model = Mage::getModel('osmm/search');
                    $data = array(
                        'query_id'              => $keyword->getId(),
                        'search_outer_id'       => $entry->id,
                        'search_source'         => 'facebook',
                        'search_published'      => $created_time,
                        'search_title'          => $entry->name,
                        'search_content'        => $text,
                        'search_author_name'    => $entry->from->name
                    );
                    $model->addData($data)->save();
                    $data = array(
                        'query_id' => $keyword->getId(),
                        'search_author_name' => $entry->from->name,
                        'search_source' => 'facebook'
                    );
                    $searchInfluencersResource->saveSearchInfluencers($data);
                }
            }
        }

        /*
         * Archiving expired entries
         */
        /** @var $collection Openstream_Osmm_Model_Resource_Search */
        $collection = Mage::getModel('osmm/search')->getCollection();
        $collection->addFieldToFilter('query_id', array('in' => $active_keywords))
                   ->addFieldToFilter('search_published', array('lt' => time() - Mage::getStoreConfig('reports/osmm/keep_timeline')*24*3600));
        foreach($collection as $_search){
            /** @var $_search Openstream_Osmm_Model_Search */
            /**
             * TODO: Simplify the following line so 'date' function is not called three times.
             */
            $date = mktime(0, 0, 0, date('n', $_search->getData('search_published')), date('j', $_search->getData('search_published')), date('Y', $_search->getData('search_published')));
            /** @var $searchIndexResource Openstream_Osmm_Model_Resource_Searchindex */
            $searchIndexResource = Mage::getResourceModel('osmm/searchindex');
            $data = array(
                'query_id'      => $_search->getData('query_id'),
                'index_date'    => $date,
                'index_source'  => $_search->getData('search_source')
            );
            $searchIndexResource->updateIndex($data);
            $_search->delete();
        }

        // TODO: 'OPTIMIZE TABLE '.$prefix.'search';

        // TODO: Mailing
    }

    public function _get_file_contents($url, $parameters, $json_decode = false, $json_assoc = false)
    {
        $url = $this->_appendQueryParams($url, $parameters);
        $response = file_get_contents($url);
        if($json_decode){
            $response = @json_decode($response, $json_assoc);
        }
        return $response;
    }

    /**
     * Append the array of parameters to the given URL string
     *
     * @param string $url
     * @param array $params
     * @return string
     */
    protected function _appendQueryParams($url, array $params)
    {
        foreach ($params as $k => $v) {
            if(trim($v)){
                $url .= strpos($url, '?') === false ? '?' : '&';
                $url .= sprintf("%s=%s", $k, urlencode(trim($v)));
            }
        }
        return $url;
    }
}