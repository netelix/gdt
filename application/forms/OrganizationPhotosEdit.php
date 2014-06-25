<?php
class Form_OrganizationPhotosEdit extends Uop_Form_OrganizationPhotosEdit {

  private $_org;
  private $_entities;
  private $_allowed_entities;
  
  protected function entitySelectValues($org)
  {
    $entity_select_values["organizations_$org->id"] = __("Le salon");
    foreach($org->products() as $r_product){
      $entity_select_values["products_$r_product->id"] = $r_product->name();
    }
    return $entity_select_values;
  }
}

