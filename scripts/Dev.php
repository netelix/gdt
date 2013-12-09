<?php

class Script_Dev extends Uop_Script_Abstract {
	public function run(){
		foreach(App::table("users")->fetchRow("id =2057 ")->organizations() as $org){
			$org->isComplete();
		}
	}
}

?>