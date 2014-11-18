<?php
class Form_OrganizationEdit extends Uop_Form_OrganizationEdit
{
  protected $_attributesSelect = array('admin_note'=>array(-1=>"-1", 0=>0, 1=>1));
  protected $_attributesCheckbox = array("tattoo_style", "piercing_style");
  
  public function init()
  {
    foreach($this->_attributesSelect as $field=>$listMethod){
      $this->addAttributeSelectElement($field, $listMethod);    
    }
    parent::init();
  	$subform = App::form("Aggregate", $this->_org);
  	$openings = json_decode($this->_org->openingDays());
  	
  	for($x=0;$x<21;$x++){
  		$day = isset($openings[$x]) ? $openings[$x] : null;
      $subform->addSubForm(App::form("OrganizationOpening", $day, array("counter"=>$x)), "openings_$x");
  	}
    $this->addSubForm($subform, "openings");	
  }
}