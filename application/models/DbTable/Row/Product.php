<?php

class Model_DbTable_Row_Product extends Uop_Model_DbTable_Row_Product
{
  protected $_dependent_attr_map_values = array(
    );
  protected $_dependent_attr_map_types = array(
    );
  protected $_auto_remove_polymorphic_associations = array('attrMap','names', 'descs');

  /**
  * @deprecated use self::delete()
  */
  public function deleteProduct()
  {
    return $this->delete(); 
  }

  public function defaultPrice($value = null)
  {
	  return null;
  }

  public function detailedPrices()
  {
    return new Zend_Db_Table_Rowset(array());
  }

  public function updateFromPricesForm(Uop_Form_Prices $form)
  {
  }
}

