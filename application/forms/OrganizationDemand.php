<?php
class Form_OrganizationDemand extends Uop_Form_OrganizationDemand
{
  public function init() {
	  parent::init();
	  $this->removeElement("number");
	  $this->removeElement("prefix");
  }
}
