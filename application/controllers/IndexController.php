<?php

class IndexController extends Uop_Controller_Index
{
	use Trait_Controller;
  public function indexAction()
  {
      // action body
  }
  
  public function creationAction()
  {
	  $form = App::form("Simple");
	  $form->addText("email", array("required"=>true));
		$this->view->contacted = $this->_getParam("submitted");

		if( $this->_isSubmittedAndValid($form)){
	 		$z_mail = new Zend_Mail("UTF-8");
    	foreach(App::table("users")->select()->where("type='admin'")->fetchAll() as $r_user){
	    	$z_mail->addTo($r_user->email);
	    }
    	$z_mail->setSubject("Lead creation");
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
}

