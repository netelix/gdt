<?php

class Form_OrganizationPhotosEditPhoto extends Uop_Form_OrganizationPhotosEditPhoto
{
	  protected $_attributesCheckbox = array("tattoo_style");
	  
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
}
