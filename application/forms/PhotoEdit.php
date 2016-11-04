<?php

class Form_PhotoEdit extends Uop_Form_Abstract
{
  protected $_image;
  protected $_attributesCheckbox = array("tattoo_style");
  protected $_attributesInputs = array(
  	"price"=>array("type"=>"text"),
  );
  
  function __construct($r_image, $opts){
    $this->_image = $r_image;

    $this->addElement('select', 'ref', array(
      "required"=>true, 
      "multiOptions"=>$opts["references"]
    ));
    foreach($this->_attributesInputs as $name=>$data){
      $this->addElement($data["type"], $name, $data);
    }
    
    $defaults = $this->entityToDefaults($r_image);
    $defaults["ref"] = $r_image->ref_type."_".$r_image->ref_id;     
    
    $this->setDefaults($defaults);

    $this->addTranslatableSubForm($r_image, "names", $r_image->id."[names]", array(
       self::_validatorStringLength(0, 255)
     ));
     
     foreach($this->_attributesCheckbox as $attribute_type){
      $subform = App::form("AttributesCheckboxes", $this->_image, array(
      	"attr_type"=>$attribute_type, 
      	"belongs_to"=>"".$this->_image->id));
      $this->addSubForm($subform, $attribute_type);  
    }

     parent::__construct();
  }

  public function getValues($suppressArrayNotation = false)
  {
    $data = parent::getValues($suppressArrayNotation);
    $ref_parts = explode("_", $data['ref']);
    $data["ref_id"]=array_pop($ref_parts);
    $data["ref_type"]=implode("_", $ref_parts);
    return $data;
  }

}
