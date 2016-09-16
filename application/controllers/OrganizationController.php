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

  protected function manageImageUpload($r_org)
  {
    $req = $this->getRequest();
    switch ($this->getRequest()->getMethod())
    {
      case 'OPTIONS':
      case 'HEAD':
        $this->head();
        break;
      case 'GET':
         break;
      case 'PATCH':
      case 'PUT':
      case 'POST':
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $titles  = $req->getParam('title');

        $adapter->addValidator('Extension', false, 'jpg,png,gif,jpeg');

        $files = $adapter->getFileInfo();
        $res   = array();
        $i = 0;
        foreach ($files as $file => $info) {
          $name  = basename($adapter->getFileName($file));
          $image = App::table('images')->createRowFor($r_org);
          $error = null;

          try {
            $image->mime($adapter->getMimeType($file));
            // file uploaded & is valid
            if (!$adapter->isUploaded($file))
            {
              throw new Exception('File not uploaded');
            }

            $image->import($info['tmp_name']);

            if (!empty($titles) && isset($titles[$i]))
            {
              $image->name(basename($titles[$i]));
            } else {
            	$name = basename($name, ".".$image->format);
              $image->name($name);
            }

            $image->buildBasePathIfNeeded();
            $adapter->setDestination($image->basepath());

            $image->updateThumbs(true);

          } catch( Exception $e ){
            $error = $e->getMessage();
            if (!empty($image->id))
            {
              $image->delete();
            }
            if(APPLICATION_ENV != 'production'){
              throw $e;
            }
          }
          if(isset($error) && APPLICATION_ENV=='production'){
            error_log($e);
            $error = 'Une erreur interne est survenue lors du téléchargement. Veuillez nous en excuser.';
          }
          $res[] = $this->jsonify($image, $error);
        }
        if($r_org instanceOf Uop_Model_DbTable_Row_Organization){
	    	  $r_org->republish();
        }

        $this->_json(array(
          'files' => $res
        ));
        break;

    case 'DELETE':
      $table = App::table('images');
      $row   = $this->_findResource(array('privilege'=>'delete', 'table' => 'images', 'id_field' => 'image_id'));
      $row->delete();

      $response = array('success' => true);
      try {
        $r_org->republish();
      } catch(Exception $e){
        $response["reload"] = true;
      }

      $this->_json($response);
      break;
    }
  }
  
	
  protected function _manageOrganizationDemand($form)
  {
    if($this->getRequest()->isXmlHttpRequest()){
	    $this->manageImageUpload(App::table("messages")->createRow()->set("id",1));
    }
    if($this->_isSubmittedAndValid($form)){
      $r_message = $form->org()->demand($form);
      $t_images = App::table("images");
      foreach($_POST["images"] as $img_id){
	      if($r_image = $t_images->find($img_id)->current()){
	      	$r_image->ref_type = "messages";
	      	$r_image->ref_id = $r_message->id;
	      	$r_image->save();
	      }
      }
      $this->_reload();
    }
  }
}


