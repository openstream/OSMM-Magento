<?php

class Openstream_Osmm_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function aasort(&$array, $key){
		$sorter = array();
		$ret = array();
		reset($array);
		foreach($array as $ii => $va) {
			$sorter[$ii] = $va[$key];
		}
		arsort($sorter);
		foreach($sorter as $ii => $va) {
			$ret[$ii] = $array[$ii];
		}
		$array = $ret;
	}

    public function getProject(){
        return Mage::registry('current_osmm_project');
    }
}