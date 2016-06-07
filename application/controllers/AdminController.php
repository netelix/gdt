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
				 // $form->getElement("code")->addErrorMessage(__("Ce code est d�j� utilis�"));
			  }
			  
			  if($this->_isSubmittedAndValid($form)){
				  $r_tattoo = $t_tattoo->createRow();
				  $r_tattoo->setFromArray($form->getValues());
				  $r_tattoo->save();
				  $this->_reload();
			  }
		  }
		}
      
      public function imagesAction()
      {
          $t_image = App::table("images");
          $this->view->preset = $preset = $this->getParam("preset", "all");
          // Building filter request
          $select = $t_image->select()
              ->setIntegrityCheck(false)
              ->from("images")
              ->where("images.ref_type = 'organizations' OR images.ref_type = 'products'")
              ->order("images.id ASC");
            
          $presets["all"] = $select;
            
          $this->view->entities = $this->_paginator($presets[$preset], 50);
          $this->view->presets = $presets;         
      }
      
      public function updatenameAction(){
          
          $t_image = App::table("images");
          $r_image = $t_image->find($_POST['id_image'])->current();
          $r_image->name($_POST['name_image']);
      }

	  public function editInvoiceAction()
	  {
 	   	$this->checkAdmin();
	    if($this->_isAjax()){
		    $this->_helper->layout->disableLayout();
	    }
 	   	$invoice_id = $this->_getParam("invoice_id");
 	   	$user_id = $this->_getParam("user_id");
 	   	
 	   	$t_invoices = App::table("invoices");
 	   	$r_invoice = $t_invoices->find($invoice_id)->current();
 	   	$r_user = App::table("users")->find($user_id)->current();

 	   	if(empty($r_invoice) && empty($r_user)){
	 	   	throw new Exception("User #$user_id and invoice #$invoice_id not found");
 	   	}	   	
 	   	if(!empty($r_invoice) && $r_user->id != $r_invoice->user_id){
	 	   	throw new Exception("Invoice #$invoice_id and does not match with #$user_id");
 	   	}
 	   	
		  $this->view->form = $form = App::form("InvoiceEdit", $r_invoice, array("user"=>$r_user));
		  if($this->_isSubmittedAndValid($form)){
		  	if(empty($r_invoice)){
			  	$r_invoice = $t_invoices->createRow(array("user_id"=>$user_id));
		  	}
		  	$values = $form->getValues();
			  $r_invoice->setFromArray($values);
			  $r_invoice->price_ht = $values["price_ttc"] / 1.20;
			  if($values["type"] == "month"){
				  $date_start = Zend_Date::now()->set($values["date_start"], Zend_Date::ISO_8601);
					$r_invoice->label = "Abonnement mensuel - ".$date_start->get("MMM YYYY");
					$r_invoice->date_next = $date_start->addMonth(1)->get(Zend_Date::ISO_8601);
			  } else {
					$r_invoice->label = "Abonnement annuel";
					$r_invoice->date_next = NULL;
			  }
			  $r_invoice->save();
			  $this->_reload();
		  }
		}

	  public function cancelNextInvoiceAction()
	  {
			$this->checkAdmin();
			$t_invoices = App::table("invoices");
			$r_invoice = $t_invoices->find($this->_getParam("invoice_id"))->current();
			
			if(empty($r_invoice)){
				throw new Exception("#$invoice_id not found");
			}
			$r_invoice->date_next = NULL;
			$r_invoice->save();
			$this->_goBack();
		}

	  public function cancelInvoiceAction()
	  {
			$this->checkAdmin();
			$t_invoices = App::table("invoices");
			$r_invoice = $t_invoices->find($this->_getParam("invoice_id"))->current();
			
			if(empty($r_invoice)){
				throw new Exception("#$invoice_id not found");
			}
			$r_invoice->delete();
			$this->_goBack();
		}
}

