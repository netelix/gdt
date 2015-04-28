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
		$r_loc = App::table("locations")->country("FR");
    $this->view->adm2 = $r_loc->getDescendants(null,  array("type"=>Uop_Model_DbTable_Locations::TYPE_ADM2));
		
		if($img_id = $this->getParam("id")){
			$this->view->img =  $img = App::table("images")->find($img_id)->current();
			$pager_link = Link::factory(array(), "galery");
		} else {
			$pager_link = null;
		}

		if($this->_isAjax()){
      $this->_helper->layout->disableLayout();
			$this->render("galery-focus");
			return;
		}

		$filters = $this->_loadFilters();
		$this->view->current_filters = $current_filters = App::table("ads")->unserializeFilters($this->_getParam('filters'));
		$current_styles = array();
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
					$current_styles[] = $r_attr;
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
		$this->view->current_styles = $current_styles;
		$this->view->filters = $checkboxes;
		$select = App::table("images")->selectBestImages();
		
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
		$this->view->images = $this->_paginator($select, 16, $pager_link);
	}
}

