<?php
class Form_OrganizationEdit extends Uop_Form_OrganizationEdit
{
  protected $_attributesSelect = array(
  	'admin_note'=>array(-1=>"-1", null=>0, 1=>1),
  	'no_rdv'=>array( 0 =>"Non", 1 =>"Oui"),
  	'status'=>array("free"=>"Gratuit", "classic"=>"Payant"));
 
  protected $_attributesInputs = array(
  	"since"=>array("type"=>"date"),
  	"size"=>array("type"=>"text"),
  	"founder"=>array("type"=>"text"),
  	"arrhes"=>array("type"=>"text"),
  	"facebook"=>array("type"=>"text"),
  	"guest"=>array("type"=>"text"),
  );

  protected $_attributesCheckbox = array("tattoo_style", "piercing_style");

  
  public function init()
  {
    foreach($this->_attributesSelect as $field=>$listMethod){
      $this->addAttributeSelectElement($field, $listMethod);    
    }
    $this->addElement("checkbox","tattoobox");
    $this->addElement("textarea", "story", array("value"=>$this->_org->story()));
    parent::init();
  	$subform = App::form("Aggregate", $this->_org);
  	$openings = json_decode($this->_org->openingDays());
  	
  	for($x=0;$x<21;$x++){
  		$day = isset($openings[$x]) ? $openings[$x] : null;
      $subform->addSubForm(App::form("OrganizationOpening", $day, array("counter"=>$x)), "openings_$x");
  	}
    $this->addSubForm($subform, "openings");
  }
  
  public function asAdmin($val)
  {
	  if(!$val){
		  $this->removeElement("admin_note");
		  $this->removeElement("status");
	  }
  }
}