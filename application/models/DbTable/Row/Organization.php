<?php
class Model_DbTable_Row_Organization extends Uop_Model_DbTable_Row_Organization
{
  protected $_dependent_attr_map_types = array(
  	"tattoo_style",
  	);
  protected $_dependent_attr_map_values = array(
  	"opening_days",
  	"piercing_style", 
  	"admin_note",  	
  	"since",
  	"founder",
  	"story",
  	"size",
  	"facebook",
  	"no_rdv",
  	"arrhes",
  	"guest"
  );
  
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
	  $this->story($form->getValue("story"));
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
  
  protected function _additionalAdValues()
  {
    return array("num_products"=>$this->products()->count());
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
				$values["styles"][$lang][] = $r_attr->name(null, array("lang"=>$lang))->__toString();
			}
		}
		foreach($this->getAllImages(11) as $r_img){
			$values["images"][] = $r_img->toArray();		
		}
		$values["piercing"] = count($this->attributesList("piercing_style")) > 0;
		foreach($this->products() as $r_product){
			$values["product_names"][] = $r_product->name()->__toString();
		}
		
		$people_names = array();
		foreach($this->products() as $r_product){
	  	$people_names[] = $r_product->name()->__toString();
	  }
	  if(count($people_names)>1){
		  $last_people_name = array_pop($people_names);
		  $people_names = implode(", ", $people_names).__(" et ").$last_people_name;
	  } else {
		  $people_names = current($people_names);
	  }


		$values["people"] = $people_names;

		return $values;
  }
  
  public function note()
  {
  	$handicap = (int)$this->adminNote();
  	$handicap = empty($handicap) ? 0 : $handicap;
 	  return parent::note()+$handicap*100;
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
	  foreach($this->city()->getNeighbors() as $r_loc){
	  	$r_loc->setReadOnly(false);
		  if($r_loc->indexed == 0){
			  $r_loc->indexed = 1;
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
  	if($this->status == "classic"){
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
  	} else {
	  	return array();
  	}
  }

}