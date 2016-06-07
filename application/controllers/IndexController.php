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

		if(isset($_POST["password"]) && $_POST["password"] == "avoscrayons"){
			$this->_redirect("/creation");
		}

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
  
  public function untattooAction()
  {
	  $form = App::form("Simple", null);
	  $form->addText("email", array("required"=>true));
	  $adm2 = App::table("locations")->country("FR")->getDescendants(null, array("type"=>"adm2"));
	  $adm2_options = array(__("Votre département"));
	  foreach($adm2 as $r_loc){
	  	if($r_loc->name()->__toString()){
 			  $adm2_options[$r_loc->name()->__toString()] = $r_loc->name()->__toString();
	  	}
	  }
	  ksort($adm2_options);
	  $form->addSelect("adm2", array("multiOptions"=>$adm2_options));
	  $element = new Zend_Form_Element_File('image');

	  $element->addValidator('Extension', false, 'jpg,png,gif')
	  	->setDestination(APPLICATION_PATH.'/../tmp');
	  $form->addElement($element, 'image');
		$this->view->contacted = $this->_getParam("submitted");

		if( $this->_isSubmittedAndValid($form)){
			$location = $form->image->getFileName();
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

			if(!empty($location)){
	 			if (!$form->image->receive()) {
			    print "Erreur de réception de fichier";
				}
	 				
				$at = $z_mail->createAttachment(file_get_contents($location));
				$at->type        = 'image/gif';
				$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
				$at->encoding    = Zend_Mime::ENCODING_BASE64;
				$at->filename    = $form->image;
			}

    	$z_mail->send();
    	$this->_gotoUrl($this->getRequest()->getHeader("referer").'?submitted=1');
		}
		$this->view->form = $form;
  }

  public function conciergerieAction()
  {
       $form = App::form("Simple", null);
       $form->addTextArea("description", array("required"=>true));
       $element = new Zend_Form_Element_File('image');
       $element->addValidator('Extension', false, 'jpg,png,gif')
        ->setDestination(APPLICATION_PATH.'/../tmp');
       $form->addElement($element, 'image'); 
       $form->addSelect("size", array("multiOptions"=>array(0=>'xxx'))); 
       $form->addSelect("date", array("multiOptions"=>array(0=>'1 mois'))); 
       $form->addText("firstname",array("required"=>true));
       $form->addText("lastname",array("required"=>true));
       $form->addText("email",array("required"=>true));
       $form->addText("phone",array("required"=>true));
       $form->addText("adress",array("required"=>true));
       $form->addText("postcode",array("required"=>true));
       $form->addText("city",array("required"=>true));
       
       $this->view->contacted = $this->_getParam("submitted");

       if( $this->_isSubmittedAndValid($form)){

           $leadsConciergerie = App::table("leads_conciergerie");
           $newLead = $leadsConciergerie->createRow();

           foreach($form->getValues() as $key=>$value){
               
               switch ($key) {
                   case 'description':
                        $newLead->description = $value;                       
                       break;
                   case 'size':
                        $newLead->size = $value;                       
                       break;
                   case 'date':
                        $newLead->date = $value;                       
                       break;
                   case 'firstname':
                        $newLead->firstname = $value;                       
                       break;
                   case 'lastname':
                        $newLead->lastname = $value;                       
                       break;
                   case 'email':
                        $newLead->email = $value;                       
                       break;
                   case 'phone':
                        $newLead->phone = $value;                       
                       break;   
                   case 'adress':
                        $newLead->adress = $value;                       
                       break;
                   case 'postcode':
                        $newLead->postcode = $value;                       
                       break;
                   case 'city':
                        $newLead->city = $value;                       
                       break;                                                                                                                                                                                                        
                   default:                       
                       break;
               }
           }
           $newLead->save();                           
       }            
       
       $this->view->form = $form;
      
  }

}

