<?php

class AdminController extends Uop_Controller_Admin
{
    use Trait_Controller;
    
    protected $_deletable_entities = array("attributes", "tattooboxes");
    
    
    public function tattooboxesAction()
    {
	    $t_tattoo = App::table("tattooboxes");
	    $this->view->preset = $preset = $this->getParam("preset", "all");
	   // Building filter request
	    $select = $t_tattoo->select()
	    	->setIntegrityCheck(false)
	    	->from("tattooboxes")
	    	->order("tattooboxes.id DESC");
	    
	    $presets["all"] = $select;
	    $presets["used"] = clone $select;
	    $presets["used"]->where("org_id IS NOT NULL");
	    $presets["not_used"] = clone $select;
	    $presets["not_used"]->where("org_id IS NULL");
	    
	    $this->view->entities = $this->_paginator($presets[$preset], 20);
	    $this->view->presets = $presets;
    }
    
    public function entityAction()
	  {
	   	$this->checkAdmin();
	    $type = $this->_getParam("table");
	    $id = $this->_getParam("id");
	    if($type == "tattooboxes"){
	    	$this->_forward("tattoobox");
	    } else {
		    parent::entityAction();
	    }
	  }
	  
	  public function tattooboxAction()
	  {
	    if($this->_isAjax()){
		    $this->_helper->layout->disableLayout();
		   // $this->_helper->viewRenderer->setNoRender(TRUE);
	    }

	  
	    $t_tattoo = App::table("tattooboxes");
		  $this->view->form = $form = App::form("simple", null);
		  $form->addText("fname", array("required"=>true));
		  $form->addText("lname", array("required"=>true));
		  $form->addText("code", array("required"=>true));
		  $form->addText("price", array("required"=>true));
		  if($this->_isSubmitted($form)){
			  if($form->getValue("code") != "" && $t_tattoo->select()->where("code = ?", $form->getValue("code"))->fetchRow() !== NULL){
				 // $form->getElement("code")->addErrorMessage(__("Ce code est déjà utilisé"));
			  }
			  
			  if($this->_isSubmittedAndValid($form)){
				  $r_tattoo = $t_tattoo->createRow();
				  $r_tattoo->setFromArray($form->getValues());
				  $r_tattoo->save();
				  $this->_reload();
			  }
		  }
		}
}

