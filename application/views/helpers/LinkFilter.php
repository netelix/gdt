<?php

class View_Helper_LinkFilter extends Uop_View_Helper_LinkFilter
{
	private $_link;
	private $_checked;
	public function LinkFilter($filters, $r_attr)
	{
		if(empty($filters["attr"])){
			$filters["attr"] = array();
		}
		$key = array_search($r_attr->id, $filters["attr"]);
		if($key !== false){
			unset($filters["attr"][$key]);
		} else {
			$filters["attr"] = array($r_attr->id);		
		}
                $this->_checked = $key!==false ? 'checked="1"':'';
		$this->_link = $this->view->location
			->link(array(
				"filters"=>$filters,
				"label"=> "<input type='checkbox' $this->_checked/> ".$r_attr->name()
			));
		return $this;
	}
	
	public function checked()
	{
		return $this->_checked;
	}
	
	public function __toString()
	{
		return $this->_link->a();
	}
}