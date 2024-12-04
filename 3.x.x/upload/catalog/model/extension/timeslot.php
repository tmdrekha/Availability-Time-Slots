<?php
class ModelExtensionTimeslot extends Model {
	
	public function getProducttimeslotstatus($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot WHERE product_id = '" . (int)$product_id . "' AND time_status = '1'");
		return $query->row;
	    }

    public function getCategory($product_id) {
		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		if(isset($query->row['category_id'])){
		  return $query->row['category_id'];
		}else{
		  return 0;
		}
	  }

	public function getProductsByCategoryId($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "timeslot_timesday td  LEFT JOIN " . DB_PREFIX . "timeslot td2 ON (td.product_id = td2.product_id) WHERE td.product_id = '" . (int)$product_id . "' ORDER BY td.sort_order ");
		return $query->rows;
	   }

	public function getProductsBydisc($time_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesvalue  WHERE time_id = '" . (int)$time_id . "' and language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	   }
	
	
	public function getTimeslotValue($product_id) {
		$timeslot_value_data  = array();
		$timeslot_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesday WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
    	    foreach ($timeslot_value_query->rows as $timeslot_value) {
			$timeslot_value_timedata = array();
			$timeslot_value_query_times = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_timesvalue WHERE time_id = '" . (int)$timeslot_value['time_id'] . "' And language_id = '" . (int)$this->config->get('config_language_id') . "'");
			foreach ($timeslot_value_query_times->rows as $timeslot_value_query_time) {
			  $timeslot_value_timedata[$timeslot_value_query_time['language_id']] = array('timeslot_time' => $timeslot_value_query_time['timeslot_time']);
			}
			    $timeslot_value_data[] = array(
				'time_id'                  => $timeslot_value['time_id'],
				'sort_order'               => $timeslot_value['sort_order'],
				'timeslot_value_timedata'  => $timeslot_value_timedata,
				'time_end'                 => $timeslot_value['time_end'],
				'time_start'               => $timeslot_value['time_start']
			);
          
		}
		return $timeslot_value_data;
	   }
	
	public function getOrdertimeslotCustomer($product_id,$order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer  WHERE product_id = '" . (int)$product_id . "' and order_id='".$order_id."' ORDER BY date_added DESC ");
		return $query->rows;
	    }
		
	public function getOrdertimeslotProductdata($order_id) {
			$query = $this->db->query("SELECT  product_id  FROM " . DB_PREFIX . "timeslot_ordertimeslotcustomer WHERE  order_id = '" . (int)$order_id . "' group by product_id");
			return $query->rows;
		}
		
	public function getProducttimeslot($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		    return $query->row;
	      }	
	
}