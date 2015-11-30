<?php

class Model_DbTable_Tattooboxes extends Uop_Model_DbTable_Abstract
{  
 	protected $_name = 'tattooboxes';
	protected $_rowClass = 'Model_DbTable_Row_Tattoobox';

	protected $_uopReferenceMap = array("Organization" => array(
    "columns" => array("org_id"),
    "refTableClass" => "Uop_Model_DbTable_Organizations",
    "refColumns" => array("id"))
  );
}