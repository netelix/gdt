<?php
class Model_DbTable_Row_Image extends Uop_Model_DbTable_Row_Image
{
  protected $_dependent_attr_map_types = array("tattoo_style");
  protected $_dependent_attr_map_values = array("shares");
  
  public function __construct($o){
	  parent::__construct($o);
  }
  
  // override me
  protected $thumbs = array(
    self::MINI => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 60x60^ -extent 60x60',
    self::SMALL => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 173x177^ -extent 173x177',
    self::MEDIUM => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 250x250^ -extent 250x250',
    self::LARGE => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 640x337^ -extent 640x337',
    self::LARGE_SQUARE => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 640x640^ -extent 640x640',

    self::LARGE_NOWM => '-background white -auto-orient -virtual-pixel tile -gravity center -thumbnail 640x337^ -extent 640x337',
    self::HD_NOWM => '-resize 1280'
    );
    
  public function save(){
  	$row = $this->findRef();
  	if($row instanceOf Uop_Model_DbTable_Row_Organization){
			$this->org_id = $row->id;
  	} else if($row instanceOf Uop_Model_DbTable_Row_Product){
	  	$this->org_id = $row->org_id;
  	}
  	return parent::save();
	}
	
	public function updateFromForm($form)
	{
		$data = $form->getValues();
		$this->ref_type = $data["ref_type"];
		$this->ref_id = $data["ref_id"];
		$this->save();
		return $this->updateAttributesFromForm($form);
	}
	
	public function meta()
	{
		$ref = $this->findRef(); 
		if($ref instanceOf Uop_Model_DbTable_Row_Product){
			$caption = __(":name:, Studio :org_link:", array("name"=>$ref->name(), "org_link"=>$ref->organization()->link()->option("jslink",false)->a()));
		} else if($ref instanceOf Uop_Model_DbTable_Row_Organization){
			$caption = __("Studio :org_link:", array("org_link"=>$ref->link()->option("jslink",false)->a()));
		} else {
			$caption = "";
		}
		return $caption;
	}
	
	public function updateFbShares()
	{
		$url = Link::factory(array("id"=>$this->id), "galery-focus", array("canonical"=>true))->assemble();
		$encoded_url = urlencode($url);
		
		// Création d'une nouvelle ressource cURL
		$ch = curl_init();
		
		// Configuration de l'URL et d'autres options
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/?ids=$encoded_url");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// Récupération de l'URL et affichage sur le naviguateur
		$result = curl_exec($ch);
		$node = json_decode($result)->$url;
		$shares = !empty(json_decode($result)->$url->shares) ? json_decode($result)->$url->shares : 0;
		// Fermeture de la session cURL
		curl_close($ch);
		
		$this->shares($shares);
		return;
	}
}
