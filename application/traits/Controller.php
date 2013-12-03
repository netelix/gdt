<?php

trait Trait_Controller
{
	public function init()
	{
		$this->manageSearchForm();
	}

	public function manageSearchForm()
	{
		if($this->_getParam("error_handler") !== null)
			return;
		$this->view->search_form = $form = App::form("adsSearch", $this->getRequest());
		if($this->_isSubmittedAndValid($form)){
			$filters = $form->getFilters();
			$loc_id = $form->getElement("search_loc_id")->getValue();
			$r_loc = App::table("locations")->find($loc_id)->current();

			if(!isset($r_loc)){
				$r_loc = App::table("locations")->findByLocalId("country", "FR");
			}
			$params = array(
				"filters"=>$filters,
				"sort"=>null,
			);
			$url = $r_loc->link($params)->assemble();
			$this->_gotoUrl($url);
		}
	}
}