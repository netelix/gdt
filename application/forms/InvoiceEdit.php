<?php
class Form_InvoiceEdit extends Uop_Form_Abstract
{
  protected $_attributesInputs = array(
//  	"price_ht"=>"Prix HT",
//  	"city"=>"Ville",
//  	"postcode"=>"Code postal",
//  	"address"=>"Adresse",
  );
  protected $user;
  protected $invoice;
  
  public function __construct($invoice=null, $data)
  {
    $this->invoice = $invoice;
    $this->user = $data["user"];

    parent::__construct($invoice);
  }


  
  public function init()
  {
  	// if is new
    $this->addElement("date","date_start");
    $this->addElement("text","price_ttc");
    $this->addElement("text","city");
    $this->addElement("text","postcode");
    $this->addElement("text","address");
    
    $this->addElement("select","type", array("multioptions"=>array(
    	"year"=>"Facture annuelle",
    	"month"=>"Facture mensuelle")));
    	
    foreach($this->user->organizations() as $r_org){
    	$txt = (string)$r_org->name();
	    $orgs[$txt]=$txt;
    }
    $this->addElement("select","org_label", array("multioptions"=>$orgs));
    foreach($this->_attributesInputs as $name){
      $this->addElement('text', $name);
    }    
    $this->requiresAll();
    // DEFAULT VALUES
    //
    $this->setDefaults($this->entityToDefaults($this->invoice) + array("address"=>$this->user->address, "postcode"=>$this->user->postcode, "city"=>$this->user->city));


    parent::init();
  }
}