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
 
    public function linkGalery($filters=array())
	{
		if($this->type != 'country'){
			throw new Exception("Place #$this->id is not a country");
		}
		if($this->local_id == "FR"){
			return !empty($filters) ?
                Link::factory(array("filters"=>$filters), "galery-with-filters") :
                Link::factory(array(), "galery");
		}
		return !empty($filters) ?
            Link::factory(array("filters"=>$filters, "loc_id"=>$this->id), "galery-country-with-filters") :
            Link::factory(array("loc_id"=>$this->id), "galery-country");
	}	

 
 	public function linkHome()
	{
		if($this->type != 'country'){
			throw new Exception("Place #$this->id is not a country");
		}
		return $this->getTable()->linkHomeCountry($this);
	}

	public function lang()
    {
        return current($this->langs());
    }

	public function langs()
    {
        if($this->type != "country"){
            $country = $this->ancester(array("country"));
        } else {
            $country = $this;
        }
        if(empty($country)){
            return array();
        }
        return explode(",", $country->country_langs);
    }
}