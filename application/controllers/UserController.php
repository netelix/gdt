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
}

