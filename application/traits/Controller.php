<?php

trait Trait_Controller
{
	public function init()
	{
		$this->view->current_url = $this->getRequest()->getRequestUri();
		$this->manageSearchForm();
		if($r_user = $this->_loggedUser(false)){
		  Uop_Link::setGlobalOption("force_hard_links", true);
		}
	}

	public function manageSearchForm()
	{
		if($this->_getParam("error_handler") !== null){
			return;
		}
			
		$this->view->search_form = $form = App::form("adsSearch", $this->getRequest());
		if($this->_isSubmittedAndValid($form)){
			$filters = $form->getFilters();
			$loc_id = $form->getElement("search_loc_id")->getValue();
			$r_loc = App::table("locations")->find($loc_id)->current();

			if(!isset($r_loc)){
				$r_loc = App::table("locations")->findByLocalId("country", "FR");
			}
			
			$style = $form->getValue("tattoo_style");
			if(!empty($style)){
				$filters["attr"]=array($style);
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