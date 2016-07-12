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
       $path = APPLICATION_PATH.'/../public/upload/images/conciergerie';
       $element = new Zend_Form_Element_File('image');
       $element->addValidator('Extension', false, 'jpg,png,gif')
        ->setDestination($path);
       
       $form->addElement($element, 'image'); 
       //$form->addSelect("size", array("multiOptions"=>array(0=>'1 à 10 cm',1=>'10 à 20 cm',2=>'20 à 30 cm',3=>'30 cm et plus'))); 
       //$form->addSelect("date", array("multiOptions"=>array(0=>'1 à 3 mois',1=>'3 à 6 mois',2=>"Plus de 6 mois"))); 
       $form->addText("firstname",array("required"=>true));
       $form->addText("lastname",array("required"=>true));
       $form->addText("email",array("required"=>true));
       $form->addText("phone",array("required"=>true));
       //$form->addText("address",array("required"=>true));
       $form->addText("postcode",array("required"=>true));
       $form->addText("city",array("required"=>true));
       
       $this->view->contacted = $this->_getParam("submitted");

       if( $this->_isSubmittedAndValid($form)){

           $t_conciergeries = App::table("conciergeries");
           $r_conciergerie = $t_conciergeries->createRow();

           foreach($form->getValues() as $key=>$value){
               
               switch ($key) {
                   case 'description':
                        $r_conciergerie->description = $value;                       
                       break;
                   case 'firstname':
                        $r_conciergerie->firstname = $value;                       
                       break;
                   case 'lastname':
                        $r_conciergerie->lastname = $value;                       
                       break;
                   case 'email':
                        $r_conciergerie->email = $value;                       
                       break;
                   case 'phone':
                        $r_conciergerie->phone = $value;                       
                       break;   
                   case 'postcode':
                        $r_conciergerie->postcode = $value;                       
                       break;
                   case 'city':
                        $r_conciergerie->city = $value;                       
                       break;                                                                                                                                                                                                        
                   default:                       
                       break;
               }
           }

           $r_conciergerie->save();

           if($form->image->getFileName() != null && $form->image->getFileName() != ''){
               $extension = pathinfo($form->image->getFileName(), PATHINFO_EXTENSION);
               rename($form->image->getFileName(),$path.'/'.$r_conciergerie->id.'.'.$extension);
               
               $r_conciergerie->filename = $r_conciergerie->id.'.'.$extension;
               $r_conciergerie->save();               
           }
                                                    
       }            
       
       $this->view->form = $form;
      
  }

}

