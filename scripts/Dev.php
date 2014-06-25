<?php

class Script_Dev extends Uop_Script_Abstract {
	public function run(){
		$img = App::table("images")->find(40)->current();
		$img->updateFbShares();
	}
}

?>