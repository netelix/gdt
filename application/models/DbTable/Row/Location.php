<?php
class Model_DbTable_Row_Location extends Uop_Model_DbTable_Row_Location
{
	public function link(array $opts=null)
	{
		if(isset($opts["page"]) && $opts["page"] == 1){
			unset($opts["page"]);
		}
		if(isset($opts['filters']) && empty($opts['filters']["attr"])){
			unset($opts['filters']["attr"]);
		}

		$link = parent::link($opts);
		if(!empty($opts['filters']) 
			&& key_exists('attr', $opts['filters']) 
			&& count($opts['filters']['attr']) > 1){
			$link->option("jslink", true);
		}
		
		
		
		return $link;
	}
    
}