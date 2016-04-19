<?php

class Model_DbTable_Ads extends Uop_Model_DbTable_Ads
{
	protected $_neighbors_radius = 70;

  protected function _commonSelectLocation(array $filters){
  	$select = parent::_commonSelectLocation($filters);
  	$select->join("organizations", "organizations.id = ads.org_id", array("tattoobox", "status"));
  	return $select;
  }
}