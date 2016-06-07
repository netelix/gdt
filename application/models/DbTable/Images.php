<?php

class Model_DbTable_Images extends Uop_Model_DbTable_Abstract
{  
    protected $_name = 'images';
    protected $_rowClass = 'Model_DbTable_Row_Image';

    protected $_uopReferenceMap = array("Organization" => array(
    "columns" => array("org_id"),
    "refTableClass" => "Uop_Model_DbTable_Organizations",
    "refColumns" => array("id"))
  );
}