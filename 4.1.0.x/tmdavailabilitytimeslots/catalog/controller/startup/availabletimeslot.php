<?php
namespace Opencart\Catalog\Controller\Extension\Tmdavailabilitytimeslots\Startup;
class Availabletimeslot extends \Opencart\System\Engine\Controller {
    public function index(): void {

			require_once(DIR_EXTENSION.'tmdavailabilitytimeslots/system/library/cart/cart.php');
			$this->registry->set('cart', new \Opencart\Extension\Tmdavailabilitytimeslots\System\Library\Cart\cart($this->registry));		
	  	
		
    }	
}
