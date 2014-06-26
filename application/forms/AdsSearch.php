<?php

class Form_AdsSearch extends Uop_Form_AdsSearch
{
  protected $_attributesSelect = array();
  protected $_attributesCheckbox = array();
  
  public function init()
  {
	  $this->addAttributeSelectElement("tattoo_style", "tattooStyleList", __("Tous les styles"));      
	  parent::init();	
  }
}