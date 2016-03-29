<?php

class Form_AdminPhoto extends Uop_Form_Abstract
{
   protected $_image;
  protected $_attributesCheckbox = array("tattoo_style");

  function __construct($r_image, $entity_select_values){
    $this->_image = $r_image;

    $this->addElement("hidden", "sort");
    $defaults = $r_image->toArray();
    $this->setDefaults($defaults);

    $this->addTranslatableSubForm($r_image, "names", $r_image->id."[names]", array(
       self::_validatorStringLength(0, 255)
     ));
     parent::__construct();
  }
  
	public function init()
  {
	    parent::init();	 
	  foreach($this->_attributesCheckbox as $attribute_type){
      $subform = App::form("AttributesCheckboxes", $this->_image, array(
      	"attr_type"=>$attribute_type, 
      	"belongs_to"=>"".$this->_image->id));
      $this->addSubForm($subform, $attribute_type);  
    }
  }


  public function getImage()
  {
    return $this->_image;
  }
}
