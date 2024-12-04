<?php
namespace Opencart\Admin\Model\Extension\Tmdavailabilitytimeslots\Tmd;
use \Opencart\System\Helper as Helper;
class Timeslot extends \Opencart\System\Engine\Model {
	
	public function install() {
	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."timeslot` (
	`timeslot_id` int(11) NOT NULL AUTO_INCREMENT,
	`product_id` int(11) NOT NULL,
	`time_status` int(11) NOT NULL,  
	 PRIMARY KEY (`timeslot_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."timeslot_ordertimeslot` (
		`ordertime_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`order_product_id` int(11) NOT NULL,
		`tmdstart` int(11) NOT NULL,
		`endtmd` int(11) NOT NULL,
		`tmdday` varchar(255) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`ordertime_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	
	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."timeslot_ordertimeslotcustomer` (
		`orderctime_id` int(11) NOT NULL AUTO_INCREMENT,
		`order_id` int(11) NOT NULL,
		`product_id` int(11) NOT NULL,
		`order_status_id` int(11) NOT NULL,
		`ordertime_id` int(11) NOT NULL,
		`tmdday` varchar(255) NOT NULL,
		`endtmd` varchar(255) NOT NULL,
		`tmdstart` varchar(255) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`orderctime_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."timeslot_timesday` (
		`time_id` int(11) NOT NULL AUTO_INCREMENT,
		`product_id` int(11) NOT NULL,
		`time_end` varchar(255) NOT NULL,
		`time_start` varchar(255) NOT NULL,
		`sort_order` varchar(255) NOT NULL,
    	PRIMARY KEY (`time_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	$this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."timeslot_timesvalue` (
		`time_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`product_id` int(11) NOT NULL,
	    `timeslot_time` varchar(255) NOT NULL
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."timeslot`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."timeslot_ordertimeslot`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."timeslot_ordertimeslotcustomer`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."timeslot_timesday`");
		$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."timeslot_timesvalue`");
	}
	
     	public function addTimeslot($data) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot  WHERE timeslot_id ='1'");
		
		if(!empty($query->row)){
		$this->db->query("UPDATE " . DB_PREFIX . "timeslot SET status = '" . (int)$data['status'] . "' WHERE timeslot_id = '" . (int)$query->row['timeslot_id'] . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_product WHERE timeslot_id = '" . (int)$query->row['timeslot_id'] . "'");
		
		if (isset($data['product_timer'])) {
			foreach ($data['product_timer'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_product SET timeslot_id = '" . (int)$query->row['timeslot_id'] . "', product_id = '" . (int)$product_id . "'");
			}
		}
	    $this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_category WHERE timeslot_id = '" . (int)$query->row['timeslot_id'] . "'");
		if (isset($data['category_timer'])) {
			foreach ($data['category_timer'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_category SET timeslot_id = '" . (int)$query->row['timeslot_id'] . "', category_id = '" . (int)$category_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_timesday WHERE timeslot_id = '" . (int)$query->row['timeslot_id'] . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "timeslot_timesvalue WHERE timeslot_id = '" . (int)$query->row['timeslot_id'] . "'");
		if (isset($data['timesselected'])) {
		foreach ($data['timesselected'] as $timess) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesday SET timeslot_id = '" . (int)$query->row['timeslot_id']  . "',time_from = '" . $this->db->escape($timess['time_from']) . "',time_to = '" . $this->db->escape($timess['time_to']) . "'");
				$time_id = $this->db->getLastId();
				 foreach ($timess['day'] as $language_id => $timeslot_value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesvalue SET time_id = '" . (int)$time_id . "', language_id = '" . (int)$language_id . "', timeslot_id = '" . (int)$query->row['timeslot_id']  . "', timeslot_time = '" . $this->db->escape($timeslot_value['timeslot_time']) . "'");
				}
			}
		}
		} else{
		$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot SET status = '" . (int)$data['status'] . "'");
		$timeslot_id = $this->db->getLastId();
		if (isset($data['product_timer'])) {
			foreach ($data['product_timer'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_product SET timeslot_id = '" . (int)$timeslot_id . "', product_id = '" . (int)$product_id . "'");
			}
		}
		if (isset($data['category_timer'])) {
			foreach ($data['category_timer'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_category SET timeslot_id = '" . (int)$timeslot_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		if (isset($data['timesselected'])) {
		foreach ($data['timesselected'] as $timess) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesday SET timeslot_id = '" . (int)$timeslot_id . "',time_from = '" . $this->db->escape($timess['time_from']) . "',time_to = '" . $this->db->escape($timess['time_to']) . "'");
				$time_id = $this->db->getLastId();
				 foreach ($timess['day'] as $language_id => $timeslot_value) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_timesvalue SET time_id = '" . (int)$time_id . "', language_id = '" . (int)$language_id . "', timeslot_id = '" . (int)$timeslot_id . "', timeslot_time = '" . $this->db->escape($timeslot_value['timeslot_time']) . "'");
				}
			}
		}		
		}
		return $timeslot_id;
	}

	
	public function gettimeslot($timeslot_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "timeslot  WHERE timeslot_id = '1'");
		return $query->row;
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	
	
	
	
	
	public function getProductTimeslot($timeslot_id) {
		$timeslotproduc_data = [];
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_product WHERE timeslot_id = '" . (int)$timeslot_id . "'");
		foreach ($query->rows as $result) {
			$timeslotproduc_data[] = $result['product_id'];
		}
		return $timeslotproduc_data;
	}
	
	public function getProductCategoryTimeslot($timeslot_id) {
		$timeslotcategory_data = [];
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_category WHERE timeslot_id = '" . (int)$timeslot_id . "'");
		foreach ($query->rows as $result) {
			$timeslotcategory_data[] = $result['category_id'];
		}
		return $timeslotcategory_data;
	}
	
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	
	public function getTimeslotValue($timeslot_id) {
		$timeslot_value_data = [];
		$timeslot_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesday WHERE timeslot_id = '" . (int)$timeslot_id . "'");
    	foreach ($timeslot_value_query->rows as $timeslot_value) {
			$timeslot_value_timedata = [];
			$timeslot_value_query_times = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesvalue WHERE time_id = '" . (int)$timeslot_value['time_id'] . "'");
			foreach ($timeslot_value_query_times->rows as $timeslot_value_query_time) {
			  $timeslot_value_timedata[$timeslot_value_query_time['language_id']] = array('timeslot_time' => $timeslot_value_query_time['timeslot_time']);
			}
			$timeslot_value_data[] = [
				'time_id'                      => $timeslot_value['time_id'],
				'timeslot_value_timedata'      => $timeslot_value_timedata,
				'time_to'                      => $timeslot_value['time_to'],
				'time_from'                    => $timeslot_value['time_from']
			];
          
		}
		return $timeslot_value_data;
	}
	
	
	/////Events////////

	/* <!----timeslot start -----> */
				
				
			
		public function getTimeslotdata($ordertime_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslot WHERE ordertime_id='".(int)$ordertime_id ."'");
			return $query->row;
		}
				
				

		public function getOrderTimeslot($order_product_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslot WHERE Order_product_id='".(int)$order_product_id ."'");
			
			return $query->rows;
		}
		public function addOrderTimeslot($order_id,$data) {

			foreach($data['tmdtimeslot'] as $key => $value){
				$query_timeslot=$this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer WHERE  order_id = '" . (int)$order_id . "' and ordertime_id = '" . (int)$value['tmdday']. "'");
				
				$query_day_name=$this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslot WHERE  ordertime_id = '" . (int)$value['tmdday']. "'");
				
                 if (!empty($query_day_name->num_rows)) {
					$tmddayname = $query_day_name->row['tmdday'];
				 }
				if ($query_timeslot->num_rows) {
					if(!empty($value['tmdday'])){
					 $this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_ordertimeslotcustomer SET order_id = '" . (int)$order_id . "',product_id = '" . (int)$value['product_id'] . "',order_status_id = '" . (int)$value['order_status_id'] . "',ordertime_id = '" . (int)$value['tmdday'] . "',tmdday = '" . $this->db->escape($tmddayname) . "',endtmd = '" . $this->db->escape($value['endtmd']) . "',tmdstart = '" . $this->db->escape($value['tmdstart']) . "',date_added = NOW()");
					}
			     }else{
					 if(!empty($value['tmdday'])){
					 $this->db->query("INSERT INTO " . DB_PREFIX . "timeslot_ordertimeslotcustomer SET order_id = '" . (int)$order_id . "',product_id = '" . (int)$value['product_id'] . "',order_status_id = '" . (int)$value['order_status_id'] . "',ordertime_id = '" . (int)$value['tmdday'] . "',tmdday = '" . $this->db->escape($tmddayname) . "',endtmd = '" . $this->db->escape($value['endtmd']) . "',tmdstart = '" . $this->db->escape($value['tmdstart']) . "',date_added = NOW()");
				}
			  }
			 }
			
		}
		
	
		
		public function getOrdertimeslotCustomer($product_id,$order_id) {
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer  WHERE product_id = '" . (int)$product_id . "' and order_id=".$order_id." ORDER BY date_added DESC ");
		return $query->rows;
	}
		
		public function getOrdertimeslotProductdata($order_id) {
			$query = $this->db->query("SELECT  product_id  FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer WHERE  order_id = '" . (int)$order_id . "' group by product_id");
			return $query->rows;
		}
		
		
		
		
	  public function getProducttimeslosst($order_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "order_product p LEFT JOIN " . DB_PREFIX . "timeslot_ordertimeslot pd ON (p.order_product_id = pd.order_product_id) WHERE p.order_id = '" . (int)$order_id . "' group by p.order_product_id ");
		return $query->rows;
	}
		
		
		
		public function getOrdertimeslotProduct($order_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE  order_id = '" . (int)$order_id . "'");
			return $query->rows;
		}
		
		public function getProducttimeslott($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}
	
	
	/* <!----timeslot start -----> */
						
	///product events 

   public function getProducttimeslotstart($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot WHERE product_id = '" . (int)$product_id . "'");
		return $query->row;
	}
   public function getProductTimeslotValue($product_id) {
	$timeslot_value_data  = [];
	$timeslot_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesday WHERE product_id = '" . (int)$product_id . "'");
	foreach ($timeslot_value_query->rows as $timeslot_value) {
		$timeslot_value_timedata = [];
		$timeslot_value_query_times = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesvalue WHERE time_id = '" . (int)$timeslot_value['time_id'] . "'");
		foreach ($timeslot_value_query_times->rows as $timeslot_value_query_time) {
		  $timeslot_value_timedata[$timeslot_value_query_time['language_id']] = array('timeslot_time' => $timeslot_value_query_time['timeslot_time']);
		}
		$timeslot_value_data[] = [
			'time_id'                       => $timeslot_value['time_id'],
			'sort_order'                    => $timeslot_value['sort_order'],
			'timeslot_value_timedata'       => $timeslot_value_timedata,
			'time_end'                      => $timeslot_value['time_end'],
			'time_start'                    => $timeslot_value['time_start']
		];
      
	}
	return $timeslot_value_data;
}
	
	
}
