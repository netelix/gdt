<?php

class Script_AutoMails extends Uop_Script_Abstract {
	public function run()
	{
		$users = App::table("users")
			->select()
			->setIntegrityCheck(false)
			->from("users")
			->where("mail_tattoobox = 0")
			->join("messages", "ref_type = 'demands' AND messages.user_id = users.id", array())
			->join("events", "events.id = messages.sent_event_id", array("time"))
			->where("DATE(events.time) < DATE_SUB(CURDATE(),INTERVAL 21 DAY)")
			->group("users.id")
			->fetchAll();
			
		foreach($users as $r_user){
			$this->info("Sendind to $r_user->email. (last demand $r_user->time)");
			$r_mail =	App::mail("auto-tattoobox", array("recipient"=>$r_user), $r_user);
			$r_mail->setSubject("Offrir un Tatouage avec TattooMe");
			$r_mail->send();
			$r_user->mail_tattoobox = true;
			$r_user->setReadOnly(false);
			$r_user->save();
			sleep(rand(1,5));
		}
	}
}

?>