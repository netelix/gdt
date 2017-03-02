<?php
class Model_DbTable_Row_Invoice extends Uop_Model_DbTable_Row_Abstract
{
    public function label()
    {
        if($this->type == "month"){
            $date_start = Zend_Date::now()->set($this->date_start, Zend_Date::ISO_8601);
            return __("Abonnement mensuel")." - ".$date_start->get("MMM YYYY", App::lang());
        } else {
            return __("Abonnement annuel");
        }
    }
}