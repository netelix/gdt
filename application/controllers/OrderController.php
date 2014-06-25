<?php

class OrderController extends Uop_Controller_Order
{
	use Trait_Controller;
	
	public function _getAllowedPaymentMeans($r_user, $for_order_id = null)
	{
		$pms = parent::_getAllowedPaymentMeans($r_user, $for_order_id);
		unset($pms['ogone']);
		return $pms;
	}
}

