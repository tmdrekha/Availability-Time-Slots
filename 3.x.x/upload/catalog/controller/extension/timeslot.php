<?php
class ControllerExtensionTimeslot extends Controller {
	public function index() {
		$data['timeslots'] = array();
		$this->load->language('extension/timeslot');
	
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$tmd_timeslot_title = $this->config->get('tmd_timeslot_title');
		
		$heading_title  = $tmd_timeslot_title[$this->config->get('config_language_id')]['title'];
		
		if(!empty($heading_title)){
			$data['text_timeslot']=$tmd_timeslot_title[$this->config->get('config_language_id')]['title'];
		}else{
			$data['text_timeslot']='';
		}

		$tmd_timeslot_slot_to = $this->config->get('tmd_timeslot_slot_to');
		$text_to  = $tmd_timeslot_slot_to[$this->config->get('config_language_id')]['to'];
		
		if(!empty($text_to)){
			$data['text_to']=$tmd_timeslot_slot_to[$this->config->get('config_language_id')]['to'];
		}else{
			$data['text_to']=$this->language->get('text_sloton');
		}

		$tmd_timeslot_slot_from = $this->config->get('tmd_timeslot_slot_from');
		$text_from  = $tmd_timeslot_slot_from[$this->config->get('config_language_id')]['from'];
		
		if(!empty($text_from)){
			$data['text_from']=$tmd_timeslot_slot_from[$this->config->get('config_language_id')]['from'];
		}else{
			$data['text_from']=$this->language->get('text_slotfrom');
		}



		if (isset($this->request->get['path'])) {
			$path  = '';
			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
		} else {
			$category_id = 0;
		}
		
		if(empty($category_id)){
			$category_id = $this->model_extension_timeslot->getCategory($product_id);
		}

		$this->load->model('extension/timeslot');
		$config_status = $this->config->get('tmd_timeslot_status');
		$statusvalue   = $this->model_extension_timeslot->getProducttimeslotstatus($product_id);
		      


        if(!empty($statusvalue['time_status'])){

         $data['timeslots'] = array();

				$timeslotvalues = $this->model_extension_timeslot->getTimeslotValue($product_id);
				foreach($timeslotvalues as $timeslotvalue){
				$timeslottimedata = array();
				foreach ($timeslotvalue['timeslot_value_timedata'] as $language_id => $day) {
					$config_language_id = $this->config->get('config_language_id');
					if(!empty($config_language_id == $language_id)){
						$timeslottimedata[$language_id] = array('timeslot_time'=>$day['timeslot_time']);
					  }				
				    }
					$data['timeslots'][] = array(
					 'times'      => $timeslottimedata,
					 'time_start' => $timeslotvalue['time_start'],
					 'time_end'   => $timeslotvalue['time_end'],
					);
				}   


      }else{

        if(!empty($config_status)){
    			$config_pro_timmer = '';
					$product_timmer    = array();
					$tmd_timeslot_product_timer = $this->config->get('tmd_timeslot_product_timer');
					if (!empty($tmd_timeslot_product_timer)) {
					    foreach ($tmd_timeslot_product_timer as $timer_product_id) {
					        $product_timmer[$timer_product_id] = $timer_product_id;
					    }
					}

					$config_cate_timmer = '';
					$category_timmer    = array(); // Make sure this is initialized as an array
					$tmd_timeslot_category_timer = $this->config->get('tmd_timeslot_category_timer');
					if (isset($tmd_timeslot_category_timer)) {
					    foreach ($tmd_timeslot_category_timer as $timer_category_id) {
					        $category_timmer[$timer_category_id] = $timer_category_id;
					    }
					}
			
		   		if (in_array($product_id,$product_timmer)){ 
				    $tmd_timeslot_slot_timer =  $this->config->get('tmd_timeslot_slot_timer');
						foreach ($tmd_timeslot_slot_timer as $value_results) {
							$timeslot_value_timedata = array();
							foreach ($value_results['days'] as $language_id => $day) {
								$config_language_id = $this->config->get('config_language_id');
								if(!empty($config_language_id == $language_id)){
									$timeslot_value_timedata[$language_id] = array('timeslot_time' => $day['timeslot_time']);
							
								}	
							}
							
	  		    $data['timeslots'][] = array(
						 'times'        => $timeslot_value_timedata,
						 'time_start'   => $value_results['time_start'],
						 'time_end'     => $value_results['time_end'],
					    );
				    }

		    	}else{
		    	  if (in_array($category_id,$category_timmer)){ 

	    	  		$tmd_timeslot_slot_timer =  $this->config->get('tmd_timeslot_slot_timer');

							foreach ($tmd_timeslot_slot_timer as $value_results) {
								$timeslot_value_timedata = array();
								foreach ($value_results['days'] as $language_id => $day) {
								$config_language_id = $this->config->get('config_language_id');
									if(!empty($config_language_id == $language_id)){
									$timeslot_value_timedata[$language_id] = array('timeslot_time' => $day['timeslot_time']);
									
									}
								}
						
			  		    $data['timeslots'][] = array(
								 'times'        => $timeslot_value_timedata,
								 'time_start'   => $value_results['time_start'],
								 'time_end'     => $value_results['time_end'],
					      );
				      }
		    	  }
		    	}    
		  }
		}
		 return $this->load->view('extension/timeslot', $data);
	}
}