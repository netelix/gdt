<?php

class Form_ProductsEditProduct extends Uop_Form_ProductsEditProduct
{
  protected $_attributesInputs = array();
  protected $_required_if_id = array();

  public function init()
  {
  	parent::init();
  }
}