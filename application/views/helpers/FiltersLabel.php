<? 
class View_Helper_FiltersLabel extends Zend_View_Helper_Abstract {
	public function filtersLabel()
	{
		$attr_names = array();
		foreach($this->view->current_filters as $type=>$ids){
			if(!is_array($ids)){
				$ids = array($ids);
			}
			foreach($ids as $attr_id){
				$attr_names[] = App::table("attributes")->find($attr_id)->current()->name();
			}
		}
		$attr_names = implode(", ", $attr_names);
		return $attr_names;
	}
}