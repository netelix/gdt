<?php

class Model_DbTable_Locations extends Uop_Model_DbTable_Locations
{
	public function linkHomeCountry($r_loc)
	{
		if($r_loc->local_id == "FR"){
			return Link::factory(array("lang"=>"fr"), "home");
		}
		if($r_loc->local_id == "ES"){
            return Link::factory(array("lang"=>"es"), "home-lang");
		}
		if($r_loc->local_id == "CH"){
			return Link::factory(array("country"=>"suisse","lang"=>"fr"), "home-country");
		}
		if($r_loc->local_id == "BE"){
			return Link::factory(array("country"=>"belgique", "lang"=>"fr"), "home-country");
		}
	}

    public static function typeLabel($type){
        switch ($type){
            case self::TYPE_ORG:return __("organisation");
            case self::TYPE_POI:return __("lieu");
            case self::TYPE_ZONE:return __("territoire");
            case self::TYPE_QUARTER:return __("quartier");
            case self::TYPE_CITY:return __("ville");
            case self::TYPE_ADM3:return __("canton");
            case self::TYPE_ADM2:return __("département");
            case self::TYPE_ADM1:return __("région");
            case self::TYPE_COUNTRY:return __("pays");
            case self::TYPE_CONTINENT:return __("continent");
        }
    }
}