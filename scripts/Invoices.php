<?php

class Script_Invoices extends Uop_Script_Abstract {
    public function run(){
	    $t_invoices = App::table("invoices");
	    $select = $t_invoices->select()
	    	->where("type = 'month'")
				->where("date_next IS NOT NULL AND date_next < curdate()");
			foreach($select->fetchAll() as $r_invoice){
				$next_date = $r_invoice->date_next;	
				$r_invoice->date_next = null;
				$values = $r_invoice->toArray();
				$values["id"] = null;
				$values["date_start"] = $next_date;
				$values["date_next"] = Zend_Date::now()->set($next_date, Zend_Date::ISO_8601)->addMonth(1)->get(Zend_Date::ISO_8601);
				$this->info("Creating next invoice for $r_invoice->id starting on {$values['date_start']} until {$values['date_next']}");

				$t_invoices->createRow()->setFromArray($values)->save();
				$r_invoice->save();
				$this->info("done");
			}
    }
}

?>