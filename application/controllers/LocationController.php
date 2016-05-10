<?php

class LocationController extends Uop_Controller_Location
{
 	use Trait_Controller;
 	
 	public function showAction()
  {
    $this->view->location = $r_loc = $this->_findResource(array("privilege"=>"show"));
		$this->view->baselink = preg_replace("/(\/[0-9]+)$/", "", $this->getRequest()->getRequestUri());

    if($redirect = $r_loc->redirectTo()){
      if(!empty($redirect) 
      && ( $redirect_loc = App::table("locations")->find($redirect)->current())
      && ( $redirect_loc->type != "org")
      )
      $this->_redirect($redirect_loc->link()->assemble(), array("code"=>301));
    }
    
    
    $this->view->current_filters = $filters = Uop_Model_DbTable_Row_Location::unserializeFilters($this->_getParam('filters'));
    $this->view->current_sort = $this->_getParam("sort");
            
    $params['filters'] = $filters;
    $params['page'] = $this->_getParam('page');
    $params['sort'] = $this->_getParam('sort');
    
    $link = $r_loc->link($params);
    $this->_checkUrl($link);
    
    
    $this->_loadSideLinks($r_loc);


    
    $select = $this->_loadAds($r_loc, $filters, $this->_getParam('sort'));
    $this->view->filters = $filters = $this->_loadFilters();
  }
  
  public function conventionsAction()
  {
	  	  $form = App::form("Simple");
		  $form->addText("email", array("required"=>true));
			$this->view->contacted = $this->_getParam("submitted");
	
			if( $this->_isSubmittedAndValid($form)){
		 		$z_mail = new Zend_Mail("UTF-8");
	    	foreach(App::table("users")->select()->where("type='admin'")->fetchAll() as $r_user){
		    	$z_mail->addTo($r_user->email);
		    }
	    	$z_mail->setSubject("Lead conventions");
	    	$txt = "";
	    	foreach($form->getValues() as $key=>$value){
		    	$txt.="$key : $value\n";
	    	}
	    	$z_mail->setBodyText($txt);
	    	$z_mail->send();
	    	$this->_gotoUrl($this->getRequest()->getHeader("referer").'?submitted=1');
			}
		$this->view->form = $form;

  }
  
  protected function _filterTypes()
  {
	  return array("tattoo_style"=>__("Style pratiqu�s"));
  }
  	
	public function galeryAction()
	{
		$r_loc = App::table("locations")->country("FR");
    $this->view->adm2 = $r_loc->getDescendants(null,  array("type"=>Uop_Model_DbTable_Locations::TYPE_ADM2));

		if($img_id = $this->getParam("id")){
			$this->view->img =  $img = App::table("images")->find($img_id)->current();
			$pager_link = Link::factory(array(), "galery");
			$this->view->baselink = $pager_link->assemble();			
		} else {
			$this->view->baselink = $this->getRequest()->getRequestUri();
			$pager_link = null;
		}

		if($this->_loggedAdmin() && isset($img)){
			$this->view->form = $form = App::form("AdminPhoto", $img, array($img->ref_type."_".$img->ref_id=>"1"));
			if($this->_isSubmittedAndValid($form)){
        $img->updateFromTranslationForm($form->getSubForm("names"));
		    foreach( $form->getAttributesCheckboxesSubForms() as $attribute_form){
		      $img->updateFromAttributesCheckboxesForm($attribute_form);
		    }
			}
		}
		if($this->_isAjax() && isset($img_id)){
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
				$filter_select[Link::factory(array("filters"=>"attr:$r_attr->id"), "galery-with-filters")->assemble()] = $r_attr->name()->__toString();
			}
		}
		$this->view->current_styles = $current_styles;
		$this->view->filters = $checkboxes;
		$this->view->filters_select = $filter_select;

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
		$this->view->images = $this->_paginator($select, 15, $pager_link);
	}
	
	protected function _loadAds($location, $filters, $sort = null, $limit = null, $ads = "ads", $count_exact = "count_exact", $count_around = "count_around")
  {
  	if(!isset($limit)){
	  	$limit = self::PAGE_NUM_ADS;
  	}
    $t_ads = App::table("Ads");
    
    // If hierarchical (!populated place) loc or city or quarter, get org from this loc
	  $select_exact = $t_ads->selectFromLocation($location, $filters, $sort);

 	  $select_exact_free = $t_ads->selectFromLocation($location, $filters, $sort);
	  $select_exact_free->where("status = 'free'");

	  $num_products_around = 0;
	  if(in_array($location->type, array("city","quarter"))){
  	  $select_exact->where("status = 'classic'");

		  $select_around = $t_ads->selectAroundLocation($location, $filters);
		  $select_around->where("status = 'classic'");
		  $select_around_free = $t_ads->selectAroundLocation($location, $filters);
		  $select_around_free->where("status = 'free'");

      $select = $t_ads->select()
        ->union(array('('.$select_exact.')', '('.$select_around.')', '('.$select_exact_free.')', '('.$select_around_free.')')); 
 		  $select->order("FIELD(status, 'classic', 'free'), distance ASC, ad_note DESC");

        // for the weird stuff : cf. http://stackoverflow.com/a/11579935/1108154
      $tmp = new Zend_Paginator_Adapter_DbTableSelect($select_around);
      $this->view->$count_around = $tmp->count();
    	$num_products_around = $select_around
    		->columns("SUM(num_products) as num_products")
    		->fetchRow()->num_products;
	  } else {
      $select_exact->reset(Zend_Db_Select::ORDER)->order("status DESC")->order("ad_note DESC");
		  $select = $select_exact;
      $this->view->$count_around = 0;
	  }
    $tmp = new Zend_Paginator_Adapter_DbTableSelect($select_exact);
    $this->view->$count_exact = $tmp->count();
    $this->view->$ads = $this->_paginator($select, $limit);
    
    $prod_select = clone $select_exact;
    $prod_select->limit(null);
    $this->view->num_products = $num_products_around + $prod_select->columns("SUM(num_products) as num_products")
    	->fetchRow()->num_products;
    
    return $select;
  }
}

