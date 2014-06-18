<?php

class LocationController extends Uop_Controller_Location
{
 	use Trait_Controller;
 	
 	public function showAction()
  {
    parent::showAction();
    $this->view->filters = $filters = $this->_loadFilters();
  }
  
  protected function _filterTypes()
  {
	  return array("tattoo_style"=>__("Style pratiqués"));
  }
  	
	public function galeryAction()
	{
		$loc = App::table("locations")->fetchRow();
		if($img_id = $this->getParam("id")){
			$this->view->img =  $img = App::table("images")->find($img_id)->current();
		}

		$filters = $this->_loadFilters();
		$this->view->current_filters = $current_filters = App::table("ads")->unserializeFilters($this->_getParam('filters'));
		$checkboxes = array();
		foreach($filters as $key=>$filter_data){
			foreach($filter_data["rowset"] as $r_attr){
				$filters = $current_filters;
				if(empty($filters["attr"])){
					$filters["attr"] = array();
				}
				$key = array_search($r_attr->id, $filters["attr"]);
				if($key !== false){
					unset($filters["attr"][$key]);
					$anchor = '<input type="checkbox" checked/> '.$r_attr->name();
				} else {
					$filters["attr"][] = $r_attr->id;
					$anchor = '<input type="checkbox"/> '.$r_attr->name();
				}

				if(empty($filters["attr"])){
					$link = Link::factory(array(), "galery");
				} else {
					$filters = App::table("ads")->serializeFilters($filters);
					$link = Link::factory(array("filters"=>$filters), "galery-with-filters");
				}
				$link->anchor($anchor);
				$checkboxes[] = $link;
			}
		}
		$this->view->filters = $checkboxes;
		$id = App::table("attributes")->findByKey("shares")->id;
		$select = App::table("images")->select()
			->from("images")
			->setIntegrityCheck(false)
			->join("ads", "ads.org_id = images.org_id", array())
			->joinLeft("attr_map", "attr_id = $id AND attr_map.ref_id=images.id AND attr_map.ref_type='images'", array())
			->order("value DESC");
		
		foreach($current_filters as $name=>$value){
      if(!isset($value)){
        continue;
      }
			if(!is_array($value)){
			  $values = array($value);
			} else {
			  $values = $value;
			}
			foreach ($values as $value) {
			  $select->where("images.id IN (SELECT ref_id from attr_map WHERE ref_type='images' AND attr_id IN (?))",$value);
			}
    }
		
		if($this->_isAjax()){
      $this->_helper->layout->disableLayout();
			$this->render("galery-focus");
			return;
		}
		
		$this->view->images = $this->_paginator($select, 44);
	}
}

