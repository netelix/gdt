<?php
class Form_UserInfos extends Uop_Form_UserInfos
{
  protected $_attributesInput = array("siret");
  
  public function init()
  {
  	$this->addElement("text", "siret");
    parent::init();
  }
}