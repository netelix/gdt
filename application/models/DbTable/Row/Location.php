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
 
 	public function linkHome()
	{
		if($this->type != 'country'){
			throw new Exception("Place #$this->id is not a country");
		}
		if($this->local_id == "FR"){
			return Link::home();
		}
		return Link::factory(array("country"=>strtolower($this->local_name)), "home-country");
	}	
}