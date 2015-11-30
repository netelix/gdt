<?php

class OrganizationController extends Uop_Controller_Organization
{
	use Trait_Controller;
	
	public function claimTattooboxAction()
	{
		$this->view->org = $r_org = $this->_findResource();
		if(!$r_org->tattoobox){
			throw new Exception("Vous ne faites pas partie du programme tattoobox");
		}
		
		
		$validator = new Zend_Validate_Callback(array($this, 'isTattooboxUsable'));
		$validator->setMessage("Le code n'est pas valide ou déjà utilisé");
		$form = App::form("simple", null);
		$form->addText("code", array(
			"required"=>true,
			"validators"=>array($validator)
		));
		$form->addElement("checkbox", "valid");

		$this->view->form = $form;		
		if($this->_isSubmittedAndValid($form)){
			$r_tattoobox = App::table("tattooboxes")->select()
				->where("code = ?", $form->getValue("code"))
				->fetchRow();
			$this->view->tattoobox = $r_tattoobox;
			$form->addElement("hidden", "tattoobox_id", array("required"=>true));

			if($form->getValue("valid")){
				$r_tattoobox->org_id = $r_org->id;
				$r_tattoobox->datetime = Zend_Date::now()->get(Zend_Date::ISO_8601);
				$r_tattoobox->save();
				$z_mail = new Zend_Mail("UTF-8");
	    	foreach(App::table("users")->select()->where("type='admin'")->fetchAll() as $r_user){
		    	$z_mail->addTo($r_user->email);
		    }
	    	$txt = "La tattoobox $r_tattoobox->id (code $r_tattoobox->code) vient d'être utilisée dans le studio $r_org->id: ".$r_org->name();
	    	$z_mail->setSubject("Tattoobox $r_tattoobox->id");
	    	$z_mail->setBodyText($txt);
	    	$z_mail->send();
	    	$this->view->success = true;
			}
		}
		$this->view->boxes = App::table("tattooboxes")->select()->where("org_id = ?", $r_org->id)->fetchAll();
	}
	
	public function isTattooboxUsable($code)
	{
		return App::table("tattooboxes")->select()
			->where("code = ?", $code)
			->where("org_id IS NULL")
			->fetchRow() !== NULL;
	}
}



