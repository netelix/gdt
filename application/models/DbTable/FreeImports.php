<?php

class Model_DbTable_FreeImports extends Uop_Model_DbTable_Abstract
{  
    protected $_name = 'free_imports';
    protected $_rowClass = 'Model_DbTable_Row_FreeImport';

    protected $_uopReferenceMap = array("Organization" => array(
    "columns" => array("org_id"),
    "refTableClass" => "Uop_Model_DbTable_Organizations",
    "refColumns" => array("id"))
  );
}