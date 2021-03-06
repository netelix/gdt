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
        $default_countries = array(
            "es"=>"ES",
            "fr"=>"FR"
        );
        $this->view->country_iso = $country_iso = $default_countries[App::lang()];
        $this->view->country = empty($this->view->country) ? App::table("locations")->country($country_iso) : $this->view->country;
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
			if($r_loc->type == "org"){
				$r_org = App::table("organizations")->findByLocId($r_loc->id)->current();
				$this->_gotoUrl($r_org->link()->assemble());
			}
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