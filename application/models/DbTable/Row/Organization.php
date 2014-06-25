<?php
class Model_DbTable_Row_Organization extends Uop_Model_DbTable_Row_Organization
{
  protected $_dependent_attr_map_types = array("tattoo_style");
  protected $_dependent_attr_map_values = array("opening_days","piercing_style");
  
  public function updateFromEdit($form){
  	$openings = array();
	  foreach($form->getSubform("openings")->getSubforms() as $subform){
	  	$values = $subform->getValues();
	  	if(isset($values)){
		  	$openings[] = $values;
	  	}
	  }
  	$openings = array_unique($openings);
  	sort($openings);
	  $this->openingDays(json_encode($openings));
	  parent::updateFromEdit($form);
  }
  
  public function updateImagesFromForm($form)
  {
  	parent::updateImagesFromForm($form);
    foreach($form->getSubforms() as $subform){
      $subform->getImage()->updateAttributesFromForm($subform);
    }
  }
  
  public function lowestPrice()
  {
	  return 0;
  }
  
  protected function extraAdValues()
  {	
  	$values = array();	
		$city = $this->city();
		$styles = $this->attributes("tattoo_style");

		foreach(App::langs() as $lang){
			$r_city = $this->city();
			$values["city_links"][$lang] = $r_city->link()->param("lang",$lang)->assemble();
			$values["city_names"][$lang] = $city->name()->label;
			foreach($styles as $r_attr){
				$values["styles"][$lang][] = $r_attr->name(null, array("lang"=>$lang));
			}
		}
		foreach($this->getAllImages(11) as $r_img){
			$values["images"][] = $r_img->toArray();		
		}
		$values["piercing"] = count($this->attributesList("piercing_style")) > 0;
		foreach($this->products() as $r_product){
			$values["product_names"][] = $r_product->name()->__toString();
		}
		return $values;
  }
  
  public function publish($opts=array())
  {
	  parent::publish($opts);
	  foreach($this->location()->hierarchy() as $r_loc){
	  	$r_loc->setReadOnly(false);
		  if($r_loc->indexed == 0){
		  	$r_loc->setReadOnly(false);
			  $r_loc->indexed = 1;
			  if($r_loc->type == 'city'){
				  $r_loc->score = $r_loc->population + 1;
			  }
			  $r_loc->save();
		  }
	  }
  }
  
  protected function _requiredProductProperties($options=array())
  {
    return array(
      'name'=>__('Nom du tatoueur'),
      'description' => __('Description du tatoueur'));
  }
  
  protected function _atLeastOneProperties($options=array())
  {
    return array(      
      array(
        'method'=>'countProducts',
        'msg'=>'Vous devez ajouter au moins un tatoueur',
        'cat'=>"products"
      ),
      array(
        'method'=>'countImages',
        'msg'=>'Vous devez ajouter au moins une photo',
        'cat'=>'images'
      ));
  }

}