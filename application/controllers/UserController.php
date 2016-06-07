<?php

class UserController extends Uop_Controller_User
{
	use Trait_Controller;
	
  protected function _sendWelcomeEmail($r_user)
  {
    $mail = App::mail("owner-welcome", array("id"=>$r_user->email, "password"=>$r_user->tmp_password), $r_user);
		$mail->setSubject(__("Bienvenue dans la Galerie du Tatouage"));
		$mail->send();
  }
  
  public function invoicesAction()
  {
   	$this->view->user = $user = $this->_loggedUser();
    $this->view->orders =  $user->invoices();
    $this->view->next_invoices = App::table("invoices")->select()
    	->where("user_id=?", $user->id)
    	->where("date_next IS NOT NULL")
    	->fetchAll();
  }
  
  
  public function showContractAction()
  {
  	$t_invoices = App::table("invoices");
		$this->view->invoice = $t_invoices->find($this->_getParam("invoice_id"))->current();
   	$this->view->user = $r_user = $this->_loggedUser();
   	$this->view->type = $this->_getParam("type");

		if(empty($r_invoice) && empty($r_user)){
			throw new Exception("User #$user_id and invoice #$invoice_id not found");
		}	   	
		if(!empty($r_invoice) && $r_user->id != $r_invoice->user_id){
			throw new Exception("Invoice #$invoice_id and does not match with #$user_id");
		}
	
    $this->_helper->layout->disableLayout();
	  $this->render("show-invoice");
  }

  public function showInvoiceAction()
  {
	  $this->_forward(array("type"=>"invoice", "show-contract"));
  }

 public function showInvoiceTattooboxAction()
  {
  	$t_tattoobox = App::table("tattooboxes");
		$this->view->box = $box = $t_tattoobox->findByCode($this->_getParam("code"))->current();
   	$this->view->org = App::table("organizations")->find($box->org_id)->current();
   	$this->view->user = $r_user = $this->_loggedUser();
	
    $this->_helper->layout->disableLayout();
  }

}

