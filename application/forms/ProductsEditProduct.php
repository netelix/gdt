<?php

class Form_ProductsEditProduct extends Uop_Form_ProductsEditProduct
{
  protected $_attributesInputs = array(
  	"interview"=>array("type"=>"text"),
  	"ink"=>array("type"=>"text"),
  	"conventions"=>array("type"=>"text"),
  	"certificate"=>array("type"=>"checkbox"),
  	"since"=>array("type"=>"date"),
  	"awards"=>array("type"=>"text"),
      "material"=>array("type"=>"text"),

  );
  protected $_attributesCheckbox = array("tattoo_style");
  protected $_required_if_id = array();

  public function init()
  {
		foreach($this->_attributesCheckbox as $attribute_type){
			 $subform = App::form("AttributesCheckboxes",isset($this->product) ? $this->product : App::table("products")->createRow(), array(
			  	"attr_type"=>$attribute_type, 
			  	"belongs_to"=>"product_".$this->counter));
			 $this->addSubForm($subform, $attribute_type);  
		}
  
  	parent::init();
  }
}
