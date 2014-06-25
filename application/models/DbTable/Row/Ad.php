<?php

class Model_DbTable_Row_Ad extends Uop_Model_DbTable_Row_Ad
{
	public function images()
	{
		$imgs = array();
		$img_metas = $this->meta("images");
		if(is_array($img_metas)){
			foreach($img_metas as $img){
		    $row = App::table("images")->createRow($img);
		    $row->setReadOnly(true);
		    $imgs[] = $row;
			}			
		}
		return $imgs;
	}
  public function image()
  {
	  return current($this->images());
  }
}

